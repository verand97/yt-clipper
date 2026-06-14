<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clip_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('youtube_url');
            $table->string('video_title')->nullable();
            $table->string('original_duration')->nullable();
            $table->string('start_time'); // format HH:MM:SS
            $table->string('end_time');   // format HH:MM:SS
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->string('output_path')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clip_jobs');
    }
};
