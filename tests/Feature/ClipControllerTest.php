<?php

namespace Tests\Feature;

use App\Models\ClipJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClipControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_clip_status_endpoint_returns_eta_and_elapsed_time(): void
    {
        // 1. Create a manual clip job
        $clipJob = ClipJob::create([
            'is_smart' => false,
            'youtube_url' => 'https://www.youtube.com/watch?v=jNQXAC9IVRw',
            'video_title' => 'Me at the zoo',
            'start_time' => '00:01',
            'end_time' => '00:11', // 10 seconds duration
            'status' => 'processing',
        ]);

        $response = $this->get(route('clipper.status', $clipJob));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'is_smart',
                'status',
                'elapsed_seconds',
                'estimated_duration',
            ]);

        $data = $response->json();
        // Estimated duration should be 15 + max(5, 10 * 0.7) = 15 + 7 = 22
        $this->assertEquals(22, $data['estimated_duration']);
        $this->assertEquals(0, $data['elapsed_seconds']); // Just started
    }

    public function test_smart_clip_parent_job_has_45_seconds_estimate(): void
    {
        $clipJob = ClipJob::create([
            'is_smart' => true,
            'youtube_url' => 'https://www.youtube.com/watch?v=jNQXAC9IVRw',
            'video_title' => 'Me at the zoo',
            'status' => 'processing',
        ]);

        $response = $this->get(route('clipper.status', $clipJob));

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals(45, $data['estimated_duration']);
    }
}
