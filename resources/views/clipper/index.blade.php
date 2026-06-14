@extends('layouts.app')

@section('content')
<div class="space-y-8">

    {{-- Hero Section --}}
    <div class="text-center space-y-3">
        <h1 class="text-3xl font-bold text-white">Potong Video YouTube</h1>
        <p class="text-gray-400 max-w-lg mx-auto">Masukkan tautan video, tentukan waktu mulai dan selesai, lalu biarkan sistem memproses klip Anda di latar belakang.</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="bg-lime-green/10 border border-lime-green/30 rounded-lg px-4 py-3 flex items-center gap-3">
        <i data-lucide="check-circle" class="w-5 h-5 text-lime-green flex-shrink-0"></i>
        <span class="text-lime-green text-sm">{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-500/10 border border-red-500/30 rounded-lg px-4 py-3 space-y-1">
        @foreach($errors->all() as $error)
        <p class="text-red-400 text-sm flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
            {{ $error }}
        </p>
        @endforeach
    </div>
    @endif

    {{-- Input Form --}}
    <form action="{{ route('clipper.store') }}" method="POST" class="bg-charcoal-light rounded-xl border border-charcoal-lighter p-6 space-y-5">
        @csrf

        {{-- YouTube URL Input --}}
        <div class="space-y-2">
            <label for="youtube_url" class="text-sm font-medium text-gray-300 flex items-center gap-2">
                <i data-lucide="youtube" class="w-4 h-4 text-neon-purple"></i>
                Tautan Video YouTube
            </label>
            <div class="relative">
                <i data-lucide="link" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                <input
                    type="url"
                    id="youtube_url"
                    name="youtube_url"
                    value="{{ old('youtube_url') }}"
                    placeholder="https://www.youtube.com/watch?v=... atau https://youtu.be/..."
                    class="w-full bg-charcoal border-2 border-charcoal-lighter focus:border-neon-purple rounded-lg pl-11 pr-4 py-3 text-white placeholder-gray-500 outline-none transition-colors"
                    required
                >
            </div>
        </div>

        {{-- Time Inputs --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- Start Time --}}
            <div class="space-y-2">
                <label for="start_time" class="text-sm font-medium text-gray-300 flex items-center gap-2">
                    <i data-lucide="clock" class="w-4 h-4 text-neon-purple"></i>
                    Waktu Mulai
                </label>
                <input
                    type="text"
                    id="start_time"
                    name="start_time"
                    value="{{ old('start_time') }}"
                    placeholder="00:00:00"
                    class="w-full bg-charcoal border-2 border-charcoal-lighter focus:border-neon-purple rounded-lg px-4 py-3 text-white placeholder-gray-500 outline-none transition-colors"
                    required
                >
            </div>

            {{-- End Time --}}
            <div class="space-y-2">
                <label for="end_time" class="text-sm font-medium text-gray-300 flex items-center gap-2">
                    <i data-lucide="scissors" class="w-4 h-4 text-neon-purple"></i>
                    Waktu Selesai
                </label>
                <input
                    type="text"
                    id="end_time"
                    name="end_time"
                    value="{{ old('end_time') }}"
                    placeholder="00:01:30"
                    class="w-full bg-charcoal border-2 border-charcoal-lighter focus:border-neon-purple rounded-lg px-4 py-3 text-white placeholder-gray-500 outline-none transition-colors"
                    required
                >
            </div>
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="w-full bg-neon-purple hover:bg-neon-purple-hover text-white font-semibold rounded-lg px-6 py-3.5 flex items-center justify-center gap-2 transition-all hover:shadow-lg hover:shadow-neon-purple/25">
            <i data-lucide="cpu" class="w-5 h-5"></i>
            Proses Pemotongan
        </button>
    </form>

    {{-- Clips History --}}
    @if($clips->count() > 0)
    <div class="space-y-4">
        <h2 class="text-lg font-semibold text-white flex items-center gap-2">
            <i data-lucide="history" class="w-5 h-5 text-neon-purple"></i>
            Riwayat Pemotongan
        </h2>

        <div class="space-y-3">
            @foreach($clips as $clip)
            <div class="bg-charcoal-light rounded-lg border border-charcoal-lighter p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 clip-item" data-clip-id="{{ $clip->id }}" data-status="{{ $clip->status }}">
                <div class="flex-1 min-w-0 space-y-1">
                    <p class="text-sm text-gray-300 truncate font-medium">
                        {{ $clip->youtube_url }}
                    </p>
                    <div class="flex items-center gap-3 text-xs text-gray-500">
                        <span class="flex items-center gap-1">
                            <i data-lucide="clock" class="w-3 h-3"></i>
                            {{ $clip->start_time }} &rarr; {{ $clip->end_time }}
                        </span>
                        <span>{{ $clip->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    {{-- Status Badge --}}
                    @switch($clip->status)
                        @case('pending')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                                <i data-lucide="clock" class="w-3 h-3"></i>
                                Menunggu
                            </span>
                            @break
                        @case('processing')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-neon-purple/10 text-neon-purple border border-neon-purple/20 pulse-glow">
                                <i data-lucide="refresh-cw" class="w-3 h-3 animate-spin-slow"></i>
                                Memproses
                            </span>
                            @break
                        @case('completed')
                            <a href="{{ route('clipper.download', $clip) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-lime-green/10 text-lime-green border border-lime-green/20 hover:bg-lime-green/20 transition-colors">
                                <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                Unduh Berkas
                            </a>
                            @break
                        @case('failed')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20" title="{{ $clip->error_message }}">
                                <i data-lucide="x-circle" class="w-3 h-3"></i>
                                Gagal
                            </span>
                            @break
                    @endswitch
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Polling for status updates on pending/processing clips
    function pollClipStatus() {
        const pendingClips = document.querySelectorAll('.clip-item[data-status="pending"], .clip-item[data-status="processing"]');

        if (pendingClips.length === 0) return;

        pendingClips.forEach(async (clipEl) => {
            const clipId = clipEl.dataset.clipId;
            try {
                const response = await fetch(`/clip/${clipId}/status`);
                const data = await response.json();

                if (data.status !== clipEl.dataset.status) {
                    // Status changed, reload page to show updated UI
                    window.location.reload();
                }
            } catch (e) {
                console.error('Polling error:', e);
            }
        });
    }

    // Poll every 5 seconds
    setInterval(pollClipStatus, 5000);
</script>
@endpush
