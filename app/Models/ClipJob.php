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
}

