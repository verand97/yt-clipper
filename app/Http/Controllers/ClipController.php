<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessVideoClip;
use App\Models\ClipJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClipController extends Controller
{
    public function index()
    {
        // Get only parent jobs and standalone jobs (parent_id is null)
        $clips = ClipJob::with('children')
            ->whereNull('parent_id')
            ->latest()
            ->take(20)
            ->get();

        return view('clipper.index', compact('clips'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'youtube_url' => ['required', 'url', 'regex:/^(https?:\/\/)?(www\.)?(youtube\.com\/(watch\?v=|shorts\/)|youtu\.be\/).+$/i'],
            'is_smart' => ['sometimes', 'boolean'],
            'start_time' => ['required_unless:is_smart,1', 'nullable', 'regex:/^(\d{1,2}:)?\d{1,2}:\d{2}$/'],
            'end_time' => ['required_unless:is_smart,1', 'nullable', 'regex:/^(\d{1,2}:)?\d{1,2}:\d{2}$/'],
            'smart_prompt' => ['nullable', 'string', 'max:255'],
            'min_duration' => ['nullable', 'integer', 'min:15', 'max:300'],
            'max_clips' => ['nullable', 'integer', 'min:1', 'max:10'],
            'gemini_api_key' => ['nullable', 'string', 'max:100'],
        ], [
            'youtube_url.regex' => 'Format URL YouTube tidak valid.',
            'start_time.required_unless' => 'Waktu mulai wajib diisi jika tidak menggunakan Klip Pintar.',
            'start_time.regex' => 'Format waktu harus HH:MM:SS atau MM:SS.',
            'end_time.required_unless' => 'Waktu akhir wajib diisi jika tidak menggunakan Klip Pintar.',
            'end_time.regex' => 'Format waktu harus HH:MM:SS atau MM:SS.',
            'min_duration.min' => 'Durasi minimal adalah 15 detik.',
        ]);

        $isSmart = isset($validated['is_smart']) && $validated['is_smart'] == '1';

        if ($isSmart) {
            // Save API key in session if provided
            if (!empty($validated['gemini_api_key'])) {
                session(['gemini_api_key' => $validated['gemini_api_key']]);
            }

            $clipJob = ClipJob::create([
                'is_smart' => true,
                'youtube_url' => $validated['youtube_url'],
                'smart_prompt' => $validated['smart_prompt'] ?? null,
                'min_duration' => $validated['min_duration'] ?? 15,
                'max_clips' => $validated['max_clips'] ?? 5,
                'status' => 'pending',
            ]);

            \App\Jobs\AnalyzeVideoAndCreateClips::dispatch($clipJob, session('gemini_api_key'));

            return redirect()->route('clipper.index')->with('success', 'Klip Pintar sedang menganalisis video dan membuat segmentasi! Silakan tunggu.');
        }

        // Validate that end_time > start_time for manual clipping
        $startSeconds = $this->timeToSeconds($validated['start_time']);
        $endSeconds = $this->timeToSeconds($validated['end_time']);

        if ($endSeconds <= $startSeconds) {
            return back()->withErrors(['end_time' => 'Waktu akhir harus lebih besar dari waktu mulai.'])->withInput();
        }

        if (($endSeconds - $startSeconds) > 1800) {
            return back()->withErrors(['end_time' => 'Durasi clip maksimal 30 menit.'])->withInput();
        }

        $clipJob = ClipJob::create([
            'is_smart' => false,
            'youtube_url' => $validated['youtube_url'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'status' => 'pending',
        ]);

        ProcessVideoClip::dispatch($clipJob);

        return redirect()->route('clipper.index')->with('success', 'Video sedang diproses! Silakan tunggu beberapa saat.');
    }

    public function status(ClipJob $clipJob)
    {
        $children = [];
        if ($clipJob->is_smart) {
            $children = $clipJob->children()->get()->map(function ($child) {
                return [
                    'id' => $child->id,
                    'status' => $child->status,
                    'video_title' => $child->video_title,
                    'start_time' => $child->start_time,
                    'end_time' => $child->end_time,
                    'output_path' => $child->output_path,
                    'error_message' => $child->error_message,
                    'download_url' => $child->status === 'completed' ? route('clipper.download', $child) : null,
                ];
            });
        }

        return response()->json([
            'id' => $clipJob->id,
            'is_smart' => $clipJob->is_smart,
            'status' => $clipJob->status,
            'video_title' => $clipJob->video_title,
            'original_duration' => $clipJob->original_duration,
            'output_path' => $clipJob->output_path,
            'error_message' => $clipJob->error_message,
            'children' => $children,
        ]);
    }

    public function download(ClipJob $clipJob)
    {
        if ($clipJob->status !== 'completed' || !$clipJob->output_path) {
            abort(404, 'File belum tersedia.');
        }

        $filePath = storage_path('app/public/' . $clipJob->output_path);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download($filePath);
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
