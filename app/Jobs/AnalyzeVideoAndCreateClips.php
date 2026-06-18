<?php

namespace App\Jobs;

use App\Models\ClipJob;
use App\Services\GeminiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class AnalyzeVideoAndCreateClips implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 600; // 10 minutes max

    public function __construct(
        public ClipJob $clipJob,
        public ?string $userApiKey = null
    ) {}

    public function handle(GeminiService $geminiService): void
    {
        $this->clipJob->refresh();
        if ($this->clipJob->status === 'failed' && $this->clipJob->error_message === 'Dibatalkan oleh pengguna') {
            Log::info("Smart Clip parent #{$this->clipJob->id} cancelled before analysis.");
            return;
        }

        $this->clipJob->update([
            'status' => 'processing',
            'current_step' => 'Membaca metadata video dari YouTube...'
        ]);

        try {
            $ytdlpPath = config('services.ytdlp.path', 'yt-dlp');
            $tempDir = storage_path('app/temp');
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // Step 1: Get metadata using yt-dlp --dump-json
            Log::info("Fetching metadata for Smart Clip #{$this->clipJob->id}");
            $metaProcess = new Process([
                $ytdlpPath,
                '--dump-json',
                '--skip-download',
                '--no-playlist',
                '--no-warnings',
                $this->clipJob->youtube_url,
            ]);
            $metaProcess->setTimeout(120);
            $metaProcess->run();

            if (!$metaProcess->isSuccessful()) {
                throw new \Exception('Failed to fetch video metadata: ' . $metaProcess->getErrorOutput());
            }

            $metadata = json_decode($metaProcess->getOutput(), true);
            if (!$metadata) {
                throw new \Exception('Failed to parse video metadata JSON.');
            }

            $videoTitle = $metadata['title'] ?? 'Video YouTube';
            $durationSeconds = (int) ($metadata['duration'] ?? 0);
            $chapters = $metadata['chapters'] ?? [];

            $this->clipJob->update([
                'video_title' => $videoTitle,
                'original_duration' => $this->secondsToTime($durationSeconds),
            ]);

            if ($durationSeconds <= 0) {
                throw new \Exception('Invalid video duration detected.');
            }

            // Step 2: Download subtitles/transcripts
            $this->clipJob->update(['current_step' => 'Mengunduh subtitle & transkrip video...']);
            Log::info("Downloading subtitles for Smart Clip #{$this->clipJob->id}");
            $subPrefix = 'sub_' . $this->clipJob->id;
            $subOutputPath = $tempDir . '/' . $subPrefix;

            // Clear old subtitle files if any
            $this->cleanupSubtitleFiles($tempDir, $subPrefix);

            $subProcess = new Process([
                $ytdlpPath,
                '--write-auto-subs',
                '--write-subs',
                '--skip-download',
                '--sub-lang', 'id,en',
                '--output', $subOutputPath,
                '--no-playlist',
                '--no-warnings',
                $this->clipJob->youtube_url,
            ]);
            $subProcess->setTimeout(120);
            $subProcess->run(); // It might return non-zero if no subtitles are found, so we won't throw exception immediately

            // Locate downloaded subtitle file
            $subFiles = glob($tempDir . '/' . $subPrefix . '.*.vtt');
            $transcript = '';

            if (!empty($subFiles)) {
                // Prefer indonesian subtitle if available
                $selectedSubFile = $subFiles[0];
                foreach ($subFiles as $file) {
                    if (str_contains($file, '.id.')) {
                        $selectedSubFile = $file;
                        break;
                    }
                }

                Log::info("Found subtitle file: " . basename($selectedSubFile) . ". Parsing transcript.");
                $transcript = $this->parseVttFile($selectedSubFile);
                
                // Cleanup subtitle files
                $this->cleanupSubtitleFiles($tempDir, $subPrefix);
            } else {
                Log::info("No subtitles found for Smart Clip #{$this->clipJob->id}");
            }

            // Step 3: Find key moments using Gemini or fallbacks
            $this->clipJob->update(['current_step' => 'Menganalisis isi video menggunakan Gemini AI...']);
            $keyMoments = null;
            $usedMethod = 'Gemini AI';

            // Try Gemini AI first if transcript is available OR chapters are available
            if (!empty($transcript) || !empty($chapters)) {
                $keyMoments = $geminiService->findKeyMoments(
                    transcript: $transcript,
                    chapters: $chapters,
                    videoTitle: $videoTitle,
                    durationSeconds: $durationSeconds,
                    customPrompt: $this->clipJob->smart_prompt,
                    minDuration: $this->clipJob->min_duration,
                    maxClips: $this->clipJob->max_clips,
                    userApiKey: $this->userApiKey
                );
            }

            // Fallback 1: YouTube native chapters (if Gemini failed/skipped but chapters exist)
            if (empty($keyMoments) && !empty($chapters)) {
                Log::info("Fallback to YouTube native chapters for Smart Clip #{$this->clipJob->id}");
                $usedMethod = 'YouTube Chapters';
                $keyMoments = [];
                
                foreach ($chapters as $index => $ch) {
                    $start = (int) ($ch['start_time'] ?? 0);
                    $end = (int) ($ch['end_time'] ?? 0);
                    $dur = $end - $start;

                    if ($dur >= $this->clipJob->min_duration) {
                        $keyMoments[] = [
                            'start_time' => $this->secondsToTime($start),
                            'end_time' => $this->secondsToTime($end),
                            'title' => $ch['title'] ?? ('Bagian ' . ($index + 1))
                        ];
                    }

                    if (count($keyMoments) >= $this->clipJob->max_clips) {
                        break;
                    }
                }
            }

            // Fallback 2: Automated Equal Segments (if chapters failed/skipped and Gemini failed/skipped)
            if (empty($keyMoments)) {
                Log::info("Fallback to Equal Time Segments for Smart Clip #{$this->clipJob->id}");
                $usedMethod = 'Interval Waktu';
                $keyMoments = [];
                
                $clipCount = $this->clipJob->max_clips;
                $clipDuration = max(30, $this->clipJob->min_duration); // default to 30s or min_duration
                
                // Exclude first 5% and last 5% of video to avoid intros/outros
                $startBound = (int) ($durationSeconds * 0.05);
                $endBound = (int) ($durationSeconds * 0.95);
                $availableDuration = $endBound - $startBound;

                if ($availableDuration > ($clipCount * $clipDuration)) {
                    $interval = (int) ($availableDuration / $clipCount);
                    for ($i = 0; $i < $clipCount; $i++) {
                        $start = $startBound + ($i * $interval);
                        $end = $start + $clipDuration;
                        
                        $keyMoments[] = [
                            'start_time' => $this->secondsToTime($start),
                            'end_time' => $this->secondsToTime($end),
                            'title' => "Highlight Bagian " . ($i + 1)
                        ];
                    }
                } else {
                    // If video is short, just make one clip of the whole middle section
                    $start = max(0, (int)(($durationSeconds - $clipDuration) / 2));
                    $end = min($durationSeconds, $start + $clipDuration);
                    if ($end - $start >= $this->clipJob->min_duration) {
                        $keyMoments[] = [
                            'start_time' => $this->secondsToTime($start),
                            'end_time' => $this->secondsToTime($end),
                            'title' => "Highlight Video"
                        ];
                    }
                }
            }

            if (empty($keyMoments)) {
                throw new \Exception('Failed to generate any key moments for clipping.');
            }

            Log::info("Smart Clip #{$this->clipJob->id} generated " . count($keyMoments) . " clips using method: {$usedMethod}");

            // Step 4: Create Child Clip Jobs
            $this->clipJob->update(['current_step' => 'Membuat antrean potongan klip segmentasi...']);
            foreach ($keyMoments as $moment) {
                $childClip = ClipJob::create([
                    'parent_id' => $this->clipJob->id,
                    'is_smart' => false,
                    'youtube_url' => $this->clipJob->youtube_url,
                    'video_title' => $this->clipJob->video_title . ' - ' . $moment['title'],
                    'original_duration' => $this->clipJob->original_duration,
                    'start_time' => $moment['start_time'],
                    'end_time' => $moment['end_time'],
                    'status' => 'pending',
                ]);

                // Dispatch single clip processing job
                ProcessVideoClip::dispatch($childClip);
            }

            $this->clipJob->update([
                'status' => 'completed',
                'current_step' => null,
                'error_message' => "Metode: {$usedMethod}", // Save the method used in the error message or note field
            ]);

            Log::info("Smart Clip parent #{$this->clipJob->id} finished analysis and dispatched children.");

        } catch (\Exception $e) {
            Log::error("Smart Clip #{$this->clipJob->id} failed: " . $e->getMessage());
            
            // Clean up files in case of exception
            $tempDir = storage_path('app/temp');
            $subPrefix = 'sub_' . $this->clipJob->id;
            $this->cleanupSubtitleFiles($tempDir, $subPrefix);

            $this->clipJob->update([
                'status' => 'failed',
                'current_step' => null,
                'error_message' => $e->getMessage(),
            ]);
        }
    }

    private function cleanupSubtitleFiles(string $dir, string $prefix): void
    {
        $files = glob($dir . '/' . $prefix . '.*');
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }

    private function parseVttFile(string $filePath): string
    {
        if (!file_exists($filePath)) {
            return '';
        }

        $content = file_get_contents($filePath);
        $lines = explode("\n", $content);
        $parsedText = [];
        $currentText = '';
        $lastTimeStr = '00:00';

        foreach ($lines as $line) {
            $line = trim($line);
            
            if (empty($line) || 
                str_contains($line, 'WEBVTT') || 
                str_contains($line, 'Kind:') || 
                str_contains($line, 'Language:') || 
                str_contains($line, 'Style:') ||
                str_contains($line, 'Position:') ||
                str_starts_with($line, 'NOTE')
            ) {
                continue;
            }

            // Match timestamps: 00:01:20.123 --> 00:01:23.456 or 01:20.123 --> 01:23.456
            if (preg_match('/(?:(\d{2}):)?(\d{2}):(\d{2})\.\d{3}\s+-->\s+/', $line, $matches)) {
                $minutes = $matches[2];
                $seconds = $matches[3];
                $lastTimeStr = "{$minutes}:{$seconds}";
                continue;
            }

            if (!empty($line) && !is_numeric($line)) {
                $cleanLine = strip_tags($line);
                $cleanLine = preg_replace('/<[^>]*>/', '', $cleanLine); // strip remaining html tag fragments
                $cleanLine = trim($cleanLine);

                if (!empty($cleanLine)) {
                    // Avoid duplicate lines (very common in VTT scrolling auto-captions)
                    if ($cleanLine === $currentText || (strlen($currentText) > 0 && str_contains($currentText, $cleanLine))) {
                        continue;
                    }
                    $parsedText[] = "[{$lastTimeStr}] {$cleanLine}";
                    $currentText = $cleanLine;
                }
            }
        }

        return implode("\n", $parsedText);
    }

    private function secondsToTime(int $seconds): string
    {
        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);
        $s = $seconds % 60;

        if ($h > 0) {
            return sprintf('%02d:%02d:%02d', $h, $m, $s);
        }
        return sprintf('%02d:%02d', $m, $s);
    }
}
