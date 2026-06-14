<?php

namespace App\Console\Commands;

use App\Models\ClipJob;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CleanupOldClips extends Command
{
    protected $signature = 'clips:cleanup {--hours=24 : Delete clips older than this many hours}';
    protected $description = 'Remove old clip files from storage to free up disk space';

    public function handle(): int
    {
        $hours = (int) $this->option('hours');
        $cutoff = Carbon::now()->subHours($hours);

        $oldClips = ClipJob::where('status', 'completed')
            ->where('created_at', '<', $cutoff)
            ->whereNotNull('output_path')
            ->get();

        $deleted = 0;

        foreach ($oldClips as $clip) {
            $filePath = storage_path('app/public/' . $clip->output_path);

            if (file_exists($filePath)) {
                unlink($filePath);
                $deleted++;
            }

            $clip->update(['output_path' => null]);
        }

        // Also clean up failed/old pending jobs
        ClipJob::where('created_at', '<', $cutoff)
            ->whereIn('status', ['failed', 'pending'])
            ->delete();

        $this->info("Cleaned up {$deleted} clip files and removed old records.");

        return Command::SUCCESS;
    }
}
