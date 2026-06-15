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
        $clips = ClipJob::latest()->take(20)->get();
        return view('clipper.index', compact('clips'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'youtube_url' => ['required', 'url', 'regex:/^(https?:\/\/)?(www\.)?(youtube\.com\/(watch\?v=|shorts\/)|youtu\.be\/).+$/i'],
            'start_time' => ['required', 'regex:/^(\d{1,2}:)?\d{1,2}:\d{2}$/'],
            'end_time' => ['required', 'regex:/^(\d{1,2}:)?\d{1,2}:\d{2}$/'],
        ], [
            'youtube_url.regex' => 'Format URL YouTube tidak valid.',
            'start_time.regex' => 'Format waktu harus HH:MM:SS atau MM:SS.',
            'end_time.regex' => 'Format waktu harus HH:MM:SS atau MM:SS.',
        ]);

        // Validate that end_time > start_time
        $startSeconds = $this->timeToSeconds($validated['start_time']);
        $endSeconds = $this->timeToSeconds($validated['end_time']);

        if ($endSeconds <= $startSeconds) {
            return back()->withErrors(['end_time' => 'Waktu akhir harus lebih besar dari waktu mulai.'])->withInput();
        }

        if (($endSeconds - $startSeconds) > 1800) {
            return back()->withErrors(['end_time' => 'Durasi clip maksimal 30 menit.'])->withInput();
        }

        $clipJob = ClipJob::create([
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
        return response()->json([
            'id' => $clipJob->id,
            'status' => $clipJob->status,
            'output_path' => $clipJob->output_path,
            'error_message' => $clipJob->error_message,
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
