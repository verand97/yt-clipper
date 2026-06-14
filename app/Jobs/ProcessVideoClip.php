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

    public int $tries = 1;
    public int $timeout = 600; // 10 minutes max

    public function __construct(
        public ClipJob $clipJob
    ) {}

    public function handle(): void
    {
        $this->clipJob->update(['status' => 'processing']);

        try {
            $outputDir = storage_path('app/public/clips');
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }

            $outputFile = $outputDir . '/' . 'clip_' . $this->clipJob->id . '_' . time() . '.mp4';

            // Step 1: Download video using yt-dlp
            $tempVideo = storage_path('app/temp/video_' . $this->clipJob->id . '.mp4');
            $tempDir = storage_path('app/temp');
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $ytdlpPath = env('YTDLP_PATH', 'yt-dlp');
            $downloadProcess = new Process([
                $ytdlpPath,
                '-f', 'bestvideo[ext=mp4]+bestaudio[ext=m4a]/best[ext=mp4]/best',
                '--merge-output-format', 'mp4',
                '-o', $tempVideo,
                '--no-playlist',
                $this->clipJob->youtube_url,
            ]);
            $downloadProcess->setTimeout(300);
            $downloadProcess->run();

            if (!$downloadProcess->isSuccessful()) {
                throw new \Exception('Download failed: ' . $downloadProcess->getErrorOutput());
            }

            // Step 2: Trim with FFmpeg
            $startSeconds = $this->timeToSeconds($this->clipJob->start_time);
            $endSeconds = $this->timeToSeconds($this->clipJob->end_time);
            $duration = $endSeconds - $startSeconds;

            $ffmpegPath = env('FFMPEG_PATH', 'ffmpeg');
            $ffmpegProcess = new Process([
                $ffmpegPath,
                '-i', $tempVideo,
                '-ss', (string) $startSeconds,
                '-t', (string) $duration,
                '-c:v', 'libx264',
                '-c:a', 'aac',
                '-y',
                $outputFile,
            ]);
            $ffmpegProcess->setTimeout(300);
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
                'output_path' => 'clips/' . basename($outputFile),
            ]);

        } catch (\Exception $e) {
            Log::error('Clip processing failed: ' . $e->getMessage());

            $this->clipJob->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }
    }

    private function timeToSeconds(string $time): int
    {
        $parts = explode(':', $time);

        if (count($parts) === 3) {
            return ($parts[0] * 3600) + ($parts[1] * 60) + $parts[2];
        } elseif (count($parts) === 2) {
            return ($parts[0] * 60) + $parts[1];
        }

        return (int) $parts[0];
    }
}
