@extends('layouts.app')

@section('content')
<section class="relative min-h-[calc(100vh-4rem)] py-8 overflow-hidden">
    {{-- Background effects --}}
    <div class="absolute inset-0 grid-pattern pointer-events-none"></div>
    <div class="hero-orb w-[400px] h-[400px] bg-accent/10 top-[-50px] left-[-50px] pointer-events-none"></div>
    <div class="hero-orb w-[350px] h-[350px] bg-pop/10 bottom-[50px] right-[-50px] pointer-events-none"></div>

    <div class="relative max-w-6xl mx-auto px-5">
        {{-- Page Header --}}
        <div class="animate-fade-up mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-white leading-none tracking-tight flex items-center gap-3">
                    <span class="bg-gradient-to-r from-accent via-accent-glow to-pop bg-clip-text text-transparent">Clipper Workspace</span>
                </h1>
                <p class="text-sm text-gray-400 mt-2">Mulai potong video YouTube secara manual atau otomatis dengan kecerdasan buatan.</p>
            </div>
            <div class="flex items-center gap-2 text-xs bg-surface-800 border border-surface-600/50 rounded-xl px-4 py-2 text-gray-400">
                <span class="w-2 h-2 rounded-full bg-pop animate-pulse"></span>
                Sistem Siap
            </div>
        </div>

        {{-- Main Dashboard Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- COLUMN 1: CLIPPER FORM --}}
            <div class="lg:col-span-5 space-y-6 animate-fade-up-delay-1">
                <div class="bg-surface-800 rounded-2xl border border-surface-600 p-6 sm:p-7 shadow-2xl relative overflow-hidden card-hover">
                    <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-accent to-pop"></div>
                    
                    {{-- Header Form --}}
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-9 h-9 rounded-xl bg-accent/10 border border-accent/20 flex items-center justify-center shrink-0">
                            <i data-lucide="scissors" class="w-4 h-4 text-accent"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-white">Buat Klip Baru</h2>
                            <p class="text-xs text-gray-500">Konfigurasi detail klip video Anda</p>
                        </div>
                    </div>

                    {{-- Flash Messages --}}
                    @if(session('success'))
                    <div class="mb-5 flex items-start gap-3 px-5 py-4 rounded-xl bg-pop/10 border border-pop/20 animate-scale-in">
                        <div class="w-5 h-5 rounded-full bg-pop/20 flex items-center justify-center shrink-0 mt-0.5">
                            <i data-lucide="check" class="w-3 h-3 text-pop"></i>
                        </div>
                        <p class="text-xs text-pop/90 leading-snug">{{ session('success') }}</p>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="mb-5 space-y-2 px-5 py-4 rounded-xl bg-red-500/10 border border-red-500/20 animate-scale-in">
                        @foreach($errors->all() as $error)
                        <p class="text-xs text-red-400 flex items-center gap-2">
                            <i data-lucide="x-circle" class="w-3.5 h-3.5 shrink-0"></i>
                            {{ $error }}
                        </p>
                        @endforeach
                    </div>
                    @endif

                    {{-- Form --}}
                    <form action="{{ route('clipper.store') }}" method="POST" class="space-y-5">
                        @csrf

                        {{-- URL Input --}}
                        <div class="space-y-1.5">
                            <label for="youtube_url" class="text-[11px] font-bold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                                <i data-lucide="link-2" class="w-3.5 h-3.5 text-accent"></i>
                                URL Video YouTube
                            </label>
                            <div class="relative">
                                <input
                                    type="url"
                                    id="youtube_url"
                                    name="youtube_url"
                                    value="{{ old('youtube_url') }}"
                                    placeholder="Tempel link YouTube atau Shorts..."
                                    class="input-field w-full bg-surface-700 border border-surface-500 rounded-xl pl-4 pr-10 py-3 text-sm text-white placeholder-gray-600 outline-none"
                                    required
                                >
                                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">
                                    <i data-lucide="youtube" class="w-4 h-4"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Method Toggle tabs --}}
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                                <i data-lucide="settings-2" class="w-3.5 h-3.5 text-accent"></i>
                                Metode Pemotongan
                            </label>
                            <div class="grid grid-cols-2 gap-1 p-1 bg-surface-900 rounded-xl border border-surface-600/70">
                                <button type="button" id="tab-manual" class="py-2 text-xs font-semibold rounded-lg transition-all text-white bg-surface-700 shadow-sm flex items-center justify-center gap-2 cursor-pointer">
                                    <i data-lucide="sliders" class="w-3.5 h-3.5"></i>
                                    Potong Manual
                                </button>
                                <button type="button" id="tab-smart" class="py-2 text-xs font-semibold rounded-lg transition-all text-gray-400 hover:text-gray-200 flex items-center justify-center gap-2 cursor-pointer">
                                    <i data-lucide="sparkles" class="w-3.5 h-3.5 text-pop"></i>
                                    Klip Pintar (AI)
                                </button>
                            </div>
                            <input type="hidden" name="is_smart" id="is_smart_input" value="0">
                        </div>

                        {{-- Manual Fields --}}
                        <div id="manual-fields" class="space-y-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1.5">
                                    <label for="start_time" class="text-[11px] font-bold text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                                        <i data-lucide="play" class="w-3 h-3 text-pop"></i>
                                        Mulai
                                    </label>
                                    <input
                                        type="text"
                                        id="start_time"
                                        name="start_time"
                                        value="{{ old('start_time') }}"
                                        placeholder="MM:SS atau HH:MM:SS"
                                        class="input-field w-full bg-surface-700 border border-surface-500 rounded-xl px-3 py-2.5 text-sm text-center text-white placeholder-gray-600 outline-none font-mono tracking-wider"
                                        required
                                    >
                                </div>
                                <div class="space-y-1.5">
                                    <label for="end_time" class="text-[11px] font-bold text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                                        <i data-lucide="square" class="w-3 h-3 text-red-400"></i>
                                        Selesai
                                    </label>
                                    <input
                                        type="text"
                                        id="end_time"
                                        name="end_time"
                                        value="{{ old('end_time') }}"
                                        placeholder="MM:SS atau HH:MM:SS"
                                        class="input-field w-full bg-surface-700 border border-surface-500 rounded-xl px-3 py-2.5 text-sm text-center text-white placeholder-gray-600 outline-none font-mono tracking-wider"
                                        required
                                    >
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-500 flex items-start gap-1.5 leading-normal">
                                <i data-lucide="info" class="w-3.5 h-3.5 text-accent shrink-0 mt-0.5"></i>
                                <span>Gunakan format waktu <strong>MM:SS</strong> (contoh: 01:30) atau <strong>HH:MM:SS</strong> (contoh: 01:15:00). Durasi maksimal klip manual adalah 30 menit.</span>
                            </p>
                        </div>

                        {{-- Smart Fields --}}
                        <div id="smart-fields" class="space-y-4 hidden">
                            <div class="space-y-1.5">
                                <label for="smart_prompt" class="text-[11px] font-bold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                                    <i data-lucide="target" class="w-3.5 h-3.5 text-pop"></i>
                                    Topik / Fokus Klip (Opsional)
                                </label>
                                <input
                                    type="text"
                                    id="smart_prompt"
                                    name="smart_prompt"
                                    value="{{ old('smart_prompt') }}"
                                    placeholder="Contoh: momen lucu, highlight coding, bagian marketing..."
                                    class="input-field w-full bg-surface-700 border border-surface-500 rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-600 outline-none"
                                >
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1.5">
                                    <label for="min_duration" class="text-[11px] font-bold text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                                        <i data-lucide="hourglass" class="w-3.5 h-3.5 text-accent"></i>
                                        Min Durasi (Detik)
                                    </label>
                                    <input
                                        type="number"
                                        id="min_duration"
                                        name="min_duration"
                                        min="15"
                                        max="300"
                                        value="{{ old('min_duration', 15) }}"
                                        class="input-field w-full bg-surface-700 border border-surface-500 rounded-xl px-3 py-2 text-sm text-center text-white outline-none font-mono"
                                    >
                                </div>
                                <div class="space-y-1.5">
                                    <label for="max_clips" class="text-[11px] font-bold text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                                        <i data-lucide="layers" class="w-3.5 h-3.5 text-accent"></i>
                                        Maks Jumlah Klip
                                    </label>
                                    <input
                                        type="number"
                                        id="max_clips"
                                        name="max_clips"
                                        min="1"
                                        max="10"
                                        value="{{ old('max_clips', 5) }}"
                                        class="input-field w-full bg-surface-700 border border-surface-500 rounded-xl px-3 py-2 text-sm text-center text-white outline-none font-mono"
                                    >
                                </div>
                            </div>

                            <div class="space-y-1.5 pt-1">
                                <label for="gemini_api_key" class="text-[11px] font-bold text-gray-400 uppercase tracking-wider flex items-center justify-between">
                                    <span class="flex items-center gap-2">
                                        <i data-lucide="key-round" class="w-3.5 h-3.5 text-pop"></i>
                                        Gemini API Key (Opsional)
                                    </span>
                                    @if(session('gemini_api_key'))
                                        <span class="text-[9px] text-pop font-normal lowercase bg-pop/10 px-2 py-0.5 rounded border border-pop/20">Tersimpan</span>
                                    @endif
                                </label>
                                <div class="relative">
                                    <input
                                        type="password"
                                        id="gemini_api_key"
                                        name="gemini_api_key"
                                        value="{{ session('gemini_api_key') ? '••••••••••••••••••••••••' : '' }}"
                                        placeholder="Kosongkan jika menggunakan server API key..."
                                        class="input-field w-full bg-surface-700 border border-surface-500 rounded-xl pl-4 pr-10 py-2.5 text-sm text-white placeholder-gray-600 outline-none font-mono"
                                    >
                                    <button type="button" id="toggle-key-visibility" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" class="btn-primary w-full text-white font-bold rounded-xl px-5 py-4 flex items-center justify-center gap-2.5 text-sm cursor-pointer shadow-lg">
                            <i data-lucide="zap" class="w-4.5 h-4.5"></i>
                            Proses Klip Sekarang
                        </button>
                    </form>
                </div>
            </div>

                        {{-- COLUMN 2: CLIP HISTORY (SPLIT VIEW) --}}
            <div class="lg:col-span-7 space-y-6 animate-fade-up-delay-2">
                
                {{-- SECTION 1: ACTIVE QUEUE & PROCESSING --}}
                @if($activeClips->count() > 0)
                <div class="bg-surface-800 rounded-2xl border border-surface-600 shadow-2xl relative overflow-hidden flex flex-col">
                    <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-yellow-500 via-accent to-accent-dim"></div>
                    
                    {{-- Header Queue --}}
                    <div class="px-6 py-5 border-b border-surface-600/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-surface-700 border border-surface-500 flex items-center justify-center shrink-0">
                                <i data-lucide="loader-2" class="w-4.5 h-4.5 text-accent animate-spin"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-white">Antrean & Proses Aktif</h2>
                                <p class="text-xs text-gray-500">Klip yang sedang diproses atau mengantre</p>
                            </div>
                        </div>
                        <div class="text-[10px] font-bold text-accent bg-accent/10 border border-accent/20 px-2.5 py-1 rounded-full uppercase tracking-wider flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-accent animate-pulse"></span>
                            {{ $activeClips->count() }} proses
                        </div>
                    </div>

                    {{-- Active List --}}
                    <div class="p-6">
                        <div class="max-h-[350px] overflow-y-auto pr-1 space-y-4 stagger-children scrollbar-thin">
                            @foreach($activeClips as $clip)
                                @if($clip->is_smart)
                                    {{-- SMART CLIP COLLECTION CARD --}}
                                    <div class="bg-surface-900/60 rounded-xl border border-surface-600/80 overflow-hidden clip-item shadow-md hover:border-surface-500/80 transition-all duration-300" data-clip-id="{{ $clip->id }}" data-status="{{ $clip->status }}">
                                        
                                        {{-- Parent Header --}}
                                        <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-start justify-between gap-4 bg-surface-800/80 border-b border-surface-600/30">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <span class="inline-flex items-center gap-1 text-[9px] font-extrabold text-pop bg-pop/10 border border-pop/20 px-2 py-0.5 rounded uppercase tracking-wider">
                                                        <i data-lucide="sparkles" class="w-2.5 h-2.5"></i>
                                                        Klip Pintar
                                                    </span>
                                                    @if($clip->smart_prompt)
                                                        <span class="inline-flex items-center gap-1 text-[9px] font-semibold text-accent bg-accent/10 border border-accent/20 px-2 py-0.5 rounded">
                                                            Topik: {{ $clip->smart_prompt }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <h3 class="text-xs font-bold text-white truncate mt-1.5" title="{{ $clip->video_title ?? 'Menganalisis metadata video...' }}">{{ $clip->video_title ?? 'Menganalisis metadata video...' }}</h3>
                                                @if($clip->status === 'processing')
                                                    <div class="parent-step-container mt-1.5 flex flex-col gap-0.5" id="parent-step-{{ $clip->id }}" data-elapsed="{{ $clip->getElapsedSeconds() }}" data-estimated="{{ $clip->getEstimatedDuration() }}">
                                                        <span class="text-[10px] text-accent-glow font-medium animate-pulse flex items-center gap-1.5">
                                                            <i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i>
                                                            <span class="step-text">{{ $clip->current_step ?? 'Membaca metadata video...' }}</span>
                                                        </span>
                                                        <span class="eta-text text-[9px] text-gray-500 font-mono pl-5">Estimasi selesai: --</span>
                                                    </div>
                                                @endif
                                                <div class="flex items-center gap-2.5 mt-1 flex-wrap">
                                                    <span class="text-[10px] text-gray-500 truncate max-w-[150px] sm:max-w-xs" title="{{ $clip->youtube_url }}">{{ $clip->youtube_url }}</span>
                                                    @if($clip->original_duration)
                                                        <span class="text-[10px] text-gray-600 font-mono">Durasi: {{ $clip->original_duration }}</span>
                                                    @endif
                                                    <span class="text-[10px] text-gray-600">{{ $clip->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>

                                            {{-- Parent Status Badge / Actions --}}
                                            <div class="shrink-0 self-start flex items-center gap-2.5 mt-0.5">
                                                @switch($clip->status)
                                                    @case('pending')
                                                        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-yellow-500/10 border border-yellow-500/20">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500/60 animate-pulse"></span>
                                                            <span class="text-[10px] text-yellow-400 font-medium">Antri Analisis</span>
                                                        </div>
                                                        <form action="{{ route('clipper.cancel', $clip) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" title="Batalkan Antrean" class="w-7 h-7 rounded-lg bg-surface-700/50 border border-surface-600/35 hover:border-red-500/20 text-gray-400 hover:text-red-400 flex items-center justify-center transition-all cursor-pointer hover:bg-red-500/10">
                                                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                                            </button>
                                                        </form>
                                                        @break
                                                    @case('processing')
                                                        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-accent/10 border border-accent/20 shimmer-bg">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-accent animate-pulse-soft"></span>
                                                            <span class="text-[10px] text-accent font-medium">Menganalisis...</span>
                                                        </div>
                                                        <form action="{{ route('clipper.cancel', $clip) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" title="Batalkan Analisis" class="w-7 h-7 rounded-lg bg-surface-700/50 border border-surface-600/35 hover:border-red-500/20 text-gray-400 hover:text-red-400 flex items-center justify-center transition-all cursor-pointer hover:bg-red-500/10">
                                                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                                            </button>
                                                        </form>
                                                        @break
                                                    @case('completed')
                                                        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-pop/10 border border-pop/20">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-pop"></span>
                                                            <span class="text-[10px] text-pop font-medium">Selesai Analisis</span>
                                                        </div>
                                                        <form action="{{ route('clipper.cancel', $clip) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" title="Hapus dari Riwayat" class="w-7 h-7 rounded-lg bg-surface-700/50 border border-surface-600/35 hover:border-red-500/20 text-gray-400 hover:text-red-400 flex items-center justify-center transition-all cursor-pointer hover:bg-red-500/10">
                                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                            </button>
                                                        </form>
                                                        @break
                                                    @case('failed')
                                                        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-red-500/10 border border-red-500/20 cursor-help" title="{{ $clip->error_message }}">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-red-400/60"></span>
                                                            <span class="text-[10px] text-red-400 font-medium">Gagal</span>
                                                        </div>
                                                        <form action="{{ route('clipper.cancel', $clip) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" title="Hapus dari Riwayat" class="w-7 h-7 rounded-lg bg-surface-700/50 border border-surface-600/35 hover:border-red-500/20 text-gray-400 hover:text-red-400 flex items-center justify-center transition-all cursor-pointer hover:bg-red-500/10">
                                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                            </button>
                                                        </form>
                                                        @break
                                                @endswitch
                                            </div>
                                        </div>

                                        {{-- Nested Child Clips --}}
                                        @if($clip->children->count() > 0)
                                            <div class="px-6 py-5 bg-surface-900/40 space-y-3">
                                                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                                                    <i data-lucide="layers" class="w-3 h-3 text-accent"></i>
                                                    Klip Segmentasi AI:
                                                </p>
                                                <div class="space-y-3">
                                                    @foreach($clip->children as $child)
                                                        <div class="child-clip-item flex items-start justify-between gap-4 px-6 py-3 rounded-xl bg-surface-800/60 border border-surface-600/30 hover:border-surface-500/50 hover:bg-surface-800 transition-all duration-200" data-child-id="{{ $child->id }}" data-status="{{ $child->status }}">
                                                            <div class="min-w-0 flex-1">
                                                                <div class="flex items-center gap-2">
                                                                    <i data-lucide="video" class="w-3 h-3 text-gray-500 shrink-0"></i>
                                                                    <h4 class="text-xs font-bold text-white truncate" title="{{ $child->video_title }}">{{ $child->video_title }}</h4>
                                                                </div>
                                                                @if($child->status === 'processing')
                                                                    <div class="child-step-container mt-1 flex flex-col gap-0.5" id="child-step-{{ $child->id }}" data-elapsed="{{ $child->getElapsedSeconds() }}" data-estimated="{{ $child->getEstimatedDuration() }}">
                                                                        <span class="text-[9px] text-accent-glow font-medium animate-pulse flex items-center gap-1">
                                                                            <i data-lucide="loader-2" class="w-2.5 h-2.5 animate-spin"></i>
                                                                            <span class="step-text">{{ $child->current_step ?? 'Memotong...' }}</span>
                                                                        </span>
                                                                        <span class="eta-text text-[8px] text-gray-500 font-mono pl-3.5">Estimasi selesai: --</span>
                                                                    </div>
                                                                @endif
                                                                <div class="flex items-center gap-2 mt-1">
                                                                    <span class="text-[9px] text-gray-600 font-mono bg-surface-900/50 px-1.5 py-0.5 rounded">
                                                                        {{ $child->start_time }} → {{ $child->end_time }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="shrink-0 flex items-center gap-2 self-start mt-0.5">
                                                                @switch($child->status)
                                                                    @case('pending')
                                                                        <span class="text-[9px] text-yellow-400 bg-yellow-500/10 px-2 py-0.5 rounded font-medium border border-yellow-500/20">Antri</span>
                                                                        <form action="{{ route('clipper.cancel', $child) }}" method="POST" class="inline">
                                                                            @csrf
                                                                            <button type="submit" title="Batalkan" class="w-6 h-6 rounded bg-surface-700/50 border border-surface-600/35 hover:border-red-500/20 text-gray-400 hover:text-red-400 flex items-center justify-center transition-all cursor-pointer hover:bg-red-500/10">
                                                                                <i data-lucide="x" class="w-3 h-3"></i>
                                                                            </button>
                                                                        </form>
                                                                        @break
                                                                    @case('processing')
                                                                        <span class="text-[9px] text-accent bg-accent/10 px-2 py-0.5 rounded font-medium border border-accent/20">Memotong</span>
                                                                        <form action="{{ route('clipper.cancel', $child) }}" method="POST" class="inline">
                                                                            @csrf
                                                                            <button type="submit" title="Batalkan" class="w-6 h-6 rounded bg-surface-700/50 border border-surface-600/35 hover:border-red-500/20 text-gray-400 hover:text-red-400 flex items-center justify-center transition-all cursor-pointer hover:bg-red-500/10">
                                                                                <i data-lucide="x" class="w-3 h-3"></i>
                                                                            </button>
                                                                        </form>
                                                                        @break
                                                                    @case('completed')
                                                                        <a href="{{ $child->status === 'completed' ? route('clipper.download', $child) : '#' }}" class="flex items-center gap-1 px-2.5 py-1 rounded text-[9px] font-bold text-surface-900 bg-pop hover:bg-pop-dim transition-all shadow">
                                                                            <i data-lucide="download" class="w-3 h-3"></i>
                                                                            Download
                                                                        </a>
                                                                        @break
                                                                    @case('failed')
                                                                        <span class="text-[9px] text-red-400 bg-red-500/10 px-2 py-0.5 rounded font-medium border border-red-500/20 cursor-help" title="{{ $child->error_message }}">Gagal</span>
                                                                        @break
                                                                @endswitch
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    {{-- SINGLE STANDALONE MANUAL CLIP CARD --}}
                                    <div class="card-hover bg-surface-900/50 rounded-xl border border-surface-600/80 px-6 py-5 flex items-start justify-between gap-4 clip-item shadow-md" data-clip-id="{{ $clip->id }}" data-status="{{ $clip->status }}">
                                        
                                        {{-- Left: info --}}
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center gap-1 text-[9px] font-bold text-gray-400 bg-surface-700/80 px-2 py-0.5 rounded uppercase tracking-wider">
                                                    Manual
                                                </span>
                                                <p class="text-xs font-bold text-white truncate" title="{{ $clip->video_title ?? $clip->youtube_url }}">{{ $clip->video_title ?? $clip->youtube_url }}</p>
                                            </div>
                                            @if($clip->status === 'processing')
                                                <div class="parent-step-container mt-1.5 flex flex-col gap-0.5" id="parent-step-{{ $clip->id }}" data-elapsed="{{ $clip->getElapsedSeconds() }}" data-estimated="{{ $clip->getEstimatedDuration() }}">
                                                    <span class="text-[10px] text-accent-glow font-medium animate-pulse flex items-center gap-1.5">
                                                        <i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i>
                                                        <span class="step-text">{{ $clip->current_step ?? 'Mempersiapkan pemotongan...' }}</span>
                                                    </span>
                                                    <span class="eta-text text-[9px] text-gray-500 font-mono pl-5">Estimasi selesai: --</span>
                                                </div>
                                            @endif
                                            <div class="flex items-center gap-2.5 mt-1.5 flex-wrap">
                                                <span class="text-[10px] text-gray-500 truncate max-w-[150px] sm:max-w-xs" title="{{ $clip->youtube_url }}">{{ $clip->youtube_url }}</span>
                                                <span class="inline-flex items-center gap-1 text-[10px] text-gray-500 font-mono bg-surface-800/80 px-2 py-0.5 rounded">
                                                    <i data-lucide="timer" class="w-3 h-3 text-pop"></i>
                                                    {{ $clip->start_time }} → {{ $clip->end_time }}
                                                </span>
                                                <span class="text-[10px] text-gray-600">{{ $clip->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>

                                        {{-- Right: status/action --}}
                                        <div class="shrink-0 flex items-center gap-2.5 self-start mt-0.5">
                                            @switch($clip->status)
                                                @case('pending')
                                                    <div class="flex items-center gap-1.5 px-3 py-2 rounded-lg bg-yellow-500/10 border border-yellow-500/20">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500/60 animate-pulse"></span>
                                                        <span class="text-[10px] text-yellow-400 font-medium">Antri</span>
                                                    </div>
                                                    <form action="{{ route('clipper.cancel', $clip) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" title="Batalkan Pemotongan" class="w-7 h-7 rounded-lg bg-surface-700/50 border border-surface-600/35 hover:border-red-500/20 text-gray-400 hover:text-red-400 flex items-center justify-center transition-all cursor-pointer hover:bg-red-500/10">
                                                             <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                                        </button>
                                                    </form>
                                                    @break
                                                @case('processing')
                                                    <div class="flex items-center gap-1.5 px-3 py-2 rounded-lg bg-accent/10 border border-accent/20 shimmer-bg">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-accent animate-pulse-soft"></span>
                                                        <span class="text-[10px] text-accent font-medium">Memotong</span>
                                                    </div>
                                                    <form action="{{ route('clipper.cancel', $clip) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" title="Batalkan Pemotongan" class="w-7 h-7 rounded-lg bg-surface-700/50 border border-surface-600/35 hover:border-red-500/20 text-gray-400 hover:text-red-400 flex items-center justify-center transition-all cursor-pointer hover:bg-red-500/10">
                                                             <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                                        </button>
                                                    </form>
                                                    @break
                                                @case('failed')
                                                    <div class="flex items-center gap-1.5 px-3 py-2 rounded-lg bg-red-500/10 border border-red-500/20 cursor-help" title="{{ $clip->error_message }}">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-red-400/60"></span>
                                                        <span class="text-[10px] text-red-400 font-medium">Gagal</span>
                                                    </div>
                                                    <form action="{{ route('clipper.cancel', $clip) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" title="Hapus dari Riwayat" class="w-7 h-7 rounded-lg bg-surface-700/50 border border-surface-600/35 hover:border-red-500/20 text-gray-400 hover:text-red-400 flex items-center justify-center transition-all cursor-pointer hover:bg-red-500/10">
                                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                        </button>
                                                    </form>
                                                    @break
                                            @endswitch
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- SECTION 2: COMPLETED CLIPS GALLERY --}}
                <div class="bg-surface-800 rounded-2xl border border-surface-600 shadow-2xl relative overflow-hidden flex flex-col">
                    <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-pop via-green-500 to-emerald-400"></div>
                    
                    {{-- Header Completed --}}
                    <div class="px-6 py-5 border-b border-surface-600/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-surface-700 border border-surface-500 flex items-center justify-center shrink-0">
                                <i data-lucide="check-circle" class="w-4.5 h-4.5 text-green-400"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-white">Klip Video Selesai</h2>
                                <p class="text-xs text-gray-500">Daftar video yang berhasil dipotong dan siap diunduh</p>
                            </div>
                        </div>
                        <div class="text-[10px] font-bold text-green-400 bg-green-500/10 border border-green-500/20 px-2.5 py-1 rounded-full uppercase tracking-wider flex items-center gap-1.5">
                            {{ $completedClips->count() }} klip
                        </div>
                    </div>

                    {{-- Completed List --}}
                    <div class="p-6">
                        @if($completedClips->count() > 0)
                            <div class="max-h-[500px] overflow-y-auto pr-1 space-y-4 stagger-children scrollbar-thin">
                                @foreach($completedClips as $clip)
                                    @if($clip->is_smart)
                                        {{-- SMART CLIP COLLECTION CARD (COMPLETED) --}}
                                        <div class="bg-surface-900/60 rounded-xl border border-surface-600/80 overflow-hidden clip-item shadow-md hover:border-surface-500/80 transition-all duration-300" data-clip-id="{{ $clip->id }}" data-status="{{ $clip->status }}">
                                            
                                            {{-- Parent Header --}}
                                            <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-start justify-between gap-4 bg-surface-800/80 border-b border-surface-600/30">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 flex-wrap">
                                                        <span class="inline-flex items-center gap-1 text-[9px] font-extrabold text-pop bg-pop/10 border border-pop/20 px-2 py-0.5 rounded uppercase tracking-wider">
                                                            <i data-lucide="sparkles" class="w-2.5 h-2.5"></i>
                                                            Klip Pintar
                                                        </span>
                                                        @if($clip->smart_prompt)
                                                            <span class="inline-flex items-center gap-1 text-[9px] font-semibold text-accent bg-accent/10 border border-accent/20 px-2 py-0.5 rounded">
                                                                Topik: {{ $clip->smart_prompt }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <h3 class="text-xs font-bold text-white truncate mt-1.5" title="{{ $clip->video_title }}">{{ $clip->video_title }}</h3>
                                                    <div class="flex items-center gap-2.5 mt-1 flex-wrap">
                                                        <span class="text-[10px] text-gray-500 truncate max-w-[150px] sm:max-w-xs" title="{{ $clip->youtube_url }}">{{ $clip->youtube_url }}</span>
                                                        @if($clip->original_duration)
                                                            <span class="text-[10px] text-gray-600 font-mono">Durasi: {{ $clip->original_duration }}</span>
                                                        @endif
                                                        <span class="text-[10px] text-gray-600">{{ $clip->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>

                                                {{-- Parent Status / Clear Button --}}
                                                <div class="shrink-0 self-start flex items-center gap-2.5 mt-0.5">
                                                    <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-pop/10 border border-pop/20">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-pop"></span>
                                                        <span class="text-[10px] text-pop font-medium">Selesai</span>
                                                    </div>
                                                    <form action="{{ route('clipper.cancel', $clip) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" title="Hapus dari Riwayat" class="w-7 h-7 rounded-lg bg-surface-700/50 border border-surface-600/35 hover:border-red-500/20 text-gray-400 hover:text-red-400 flex items-center justify-center transition-all cursor-pointer hover:bg-red-500/10">
                                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>

                                            {{-- Nested Child Clips --}}
                                            @if($clip->children->count() > 0)
                                                <div class="px-6 py-5 bg-surface-900/40 space-y-3">
                                                    <p class="text-[9px] font-bold text-gray-500 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                                                        <i data-lucide="layers" class="w-3 h-3 text-accent"></i>
                                                        Klip Segmentasi AI:
                                                    </p>
                                                    <div class="space-y-3">
                                                        @foreach($clip->children as $child)
                                                            <div class="child-clip-item flex items-start justify-between gap-4 px-6 py-3 rounded-xl bg-surface-800/60 border border-surface-600/30 hover:border-surface-500/50 hover:bg-surface-800 transition-all duration-200" data-child-id="{{ $child->id }}" data-status="{{ $child->status }}">
                                                                <div class="min-w-0 flex-1">
                                                                    <div class="flex items-center gap-2">
                                                                        <i data-lucide="video" class="w-3 h-3 text-gray-500 shrink-0"></i>
                                                                        <h4 class="text-xs font-bold text-white truncate" title="{{ $child->video_title }}">{{ $child->video_title }}</h4>
                                                                    </div>
                                                                    <div class="flex items-center gap-2 mt-1">
                                                                        <span class="text-[9px] text-gray-600 font-mono bg-surface-900/50 px-1.5 py-0.5 rounded">
                                                                            {{ $child->start_time }} → {{ $child->end_time }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="shrink-0 flex items-center gap-2 self-start mt-0.5">
                                                                    @if($child->status === 'completed')
                                                                        <a href="{{ route('clipper.download', $child) }}" class="flex items-center gap-1 px-2.5 py-1 rounded text-[9px] font-bold text-surface-900 bg-pop hover:bg-pop-dim transition-all shadow">
                                                                            <i data-lucide="download" class="w-3 h-3"></i>
                                                                            Download
                                                                        </a>
                                                                    @else
                                                                        <span class="text-[9px] text-red-400 bg-red-500/10 px-2 py-0.5 rounded font-medium border border-red-500/20 cursor-help" title="{{ $child->error_message }}">Gagal</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        {{-- SINGLE STANDALONE MANUAL COMPLETED CLIP CARD --}}
                                        <div class="card-hover bg-surface-900/50 rounded-xl border border-surface-600/80 px-6 py-5 flex items-start justify-between gap-4 clip-item shadow-md" data-clip-id="{{ $clip->id }}" data-status="{{ $clip->status }}">
                                            
                                            {{-- Left: info --}}
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex items-center gap-1 text-[9px] font-bold text-gray-400 bg-surface-700/80 px-2 py-0.5 rounded uppercase tracking-wider">
                                                        Manual
                                                    </span>
                                                    <p class="text-xs font-bold text-white truncate" title="{{ $clip->video_title ?? $clip->youtube_url }}">{{ $clip->video_title ?? $clip->youtube_url }}</p>
                                                </div>
                                                <div class="flex items-center gap-2.5 mt-1.5 flex-wrap">
                                                    <span class="text-[10px] text-gray-500 truncate max-w-[150px] sm:max-w-xs" title="{{ $clip->youtube_url }}">{{ $clip->youtube_url }}</span>
                                                    <span class="inline-flex items-center gap-1 text-[10px] text-gray-500 font-mono bg-surface-800/80 px-2 py-0.5 rounded">
                                                        <i data-lucide="timer" class="w-3 h-3 text-pop"></i>
                                                        {{ $clip->start_time }} → {{ $clip->end_time }}
                                                    </span>
                                                    <span class="text-[10px] text-gray-600">{{ $clip->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>

                                            {{-- Right: actions --}}
                                            <div class="shrink-0 flex items-center gap-2.5 self-start mt-0.5">
                                                <a href="{{ route('clipper.download', $clip) }}" class="flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-[10px] font-bold text-surface-900 bg-pop hover:bg-pop-dim transition-all hover:shadow-lg hover:shadow-pop/20 hover:-translate-y-0.5 shadow-md">
                                                    <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                                    Download
                                                </a>
                                                <form action="{{ route('clipper.cancel', $clip) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" title="Hapus dari Riwayat" class="w-7 h-7 rounded-lg bg-surface-700/50 border border-surface-600/35 hover:border-red-500/20 text-gray-400 hover:text-red-400 flex items-center justify-center transition-all cursor-pointer hover:bg-red-500/10">
                                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            {{-- Empty State --}}
                            <div class="py-16 flex flex-col items-center justify-center text-center space-y-4 animate-scale-in">
                                <div class="w-16 h-16 rounded-full bg-surface-700/50 border border-surface-600 flex items-center justify-center text-gray-500 mb-2">
                                    <i data-lucide="film" class="w-7 h-7 text-gray-500"></i>
                                </div>
                                <div class="space-y-1">
                                    <h3 class="text-sm font-bold text-white">Belum Ada Klip Selesai</h3>
                                    <p class="text-xs text-gray-500 max-w-xs mx-auto">Klip yang berhasil dipotong akan otomatis muncul di bagian ini.</p>
                                </div>
                            </div>
                        @endif
                    </div>
            </div>
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

                // Update step message if processing
                if (data.status === 'processing' && data.current_step) {
                    let stepContainer = parentEl.querySelector(`#parent-step-${id}`);
                    if (!stepContainer) {
                        const titleBlock = parentEl.querySelector('.min-w-0');
                        if (titleBlock) {
                            const titleEl = titleBlock.querySelector('h3') || titleBlock.querySelector('.flex');
                            stepContainer = document.createElement('div');
                            stepContainer.id = `parent-step-${id}`;
                            stepContainer.className = 'parent-step-container mt-1.5 flex flex-col gap-0.5';
                            stepContainer.innerHTML = `
                                <span class="text-[10px] text-accent-glow font-medium animate-pulse flex items-center gap-1.5">
                                    <i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i>
                                    <span class="step-text"></span>
                                </span>
                                <span class="eta-text text-[9px] text-gray-500 font-mono pl-5">Estimasi selesai: --</span>
                            `;
                            titleEl.parentNode.insertBefore(stepContainer, titleEl.nextSibling);
                            lucide.createIcons();
                        }
                    }
                    if (stepContainer) {
                        stepContainer.setAttribute('data-elapsed', data.elapsed_seconds || 0);
                        stepContainer.setAttribute('data-estimated', data.estimated_duration || 0);
                        const textEl = stepContainer.querySelector('.step-text');
                        if (textEl && textEl.textContent !== data.current_step) {
                            textEl.textContent = data.current_step;
                        }
                    }
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

                        // Update child step message if processing
                        if (child.status === 'processing' && child.current_step) {
                            let childStepContainer = childDom.querySelector(`#child-step-${child.id}`);
                            if (!childStepContainer) {
                                const detailsEl = childDom.querySelector('h4');
                                if (detailsEl) {
                                    childStepContainer = document.createElement('div');
                                    childStepContainer.id = `child-step-${child.id}`;
                                    childStepContainer.className = 'child-step-container mt-1 flex flex-col gap-0.5';
                                    childStepContainer.innerHTML = `
                                        <span class="text-[9px] text-accent-glow font-medium animate-pulse flex items-center gap-1">
                                            <i data-lucide="loader-2" class="w-2.5 h-2.5 animate-spin"></i>
                                            <span class="step-text"></span>
                                        </span>
                                        <span class="eta-text text-[8px] text-gray-500 font-mono pl-3.5">Estimasi selesai: --</span>
                                    `;
                                    detailsEl.parentNode.insertBefore(childStepContainer, detailsEl.nextSibling);
                                    lucide.createIcons();
                                }
                            }
                            if (childStepContainer) {
                                childStepContainer.setAttribute('data-elapsed', child.elapsed_seconds || 0);
                                childStepContainer.setAttribute('data-estimated', child.estimated_duration || 0);
                                const textEl = childStepContainer.querySelector('.step-text');
                                if (textEl && textEl.textContent !== child.current_step) {
                                    textEl.textContent = child.current_step;
                                }
                            }
                        }
                    }
                }
            } catch (e) {}
        });
    }

    // Live ticking countdown for ETA
    function tickCountdown() {
        const containers = document.querySelectorAll('.parent-step-container, .child-step-container');
        containers.forEach(container => {
            let elapsed = parseInt(container.getAttribute('data-elapsed') || 0);
            let estimated = parseInt(container.getAttribute('data-estimated') || 0);
            
            // Only tick if estimated duration is set
            if (estimated > 0) {
                elapsed++;
                container.setAttribute('data-elapsed', elapsed);
                
                const remaining = Math.max(0, estimated - elapsed);
                const etaTextEl = container.querySelector('.eta-text');
                if (etaTextEl) {
                    if (remaining > 0) {
                        etaTextEl.textContent = `Estimasi selesai: ~${remaining} detik`;
                    } else {
                        etaTextEl.textContent = 'Hampir selesai...';
                    }
                }
            }
        });
    }
    // Run tick every second
    setInterval(tickCountdown, 1000);
    // Initial run to show values immediately
    setTimeout(tickCountdown, 100);

    setInterval(pollStatus, 4000);
</script>
@endpush
