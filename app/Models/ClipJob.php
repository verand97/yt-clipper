<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClipJob extends Model
{
    protected $fillable = [
        'youtube_url',
        'video_title',
        'original_duration',
        'start_time',
        'end_time',
        'status',
        'output_path',
        'error_message',
    ];
}
