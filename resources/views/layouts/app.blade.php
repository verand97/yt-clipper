<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'YouTube Clipper') }}</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        charcoal: '#1E1F22',
                        'charcoal-light': '#2A2B2F',
                        'charcoal-lighter': '#35363B',
                        'neon-purple': '#7F56FF',
                        'neon-purple-hover': '#6B45E0',
                        'lime-green': '#80FF56',
                        'lime-green-hover': '#6BE048',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            background-color: #1E1F22;
            font-family: 'Inter', sans-serif;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #1E1F22;
        }
        ::-webkit-scrollbar-thumb {
            background: #7F56FF;
            border-radius: 3px;
        }

        /* Spinner animation */
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .animate-spin-slow {
            animation: spin 2s linear infinite;
        }

        /* Pulse glow for processing */
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 5px rgba(127, 86, 255, 0.3); }
            50% { box-shadow: 0 0 20px rgba(127, 86, 255, 0.6); }
        }
        .pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }
    </style>
</head>
<body class="min-h-screen text-gray-200 antialiased">
    {{-- Navigation Bar --}}
    <nav class="border-b border-neon-purple/30 bg-charcoal-light/50 backdrop-blur-sm sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('clipper.index') }}" class="flex items-center gap-2 group">
                <i data-lucide="scissors" class="w-6 h-6 text-neon-purple group-hover:text-lime-green transition-colors"></i>
                <span class="text-xl font-bold text-white">YT <span class="text-neon-purple">Clipper</span></span>
            </a>
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <i data-lucide="cpu" class="w-4 h-4"></i>
                <span>Video Processor</span>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="max-w-5xl mx-auto px-4 py-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-charcoal-lighter mt-12 py-6">
        <div class="max-w-5xl mx-auto px-4 text-center text-sm text-gray-500">
            <p>YouTube Clipper &mdash; Potong video YouTube dengan presisi.</p>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
