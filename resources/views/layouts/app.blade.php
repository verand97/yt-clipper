<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'YouTube Clipper') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        surface: {
                            900: '#0f0f12',
                            800: '#16161b',
                            700: '#1c1c23',
                            600: '#25252e',
                            500: '#32323e',
                            400: '#42424f',
                        },
                        accent: '#7F56FF',
                        'accent-dim': '#6344cc',
                        'accent-glow': '#a78bfa',
                        pop: '#80FF56',
                        'pop-dim': '#5ecc3e',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                }
            }
        }
    </script>

    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        * { box-sizing: border-box; }
        body {
            background: #0f0f12;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #32323e; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #7F56FF; }

        /* Hero gradient orb */
        .hero-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.12;
            pointer-events: none;
        }

        /* Grid pattern */
        .grid-pattern {
            background-image:
                linear-gradient(rgba(127, 86, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(127, 86, 255, 0.03) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-12px) rotate(1deg); }
        }
        @keyframes float-reverse {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(12px) rotate(-1deg); }
        }
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes scale-in {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes slide-right {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        @keyframes orbit {
            from { transform: rotate(0deg) translateX(120px) rotate(0deg); }
            to { transform: rotate(360deg) translateX(120px) rotate(-360deg); }
        }

        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-reverse { animation: float-reverse 7s ease-in-out infinite; }
        .animate-fade-up { animation: fade-up 0.6s ease-out forwards; }
        .animate-fade-up-delay-1 { animation: fade-up 0.6s ease-out 0.1s forwards; opacity: 0; }
        .animate-fade-up-delay-2 { animation: fade-up 0.6s ease-out 0.2s forwards; opacity: 0; }
        .animate-fade-up-delay-3 { animation: fade-up 0.6s ease-out 0.3s forwards; opacity: 0; }
        .animate-fade-up-delay-4 { animation: fade-up 0.6s ease-out 0.4s forwards; opacity: 0; }
        .animate-scale-in { animation: scale-in 0.5s ease-out forwards; }
        .animate-slide-right { animation: slide-right 0.4s ease-out forwards; }
        .animate-pulse-soft { animation: pulse-soft 2s ease-in-out infinite; }
        .animate-orbit { animation: orbit 20s linear infinite; }

        .shimmer-bg {
            background: linear-gradient(90deg, transparent 0%, rgba(127,86,255,0.04) 50%, transparent 100%);
            background-size: 200% 100%;
            animation: shimmer 3s linear infinite;
        }

        /* Input focus */
        .input-field {
            transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
        }
        .input-field:focus {
            border-color: #7F56FF;
            box-shadow: 0 0 0 3px rgba(127, 86, 255, 0.08);
            background-color: #1c1c23;
        }

        /* Button */
        .btn-primary {
            background: linear-gradient(135deg, #7F56FF 0%, #6344cc 100%);
            transition: all 0.25s;
            position: relative;
            overflow: hidden;
        }
        .btn-primary::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent 0%, rgba(255,255,255,0.1) 50%, transparent 100%);
            opacity: 0;
            transition: opacity 0.25s;
        }
        .btn-primary:hover::before { opacity: 1; }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(127, 86, 255, 0.25);
        }
        .btn-primary:active { transform: translateY(0); }

        /* Card hover */
        .card-hover {
            transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s;
        }
        .card-hover:hover {
            border-color: #32323e;
            transform: translateY(-1px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        /* Stagger children */
        .stagger-children > * {
            opacity: 0;
            animation: fade-up 0.4s ease-out forwards;
        }
        .stagger-children > *:nth-child(1) { animation-delay: 0s; }
        .stagger-children > *:nth-child(2) { animation-delay: 0.06s; }
        .stagger-children > *:nth-child(3) { animation-delay: 0.12s; }
        .stagger-children > *:nth-child(4) { animation-delay: 0.18s; }
        .stagger-children > *:nth-child(5) { animation-delay: 0.24s; }
        .stagger-children > *:nth-child(6) { animation-delay: 0.3s; }
        .stagger-children > *:nth-child(7) { animation-delay: 0.36s; }
        .stagger-children > *:nth-child(8) { animation-delay: 0.42s; }
        .stagger-children > *:nth-child(9) { animation-delay: 0.48s; }
        .stagger-children > *:nth-child(10) { animation-delay: 0.54s; }
    </style>
</head>
<body class="min-h-screen text-gray-300">

    {{-- Navigation --}}
    <nav class="sticky top-0 z-50 border-b border-white/[0.04] bg-surface-900/70 backdrop-blur-2xl">
        <div class="max-w-5xl mx-auto px-5 h-16 flex items-center justify-between">
            <a href="{{ route('clipper.index') }}" class="flex items-center gap-3 group">
                <div class="relative w-8 h-8 rounded-lg bg-gradient-to-br from-accent to-accent-dim flex items-center justify-center shadow-lg shadow-accent/20">
                    <i data-lucide="scissors" class="w-4 h-4 text-white"></i>
                </div>
                <span class="text-lg font-bold text-white tracking-tight">YT Clipper</span>
            </a>
            <div class="flex items-center gap-4">
                <a href="#clipper-form" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-accent/10 border border-accent/20 text-accent text-sm font-medium hover:bg-accent/15 transition-colors">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    Clip Baru
                </a>
            </div>
        </div>
    </nav>

    {{-- Main --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-white/[0.03] py-8 mt-20">
        <div class="max-w-5xl mx-auto px-5 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <i data-lucide="scissors" class="w-3.5 h-3.5"></i>
                <span>YT Clipper</span>
            </div>
            <p class="text-xs text-gray-700">Ditenagai oleh yt-dlp & FFmpeg</p>
        </div>
    </footer>

    <script>lucide.createIcons();</script>
    @stack('scripts')
</body>
</html>
