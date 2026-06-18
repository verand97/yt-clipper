<?php

namespace App\Jobs;

use App\Models\ClipJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class ProcessVideoClip implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 900; // 15 minutes max

    public function __construct(
        public ClipJob $clipJob
    ) {}

    public function handle(): void
    {
        $this->clipJob->refresh();
        if ($this->clipJob->status === 'failed' && $this->clipJob->error_message === 'Dibatalkan oleh pengguna') {
            Log::info("Clip job #{$this->clipJob->id} cancelled before processing.");
            return;
        }

        $this->clipJob->update([
            'status' => 'processing',
            'current_step' => 'Mempersiapkan lingkungan pengerjaan...'
        ]);

        try {
            $outputDir = storage_path('app/public/clips');
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }

            $outputFile = $outputDir . '/clip_' . $this->clipJob->id . '_' . time() . '.mp4';

            // Step 1: Download video using yt-dlp
            $tempVideo = storage_path('app/temp/video_' . $this->clipJob->id . '.mp4');
            $tempDir = storage_path('app/temp');
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // Remove stale temp file from previous attempt
            if (file_exists($tempVideo)) {
                unlink($tempVideo);
            }

            $ytdlpPath = config('services.ytdlp.path', 'yt-dlp');

            // Step 1: Download only the needed section using yt-dlp's --download-sections
            $startSeconds = $this->timeToSeconds($this->clipJob->start_time);
            $endSeconds = $this->timeToSeconds($this->clipJob->end_time);
            $duration = $endSeconds - $startSeconds;

            if ($duration <= 0) {
                throw new \Exception('Invalid time range: end_time must be greater than start_time.');
            }

            $this->clipJob->update(['current_step' => 'Mengunduh segmen video dari YouTube...']);

            $downloadProcess = new Process([
                $ytdlpPath,
                '-f', 'bestvideo[ext=mp4][height<=1080]+bestaudio[ext=m4a]/best[ext=mp4][height<=1080]/best',
                '--merge-output-format', 'mp4',
                '--download-sections', "*{$startSeconds}-{$endSeconds}",
                '--force-keyframes-at-cuts',
                '-o', $tempVideo,
                '--no-playlist',
                '--no-warnings',
                $this->clipJob->youtube_url,
            ]);
            $downloadProcess->setTimeout(600);
            $downloadProcess->run();

            if (!$downloadProcess->isSuccessful()) {
                throw new \Exception('Download failed: ' . $downloadProcess->getErrorOutput());
            }

            if (!file_exists($tempVideo)) {
                throw new \Exception('Download failed: temp video file not found after yt-dlp completed.');
            }

            // Step 2: Re-encode with FFmpeg for clean output
            $this->clipJob->update(['current_step' => 'Memotong dan merender video (FFmpeg)...']);

            $ffmpegPath = config('services.ffmpeg.path', 'ffmpeg');
            $ffmpegProcess = new Process([
                $ffmpegPath,
                '-i', $tempVideo,
                '-c:v', 'libx264',
                '-preset', 'fast',
                '-c:a', 'aac',
                '-y',
                $outputFile,
            ]);
            $ffmpegProcess->setTimeout(600);
            $ffmpegProcess->run();

            if (!$ffmpegProcess->isSuccessful()) {
                throw new \Exception('FFmpeg failed: ' . $ffmpegProcess->getErrorOutput());
            }

            // Clean up temp file
            if (file_exists($tempVideo)) {
                unlink($tempVideo);
            }

            $this->clipJob->update([
                'status' => 'completed',
                'current_step' => null,
                'output_path' => 'clips/' . basename($outputFile),
            ]);

            Log::info("Clip #{$this->clipJob->id} completed successfully: " . basename($outputFile));

        } catch (\Exception $e) {
            Log::error("Clip #{$this->clipJob->id} processing failed: " . $e->getMessage());

            // Clean up temp file on failure too
            $tempVideo = storage_path('app/temp/video_' . $this->clipJob->id . '.mp4');
            if (file_exists($tempVideo)) {
                unlink($tempVideo);
            }

            $this->clipJob->update([
                'status' => 'failed',
                'current_step' => null,
                'error_message' => $e->getMessage(),
            ]);
        }
    }

    private function timeToSeconds(string $time): int
    {
        $parts = explode(':', $time);

        if (count($parts) === 3) {
            return ((int) $parts[0] * 3600) + ((int) $parts[1] * 60) + (int) $parts[2];
        } elseif (count($parts) === 2) {
            return ((int) $parts[0] * 60) + (int) $parts[1];
        }

        return (int) $parts[0];
    }
}
