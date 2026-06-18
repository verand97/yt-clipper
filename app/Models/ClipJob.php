<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClipJob extends Model
{
    protected $fillable = [
        'parent_id',
        'is_smart',
        'youtube_url',
        'video_title',
        'original_duration',
        'start_time',
        'end_time',
        'smart_prompt',
        'min_duration',
        'max_clips',
        'status',
        'current_step',
        'output_path',
        'error_message',
    ];

    public function parent()
    {
        return $this->belongsTo(ClipJob::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ClipJob::class, 'parent_id');
    }

    public function getEstimatedDuration(): int
    {
        if ($this->is_smart && !$this->parent_id) {
            return 45; // 45 seconds for Smart Analysis
        }

        $start = $this->timeToSeconds($this->start_time ?? '00:00');
        $end = $this->timeToSeconds($this->end_time ?? '00:00');
        $duration = max(0, $end - $start);
        return 15 + max(5, intval($duration * 0.7));
    }

    public function getElapsedSeconds(): int
    {
        if ($this->status !== 'processing') {
            return 0;
        }
        return now()->diffInSeconds($this->updated_at);
    }

    private function timeToSeconds(string $time): int
    {
        $parts = array_reverse(explode(':', $time));
        $secs = 0;
        if (isset($parts[0])) {
            $secs += intval($parts[0]);
        }
        if (isset($parts[1])) {
            $secs += intval($parts[1]) * 60;
        }
        if (isset($parts[2])) {
            $secs += intval($parts[2]) * 3600;
        }
        return $secs;
    }
}

