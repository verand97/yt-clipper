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

            {{-- Time inputs --}}
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
        <div class="space-y-2.5 stagger-children">
            @foreach($clips as $clip)
            <div class="card-hover bg-surface-800 rounded-xl border border-surface-600 px-5 py-4 flex items-center justify-between gap-4 clip-item" data-clip-id="{{ $clip->id }}" data-status="{{ $clip->status }}">

                {{-- Left: info --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-200 truncate font-medium">{{ $clip->youtube_url }}</p>
                    <div class="flex items-center gap-3 mt-1.5">
                        <span class="inline-flex items-center gap-1 text-[11px] text-gray-500 font-mono bg-surface-700 px-2 py-0.5 rounded">
                            <i data-lucide="timer" class="w-3 h-3"></i>
                            {{ $clip->start_time }} → {{ $clip->end_time }}
                        </span>
                        <span class="text-[11px] text-gray-600">{{ $clip->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                {{-- Right: status/action --}}
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
                        <a href="{{ route('clipper.download', $clip) }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold text-surface-900 bg-pop hover:bg-pop-dim transition-all hover:shadow-lg hover:shadow-pop/20 hover:-translate-y-0.5">
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
                <i data-lucide="repeat" class="w-4 h-4 text-pop"></i>
            </div>
            <h4 class="text-sm font-semibold text-white">Auto Retry</h4>
            <p class="text-xs text-gray-500 leading-relaxed">Jika gagal, sistem otomatis mencoba ulang tanpa perlu submit ulang.</p>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Poll status for active clips
    function pollStatus() {
        const items = document.querySelectorAll('.clip-item[data-status="pending"], .clip-item[data-status="processing"]');
        if (!items.length) return;

        items.forEach(async (el) => {
            try {
                const res = await fetch(`/clip/${el.dataset.clipId}/status`);
                const data = await res.json();
                if (data.status !== el.dataset.status) {
                    window.location.reload();
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
