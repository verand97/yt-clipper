<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clip_jobs', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('id')->index();
            $table->boolean('is_smart')->default(false)->after('parent_id');
            $table->string('smart_prompt')->nullable()->after('end_time');
            $table->integer('min_duration')->default(15)->after('smart_prompt');
            $table->integer('max_clips')->default(5)->after('min_duration');
            
            // Make start_time and end_time nullable for parent smart clip jobs
            $table->string('start_time')->nullable()->change();
            $table->string('end_time')->nullable()->change();
            
            $table->foreign('parent_id')->references('id')->on('clip_jobs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('clip_jobs', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'is_smart', 'smart_prompt', 'min_duration', 'max_clips']);
            
            // Revert start_time and end_time to non-nullable (only if needed, but in SQLite it can be restrictive)
            $table->string('start_time')->nullable(false)->change();
            $table->string('end_time')->nullable(false)->change();
        });
    }
};
