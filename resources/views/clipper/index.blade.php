@extends('layouts.app')

@section('content')

{{-- ======================== HERO SECTION ======================== --}}
<section class="relative overflow-hidden min-h-[85vh] flex items-center">
    {{-- Background effects --}}
    <div class="absolute inset-0 grid-pattern pointer-events-none"></div>
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
    <div class="relative max-w-5xl mx-auto px-5 py-20 w-full">
        <div class="max-w-2xl mx-auto text-center space-y-6">
            {{-- Badge --}}
            <div class="animate-fade-up inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-surface-700 border border-surface-500 text-xs text-gray-400">
                <span class="w-1.5 h-1.5 rounded-full bg-pop animate-pulse"></span>
                Gratis & Tanpa Batas
            </div>

            {{-- Title --}}
            <h1 class="animate-fade-up-delay-1 text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-[1.1] tracking-tight">
                Potong Video YouTube<br>
                <span class="bg-linear-to-r from-accent via-accent-glow to-pop bg-clip-text text-transparent">Dalam Sekejap</span>
            </h1>

            {{-- Subtitle --}}
            <p class="animate-fade-up-delay-2 text-base sm:text-lg text-gray-400 max-w-lg mx-auto leading-relaxed">
                Tempel link, pilih durasi, dan dapatkan klipnya secara instan. Tidak perlu mendownload seluruh video secara manual.
            </p>

            {{-- CTA --}}
            <div class="animate-fade-up-delay-3 flex items-center justify-center gap-4">
                <a href="{{ route('clipper.app') }}" class="btn-primary text-white font-semibold rounded-xl px-7 py-3.5 flex items-center gap-2 text-sm shadow-xl shadow-accent/25 hover:shadow-accent/40">
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
                <p class="text-2xl font-bold text-white">{{ $completedCount }}</p>
                <p class="text-xs text-gray-500 mt-1">Klip Selesai</p>
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
<section id="how-it-works" class="relative max-w-5xl mx-auto px-5 py-16 scroll-mt-20">
    <div class="text-center mb-12">
        <h2 class="text-sm font-semibold text-accent uppercase tracking-wider">Cara Kerja</h2>
        <p class="text-3xl font-bold text-white mt-2">Tiga langkah sederhana</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Step 1 --}}
        <div class="card-hover bg-surface-800 rounded-2xl border border-surface-600 p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-20 h-20 bg-accent/5 rounded-bl-[60px] group-hover:bg-accent/10 transition-colors"></div>
            <div class="w-10 h-10 rounded-xl bg-accent/10 border border-accent/20 flex items-center justify-center mb-4">
                <span class="text-accent font-bold text-sm">01</span>
            </div>
            <h3 class="text-white font-semibold mb-2">Tempel Link</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Salin URL video YouTube yang ingin dipotong, lalu tempel di halaman Clipper Workspace.</p>
        </div>

        {{-- Step 2 --}}
        <div class="card-hover bg-surface-800 rounded-2xl border border-surface-600 p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-20 h-20 bg-pop/5 rounded-bl-[60px] group-hover:bg-pop/10 transition-colors"></div>
            <div class="w-10 h-10 rounded-xl bg-pop/10 border border-pop/20 flex items-center justify-center mb-4">
                <span class="text-pop font-bold text-sm">02</span>
            </div>
            <h3 class="text-white font-semibold mb-2">Pilih Waktu / Deteksi AI</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Atur rentang waktu manual atau gunakan fitur AI (Klip Pintar) untuk mendeteksi bagian penting otomatis.</p>
        </div>

        {{-- Step 3 --}}
        <div class="card-hover bg-surface-800 rounded-2xl border border-surface-600 p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-20 h-20 bg-accent/5 rounded-bl-[60px] group-hover:bg-accent/10 transition-colors"></div>
            <div class="w-10 h-10 rounded-xl bg-accent/10 border border-accent/20 flex items-center justify-center mb-4">
                <span class="text-accent font-bold text-sm">03</span>
            </div>
            <h3 class="text-white font-semibold mb-2">Download Klip</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Tunggu pemrosesan di server selesai, lalu unduh file MP4 klip langsung ke penyimpanan lokal Anda.</p>
        </div>
    </div>
</section>

{{-- ======================== FEATURES SECTION ======================== --}}
<section class="relative max-w-5xl mx-auto px-5 py-16">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-surface-800/50 rounded-xl border border-surface-600 p-5 space-y-3 card-hover">
            <div class="w-9 h-9 rounded-lg bg-accent/10 flex items-center justify-center">
                <i data-lucide="zap" class="w-4 h-4 text-accent"></i>
            </div>
            <h4 class="text-sm font-semibold text-white">Proses Cepat</h4>
            <p class="text-xs text-gray-500 leading-relaxed">Sistem mendownload dan merender segmen yang Anda pilih secara efisien di server.</p>
        </div>
        <div class="bg-surface-800/50 rounded-xl border border-surface-600 p-5 space-y-3 card-hover">
            <div class="w-9 h-9 rounded-lg bg-pop/10 flex items-center justify-center">
                <i data-lucide="hard-drive" class="w-4 h-4 text-pop"></i>
            </div>
            <h4 class="text-sm font-semibold text-white">Hemat Penyimpanan</h4>
            <p class="text-xs text-gray-500 leading-relaxed">File klip berukuran kecil karena hanya berisi potongan video yang Anda inginkan.</p>
        </div>
        <div class="bg-surface-800/50 rounded-xl border border-surface-600 p-5 space-y-3 card-hover">
            <div class="w-9 h-9 rounded-lg bg-accent/10 flex items-center justify-center">
                <i data-lucide="shield-check" class="w-4 h-4 text-accent"></i>
            </div>
            <h4 class="text-sm font-semibold text-white">Kualitas Terjaga</h4>
            <p class="text-xs text-gray-500 leading-relaxed">Menggunakan encoding H.264 & AAC berkualitas tinggi dengan wrapper MP4 standar industri.</p>
        </div>
        <div class="bg-surface-800/50 rounded-xl border border-surface-600 p-5 space-y-3 card-hover">
            <div class="w-9 h-9 rounded-lg bg-pop/10 flex items-center justify-center">
                <i data-lucide="sparkles" class="w-4 h-4 text-pop"></i>
            </div>
            <h4 class="text-sm font-semibold text-white">Deteksi Pintar AI</h4>
            <p class="text-xs text-gray-500 leading-relaxed">Menggunakan integrasi Gemini AI canggih untuk menganalisis isi video otomatis.</p>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
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

    document.querySelectorAll('#how-it-works .card-hover, section:last-of-type .card-hover').forEach(el => {
        el.style.opacity = '0';
        observer.observe(el);
    });
</script>
@endpush
