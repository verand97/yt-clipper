@extends('layouts.app')

@section('content')

{{-- ======================== HERO SECTION ======================== --}}
<section class="relative overflow-hidden">
    {{-- Background effects --}}
    <div class="absolute inset-0 grid-pattern"></div>
    <div class="hero-orb w-[500px] h-[500px] bg-accent top-[-100px] left-[-100px]"></div>
    <div class="hero-orb w-[400px] h-[400px] bg-pop top-[100px] right-[-150px]"></div>

    {{-- Floating decorative elements --}}
    <div class="absolute top-20 left-[10%] animate-float hidden lg:block">
        <div class="w-10 h-10 rounded-xl bg-accent/10 border border-accent/20 flex items-center justify-center backdrop-blur-sm">
            <i data-lucide="film" class="w-5 h-5 text-accent/60"></i>
        </div>
    </div>
    <div class="absolute top-40 right-[12%] animate-float-reverse hidden lg:block">
        <div class="w-9 h-9 rounded-lg bg-pop/10 border border-pop/20 flex items-center justify-center backdrop-blur-sm">
            <i data-lucide="download" class="w-4 h-4 text-pop/60"></i>
        </div>
    </div>
    <div class="absolute bottom-20 left-[15%] animate-float-reverse hidden lg:block">
        <div class="w-8 h-8 rounded-lg bg-surface-600 border border-surface-500 flex items-center justify-center">
            <i data-lucide="clock" class="w-4 h-4 text-gray-500"></i>
        </div>
    </div>

    {{-- Hero content --}}
    <div class="relative max-w-5xl mx-auto px-5 pt-20 pb-16">
        <div class="max-w-2xl mx-auto text-center space-y-6">
            {{-- Badge --}}
            <div class="animate-fade-up inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-surface-700 border border-surface-500 text-xs text-gray-400">
                <span class="w-1.5 h-1.5 rounded-full bg-pop animate-pulse"></span>
                Gratis & tanpa batas
            </div>

            {{-- Title --}}
            <h1 class="animate-fade-up-delay-1 text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-[1.1] tracking-tight">
                Potong video YouTube<br>
                <span class="bg-gradient-to-r from-accent via-accent-glow to-pop bg-clip-text text-transparent">dalam sekejap</span>
            </h1>

            {{-- Subtitle --}}
            <p class="animate-fade-up-delay-2 text-base sm:text-lg text-gray-400 max-w-lg mx-auto leading-relaxed">
                Tempel link, pilih durasi, dan dapatkan clip-nya. Tidak perlu download seluruh video.
            </p>

            {{-- CTA --}}
            <div class="animate-fade-up-delay-3 flex items-center justify-center gap-3">
                <a href="#clipper-form" class="btn-primary text-white font-semibold rounded-xl px-7 py-3.5 flex items-center gap-2 text-sm">
                    <i data-lucide="scissors" class="w-4 h-4"></i>
                    Mulai Potong
                </a>
                <a href="#how-it-works" class="px-5 py-3.5 rounded-xl border border-surface-500 text-gray-400 text-sm font-medium hover:border-surface-400 hover:text-gray-300 transition-colors">
                    Cara Kerja
                </a>
            </div>
        </div>

        {{-- Stats row --}}
        <div class="animate-fade-up-delay-4 mt-16 grid grid-cols-3 max-w-md mx-auto">
            <div class="text-center px-4 border-r border-surface-600">
                <p class="text-2xl font-bold text-white">{{ \App\Models\ClipJob::where('status', 'completed')->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Clip Selesai</p>
            </div>
            <div class="text-center px-4 border-r border-surface-600">
                <p class="text-2xl font-bold text-white">30<span class="text-sm text-gray-500">min</span></p>
                <p class="text-xs text-gray-500 mt-1">Maks Durasi</p>
            </div>
            <div class="text-center px-4">
                <p class="text-2xl font-bold text-pop">MP4</p>
                <p class="text-xs text-gray-500 mt-1">Format Output</p>
            </div>
        </div>
    </div>
</section>

{{-- ======================== HOW IT WORKS ======================== --}}
<section id="how-it-works" class="relative max-w-5xl mx-auto px-5 py-16">
    <div class="text-center mb-10">
        <h2 class="text-sm font-semibold text-accent uppercase tracking-wider">Cara Kerja</h2>
        <p class="text-2xl font-bold text-white mt-2">Tiga langkah sederhana</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        {{-- Step 1 --}}
        <div class="card-hover bg-surface-800 rounded-2xl border border-surface-600 p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-20 h-20 bg-accent/5 rounded-bl-[60px] group-hover:bg-accent/10 transition-colors"></div>
            <div class="w-10 h-10 rounded-xl bg-accent/10 border border-accent/20 flex items-center justify-center mb-4">
                <span class="text-accent font-bold text-sm">01</span>
            </div>
            <h3 class="text-white font-semibold mb-2">Tempel Link</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Salin URL video YouTube yang ingin dipotong, lalu tempel di form.</p>
        </div>

        {{-- Step 2 --}}
        <div class="card-hover bg-surface-800 rounded-2xl border border-surface-600 p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-20 h-20 bg-pop/5 rounded-bl-[60px] group-hover:bg-pop/10 transition-colors"></div>
            <div class="w-10 h-10 rounded-xl bg-pop/10 border border-pop/20 flex items-center justify-center mb-4">
                <span class="text-pop font-bold text-sm">02</span>
            </div>
            <h3 class="text-white font-semibold mb-2">Atur Waktu</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Tentukan titik mulai dan selesai. Bisa dalam format MM:SS atau HH:MM:SS.</p>
        </div>

        {{-- Step 3 --}}
        <div class="card-hover bg-surface-800 rounded-2xl border border-surface-600 p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-20 h-20 bg-accent/5 rounded-bl-[60px] group-hover:bg-accent/10 transition-colors"></div>
            <div class="w-10 h-10 rounded-xl bg-accent/10 border border-accent/20 flex items-center justify-center mb-4">
                <span class="text-accent font-bold text-sm">03</span>
            </div>
            <h3 class="text-white font-semibold mb-2">Download</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Tunggu proses selesai, lalu unduh file MP4 langsung ke perangkat kamu.</p>
        </div>
    </div>
</section>

{{-- ======================== CLIP FORM ======================== --}}
<section id="clipper-form" class="relative max-w-5xl mx-auto px-5 py-12">
    <div class="max-w-2xl mx-auto">

        {{-- Section title --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 rounded-lg bg-accent/10 border border-accent/20 flex items-center justify-center">
                <i data-lucide="scissors" class="w-4 h-4 text-accent"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-white">Buat Clip Baru</h2>
                <p class="text-xs text-gray-500">Isi form di bawah untuk memulai</p>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="mb-5 flex items-start gap-3 px-4 py-3.5 rounded-xl bg-pop/[0.06] border border-pop/20 animate-scale-in">
            <div class="w-5 h-5 rounded-full bg-pop/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i data-lucide="check" class="w-3 h-3 text-pop"></i>
            </div>
            <p class="text-sm text-pop/90">{{ session('success') }}</p>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-5 space-y-2 px-4 py-3.5 rounded-xl bg-red-500/[0.06] border border-red-500/20 animate-scale-in">
            @foreach($errors->all() as $error)
            <p class="text-sm text-red-400 flex items-center gap-2">
                <i data-lucide="x-circle" class="w-3.5 h-3.5 flex-shrink-0"></i>
                {{ $error }}
            </p>
            @endforeach
        </div>
        @endif

        {{-- Form Card --}}
        <form action="{{ route('clipper.store') }}" method="POST" class="bg-surface-800 rounded-2xl border border-surface-600 p-6 sm:p-8 space-y-6 card-hover">
            @csrf

            {{-- URL --}}
            <div class="space-y-2">
                <label for="youtube_url" class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i data-lucide="link-2" class="w-3.5 h-3.5 text-accent"></i>
                    URL Video YouTube
                </label>
                <input
                    type="url"
                    id="youtube_url"
                    name="youtube_url"
                    value="{{ old('youtube_url') }}"
                    placeholder="https://youtube.com/watch?v=... atau https://youtu.be/..."
                    class="input-field w-full bg-surface-700 border border-surface-500 rounded-xl px-4 py-3.5 text-sm text-white placeholder-gray-600 outline-none"
                    required
                >
            </div>

            {{-- Method Toggle --}}
            <div class="space-y-2">
                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i data-lucide="settings-2" class="w-3.5 h-3.5 text-accent"></i>
                    Metode Pemotongan
                </label>
                <div class="grid grid-cols-2 gap-2 p-1.5 bg-surface-900 rounded-xl border border-surface-600">
                    <button type="button" id="tab-manual" class="py-2.5 text-xs font-semibold rounded-lg transition-all text-white bg-surface-700 shadow-sm flex items-center justify-center gap-2 cursor-pointer">
                        <i data-lucide="sliders" class="w-3.5 h-3.5"></i>
                        Potong Manual
                    </button>
                    <button type="button" id="tab-smart" class="py-2.5 text-xs font-semibold rounded-lg transition-all text-gray-400 hover:text-gray-200 flex items-center justify-center gap-2 cursor-pointer">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5 text-pop"></i>
                        Klip Pintar (AI)
                    </button>
                </div>
                <input type="hidden" name="is_smart" id="is_smart_input" value="0">
            </div>

            {{-- Manual Time Inputs --}}
            <div id="manual-fields" class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="start_time" class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                            <i data-lucide="play" class="w-3 h-3 text-pop"></i>
                            Waktu Mulai
                        </label>
                        <input
                            type="text"
                            id="start_time"
                            name="start_time"
                            value="{{ old('start_time') }}"
                            placeholder="00:30"
                            class="input-field w-full bg-surface-700 border border-surface-500 rounded-xl px-4 py-3.5 text-sm text-white placeholder-gray-600 outline-none font-mono text-center tracking-wider"
                            required
                        >
                    </div>
                    <div class="space-y-2">
                        <label for="end_time" class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                            <i data-lucide="square" class="w-3 h-3 text-red-400"></i>
                            Waktu Selesai
                        </label>
                        <input
                            type="text"
                            id="end_time"
                            name="end_time"
                            value="{{ old('end_time') }}"
                            placeholder="02:15"
                            class="input-field w-full bg-surface-700 border border-surface-500 rounded-xl px-4 py-3.5 text-sm text-white placeholder-gray-600 outline-none font-mono text-center tracking-wider"
                            required
                        >
                    </div>
                </div>
                <p class="text-[11px] text-gray-600 flex items-center gap-1.5">
                    <i data-lucide="info" class="w-3 h-3"></i>
                    Format: MM:SS atau HH:MM:SS — Durasi maksimal 30 menit per clip
                </p>
            </div>

            {{-- Smart Clip Options --}}
            <div id="smart-fields" class="space-y-4 hidden">
                <div class="space-y-2">
                    <label for="smart_prompt" class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="target" class="w-3.5 h-3.5 text-pop"></i>
                        Fokus / Topik Klip (Opsional)
                    </label>
                    <input
                        type="text"
                        id="smart_prompt"
                        name="smart_prompt"
                        value="{{ old('smart_prompt') }}"
                        placeholder="Contoh: bagian lucu, ringkasan materi, momen penting..."
                        class="input-field w-full bg-surface-700 border border-surface-500 rounded-xl px-4 py-3.5 text-sm text-white placeholder-gray-600 outline-none"
                    >
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="min_duration" class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                            <i data-lucide="hourglass" class="w-3.5 h-3.5 text-accent"></i>
                            Durasi Minimal (Detik)
                        </label>
                        <input
                            type="number"
                            id="min_duration"
                            name="min_duration"
                            min="15"
                            max="300"
                            value="{{ old('min_duration', 15) }}"
                            class="input-field w-full bg-surface-700 border border-surface-500 rounded-xl px-4 py-3.5 text-sm text-white outline-none font-mono text-center"
                        >
                    </div>
                    <div class="space-y-2">
                        <label for="max_clips" class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                            <i data-lucide="layers" class="w-3.5 h-3.5 text-accent"></i>
                            Klip Maksimal
                        </label>
                        <input
                            type="number"
                            id="max_clips"
                            name="max_clips"
                            min="1"
                            max="10"
                            value="{{ old('max_clips', 5) }}"
                            class="input-field w-full bg-surface-700 border border-surface-500 rounded-xl px-4 py-3.5 text-sm text-white outline-none font-mono text-center"
                        >
                    </div>
                </div>

                <div class="space-y-2 pt-2">
                    <label for="gemini_api_key" class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center justify-between">
                        <span class="flex items-center gap-2">
                            <i data-lucide="key-round" class="w-3.5 h-3.5 text-pop"></i>
                            Gemini API Key (Opsional)
                        </span>
                        @if(session('gemini_api_key'))
                            <span class="text-[10px] text-pop font-normal lowercase">Tersimpan di sesi</span>
                        @endif
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="gemini_api_key"
                            name="gemini_api_key"
                            value="{{ session('gemini_api_key') ? '••••••••••••••••••••••••' : '' }}"
                            placeholder="Masukkan API Key Anda..."
                            class="input-field w-full bg-surface-700 border border-surface-500 rounded-xl pl-4 pr-10 py-3.5 text-sm text-white placeholder-gray-600 outline-none font-mono"
                        >
                        <button type="button" id="toggle-key-visibility" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <p class="text-[10px] text-gray-600 leading-normal mt-1">
                        Kosongkan jika server telah mengonfigurasi `GEMINI_API_KEY` di file `.env`. API Key Anda aman dan hanya disimpan sementara dalam sesi PHP Anda.
                    </p>
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-primary w-full text-white font-semibold rounded-xl px-6 py-4 flex items-center justify-center gap-2.5 text-sm">
                <i data-lucide="zap" class="w-4 h-4"></i>
                Proses Sekarang
            </button>
        </form>
    </div>
</section>

{{-- ======================== CLIP HISTORY ======================== --}}
@if($clips->count() > 0)
<section class="relative max-w-5xl mx-auto px-5 py-12">
    <div class="max-w-2xl mx-auto">

        {{-- Section header --}}
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-surface-600 border border-surface-500 flex items-center justify-center">
                    <i data-lucide="list" class="w-4 h-4 text-gray-400"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-white">Riwayat Clip</h2>
                    <p class="text-xs text-gray-600">{{ $clips->count() }} item terakhir</p>
                </div>
            </div>
        </div>

        {{-- Clip list --}}
        <div class="space-y-4 stagger-children">
            @foreach($clips as $clip)
                @if($clip->is_smart)
                    {{-- SMART CLIP COLLECTION CARD --}}
                    <div class="bg-surface-800 rounded-xl border border-surface-600 overflow-hidden clip-item shadow-xl transition-all duration-300 hover:border-surface-500/80" data-clip-id="{{ $clip->id }}" data-status="{{ $clip->status }}">
                        
                        {{-- Parent Header --}}
                        <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-surface-800/90 border-b border-surface-600/30">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-pop bg-pop/10 border border-pop/20 px-2 py-0.5 rounded uppercase tracking-wider">
                                        <i data-lucide="sparkles" class="w-3 h-3"></i>
                                        Klip Pintar
                                    </span>
                                    @if($clip->smart_prompt)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-accent bg-accent/10 border border-accent/20 px-2 py-0.5 rounded">
                                            Topik: {{ $clip->smart_prompt }}
                                        </span>
                                    @endif
                                </div>
                                <h3 class="text-sm font-bold text-white truncate mt-2">{{ $clip->video_title ?? 'Menganalisis metadata video...' }}</h3>
                                <div class="flex items-center gap-3 mt-1.5 flex-wrap">
                                    <span class="text-[11px] text-gray-500 truncate max-w-[200px] sm:max-w-xs" title="{{ $clip->youtube_url }}">{{ $clip->youtube_url }}</span>
                                    @if($clip->original_duration)
                                        <span class="text-[11px] text-gray-600 font-mono">Durasi: {{ $clip->original_duration }}</span>
                                    @endif
                                    <span class="text-[11px] text-gray-600">{{ $clip->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            {{-- Parent Status Badge --}}
                            <div class="flex-shrink-0 self-start sm:self-center">
                                @switch($clip->status)
                                    @case('pending')
                                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-yellow-500/[0.06] border border-yellow-500/15">
                                            <span class="w-2 h-2 rounded-full bg-yellow-500/60 animate-pulse"></span>
                                            <span class="text-xs text-yellow-400 font-medium">Antri Analisis</span>
                                        </div>
                                        @break
                                    @case('processing')
                                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-accent/[0.06] border border-accent/15 shimmer-bg">
                                            <span class="w-2 h-2 rounded-full bg-accent animate-pulse-soft"></span>
                                            <span class="text-xs text-accent font-medium">Menganalisis...</span>
                                        </div>
                                        @break
                                    @case('completed')
                                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-pop/[0.06] border border-pop/15">
                                            <span class="w-2 h-2 rounded-full bg-pop"></span>
                                            <span class="text-xs text-pop font-medium" title="{{ $clip->error_message ?? '' }}">Siap</span>
                                        </div>
                                        @break
                                    @case('failed')
                                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-red-500/[0.06] border border-red-500/15 cursor-help" title="{{ $clip->error_message }}">
                                            <span class="w-2 h-2 rounded-full bg-red-400/60"></span>
                                            <span class="text-xs text-red-400 font-medium">Gagal</span>
                                        </div>
                                        @break
                                @endswitch
                            </div>
                        </div>

                        {{-- Nested Child Clips --}}
                        @if($clip->children->count() > 0)
                            <div class="px-5 py-3.5 bg-surface-900/40 space-y-2.5">
                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                    <i data-lucide="layers" class="w-3.5 h-3.5 text-accent"></i>
                                    Klip yang Dihasilkan (Min 15d)
                                </p>
                                <div class="space-y-2">
                                    @foreach($clip->children as $child)
                                        <div class="child-clip-item flex items-center justify-between gap-4 px-4 py-3 rounded-xl bg-surface-800/60 border border-surface-600/30 hover:border-surface-500/50 hover:bg-surface-800 transition-all duration-200" data-child-id="{{ $child->id }}" data-status="{{ $child->status }}">
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-start gap-2">
                                                    <i data-lucide="video" class="w-3.5 h-3.5 text-pop flex-shrink-0 mt-0.5"></i>
                                                    <div>
                                                        <h4 class="text-xs font-semibold text-gray-200 leading-normal">{{ str_replace($clip->video_title . ' - ', '', $child->video_title) }}</h4>
                                                        <span class="inline-flex items-center gap-1 text-[9px] text-gray-500 font-mono bg-surface-700/80 px-1.5 py-0.5 rounded mt-1">
                                                            <i data-lucide="timer" class="w-2.5 h-2.5"></i>
                                                            {{ $child->start_time }} → {{ $child->end_time }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex-shrink-0">
                                                @switch($child->status)
                                                    @case('pending')
                                                        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-yellow-500/[0.04] border border-yellow-500/10">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500/60 animate-pulse"></span>
                                                            <span class="text-[10px] text-yellow-400 font-medium">Antri</span>
                                                        </div>
                                                        @break
                                                    @case('processing')
                                                        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-accent/[0.04] border border-accent/10 shimmer-bg">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-accent animate-pulse-soft"></span>
                                                            <span class="text-[10px] text-accent font-medium">Memotong</span>
                                                        </div>
                                                        @break
                                                    @case('completed')
                                                        <a href="{{ route('clipper.download', $child) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-bold text-surface-900 bg-pop hover:bg-pop-dim transition-all shadow-md shadow-pop/10 hover:shadow-pop/20 hover:-translate-y-0.5">
                                                            <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                                            Unduh
                                                        </a>
                                                        @break
                                                    @case('failed')
                                                        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-red-500/[0.04] border border-red-500/10 cursor-help" title="{{ $child->error_message }}">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-red-400/60"></span>
                                                            <span class="text-[10px] text-red-400 font-medium">Gagal</span>
                                                        </div>
                                                        @break
                                                @endswitch
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            {{-- Waiting for analysis completion to generate child clips --}}
                            @if($clip->status === 'completed')
                                <div class="px-5 py-4 bg-surface-900/40 text-center border-t border-surface-600/20">
                                    <p class="text-xs text-gray-500">Tidak ada klip penting dengan durasi minimal 15 detik yang ditemukan.</p>
                                </div>
                            @elseif($clip->status === 'pending' || $clip->status === 'processing')
                                <div class="px-5 py-5 bg-surface-900/40 text-center border-t border-surface-600/20 flex flex-col items-center justify-center gap-2">
                                    <div class="w-5 h-5 rounded-full border-2 border-accent border-t-transparent animate-spin"></div>
                                    <p class="text-xs text-gray-500 font-medium animate-pulse-soft">Sedang menganalisis konten video untuk mendeteksi bagian penting...</p>
                                </div>
                            @endif
                        @endif
                    </div>
                @else
                    {{-- SINGLE STANDALONE MANUAL CLIP CARD --}}
                    <div class="card-hover bg-surface-800 rounded-xl border border-surface-600 px-5 py-4 flex items-center justify-between gap-4 clip-item" data-clip-id="{{ $clip->id }}" data-status="{{ $clip->status }}">
                        
                        {{-- Left: info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-gray-400 bg-surface-700/80 px-2 py-0.5 rounded uppercase">
                                    Manual
                                </span>
                                <p class="text-sm font-bold text-white truncate">{{ $clip->video_title ?? $clip->youtube_url }}</p>
                            </div>
                            <div class="flex items-center gap-3 mt-1.5 flex-wrap">
                                <span class="text-[11px] text-gray-500 truncate max-w-[200px] sm:max-w-xs" title="{{ $clip->youtube_url }}">{{ $clip->youtube_url }}</span>
                                <span class="inline-flex items-center gap-1 text-[11px] text-gray-500 font-mono bg-surface-700 px-2 py-0.5 rounded">
                                    <i data-lucide="timer" class="w-3 h-3"></i>
                                    {{ $clip->start_time }} → {{ $clip->end_time }}
                                </span>
                                <span class="text-[11px] text-gray-600">{{ $clip->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        {{-- Right: status/action --}}
                        <div class="flex-shrink-0">
                            @switch($clip->status)
                                @case('pending')
                                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-yellow-500/[0.06] border border-yellow-500/15">
                                        <span class="w-2 h-2 rounded-full bg-yellow-500/60 animate-pulse"></span>
                                        <span class="text-xs text-yellow-400 font-medium">Antri</span>
                                    </div>
                                    @break
                                @case('processing')
                                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-accent/[0.06] border border-accent/15 shimmer-bg">
                                        <span class="w-2 h-2 rounded-full bg-accent animate-pulse-soft"></span>
                                        <span class="text-xs text-accent font-medium">Memproses</span>
                                    </div>
                                    @break
                                @case('completed')
                                    <a href="{{ route('clipper.download', $clip) }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold text-surface-900 bg-pop hover:bg-pop-dim transition-all hover:shadow-lg hover:shadow-pop/20 hover:-translate-y-0.5 shadow-md shadow-pop/10">
                                        <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                        Unduh
                                    </a>
                                    @break
                                @case('failed')
                                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-red-500/[0.06] border border-red-500/15 cursor-help" title="{{ $clip->error_message }}">
                                        <span class="w-2 h-2 rounded-full bg-red-400/60"></span>
                                        <span class="text-xs text-red-400 font-medium">Gagal</span>
                                    </div>
                                    @break
                            @endswitch
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ======================== FEATURES SECTION ======================== --}}
<section class="relative max-w-5xl mx-auto px-5 py-16">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-surface-800/50 rounded-xl border border-surface-600 p-5 space-y-3">
            <div class="w-9 h-9 rounded-lg bg-accent/10 flex items-center justify-center">
                <i data-lucide="zap" class="w-4 h-4 text-accent"></i>
            </div>
            <h4 class="text-sm font-semibold text-white">Proses Cepat</h4>
            <p class="text-xs text-gray-500 leading-relaxed">Hanya download bagian yang dibutuhkan, bukan seluruh video.</p>
        </div>
        <div class="bg-surface-800/50 rounded-xl border border-surface-600 p-5 space-y-3">
            <div class="w-9 h-9 rounded-lg bg-pop/10 flex items-center justify-center">
                <i data-lucide="hard-drive" class="w-4 h-4 text-pop"></i>
            </div>
            <h4 class="text-sm font-semibold text-white">Hemat Storage</h4>
            <p class="text-xs text-gray-500 leading-relaxed">File clip berukuran kecil karena hanya berisi segmen yang dipilih.</p>
        </div>
        <div class="bg-surface-800/50 rounded-xl border border-surface-600 p-5 space-y-3">
            <div class="w-9 h-9 rounded-lg bg-accent/10 flex items-center justify-center">
                <i data-lucide="shield-check" class="w-4 h-4 text-accent"></i>
            </div>
            <h4 class="text-sm font-semibold text-white">Kualitas Terjaga</h4>
            <p class="text-xs text-gray-500 leading-relaxed">Encoding H.264 + AAC memastikan output berkualitas tinggi.</p>
        </div>
        <div class="bg-surface-800/50 rounded-xl border border-surface-600 p-5 space-y-3">
            <div class="w-9 h-9 rounded-lg bg-pop/10 flex items-center justify-center">
                <i data-lucide="sparkles" class="w-4 h-4 text-pop"></i>
            </div>
            <h4 class="text-sm font-semibold text-white">Deteksi Pintar</h4>
            <p class="text-xs text-gray-500 leading-relaxed">Menggunakan Gemini AI untuk mendeteksi key moments di dalam video secara otomatis.</p>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Tab switching logic
    const tabManual = document.getElementById('tab-manual');
    const tabSmart = document.getElementById('tab-smart');
    const manualFields = document.getElementById('manual-fields');
    const smartFields = document.getElementById('smart-fields');
    const isSmartInput = document.getElementById('is_smart_input');
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');

    if (tabManual && tabSmart) {
        tabManual.addEventListener('click', () => {
            // Styling updates
            tabManual.classList.add('bg-surface-700', 'text-white', 'shadow-sm');
            tabManual.classList.remove('text-gray-400', 'hover:text-gray-200');
            tabSmart.classList.remove('bg-surface-700', 'text-white', 'shadow-sm');
            tabSmart.classList.add('text-gray-400', 'hover:text-gray-200');
            
            // Field display
            manualFields.classList.remove('hidden');
            smartFields.classList.add('hidden');
            
            // Set hidden field value
            isSmartInput.value = '0';
            
            // Adjust validation requirements
            startTimeInput.setAttribute('required', 'required');
            endTimeInput.setAttribute('required', 'required');
        });

        tabSmart.addEventListener('click', () => {
            // Styling updates
            tabSmart.classList.add('bg-surface-700', 'text-white', 'shadow-sm');
            tabSmart.classList.remove('text-gray-400', 'hover:text-gray-200');
            tabManual.classList.remove('bg-surface-700', 'text-white', 'shadow-sm');
            tabManual.classList.add('text-gray-400', 'hover:text-gray-200');
            
            // Field display
            smartFields.classList.remove('hidden');
            manualFields.classList.add('hidden');
            
            // Set hidden field value
            isSmartInput.value = '1';
            
            // Adjust validation requirements
            startTimeInput.removeAttribute('required');
            endTimeInput.removeAttribute('required');
        });
    }

    // Toggle API key visibility
    const toggleKeyBtn = document.getElementById('toggle-key-visibility');
    const keyInput = document.getElementById('gemini_api_key');
    if (toggleKeyBtn && keyInput) {
        toggleKeyBtn.addEventListener('click', () => {
            const isPassword = keyInput.type === 'password';
            keyInput.type = isPassword ? 'text' : 'password';
            toggleKeyBtn.innerHTML = isPassword 
                ? '<i data-lucide="eye-off" class="w-4 h-4"></i>' 
                : '<i data-lucide="eye" class="w-4 h-4"></i>';
            lucide.createIcons();
        });
    }

    // Poll status for active clips
    function pollStatus() {
        const items = document.querySelectorAll('.clip-item[data-status="pending"], .clip-item[data-status="processing"], .child-clip-item[data-status="pending"], .child-clip-item[data-status="processing"]');
        if (!items.length) return;

        // Group parents to avoid redundant api queries
        const parentIds = new Set();
        items.forEach(el => {
            if (el.classList.contains('child-clip-item')) {
                const parent = el.closest('.clip-item');
                if (parent) parentIds.add(parent.dataset.clipId);
            } else {
                parentIds.add(el.dataset.clipId);
            }
        });

        parentIds.forEach(async (id) => {
            try {
                const res = await fetch(`/clip/${id}/status`);
                const data = await res.json();
                
                const parentEl = document.querySelector(`.clip-item[data-clip-id="${id}"]`);
                if (!parentEl) return;

                // Check parent status change
                if (data.status !== parentEl.dataset.status) {
                    window.location.reload();
                    return;
                }

                // Check child status/count changes
                if (data.is_smart && data.children) {
                    const domChildren = parentEl.querySelectorAll('.child-clip-item');
                    if (data.children.length !== domChildren.length) {
                        window.location.reload();
                        return;
                    }

                    for (let child of data.children) {
                        const childDom = parentEl.querySelector(`.child-clip-item[data-child-id="${child.id}"]`);
                        if (!childDom || child.status !== childDom.dataset.status) {
                            window.location.reload();
                            return;
                        }
                    }
                }
            } catch (e) {}
        });
    }
    setInterval(pollStatus, 4000);

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', (e) => {
            e.preventDefault();
            const target = document.querySelector(a.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Intersection Observer for scroll animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-up');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('#how-it-works .card-hover, section:last-of-type .bg-surface-800\\/50').forEach(el => {
        el.style.opacity = '0';
        observer.observe(el);
    });
</script>
@endpush

