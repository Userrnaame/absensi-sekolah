<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Absensi Sekolah')</title>

    <!-- Favicon Kadas -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/kadas-icon.svg') }}">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'smk-blue': '#2d3fa0',
                        'smk-blue-dark': '#1e2d7a',
                        'smk-blue-light': '#4a5fc1',
                    }
                }
            }
        }
    </script>

    <style>
        /* Background pattern subtle */
        body {
            background-color: #f8fafc;
            background-image: 
                radial-gradient(circle at 1px 1px, rgba(99, 102, 241, 0.08) 1px, transparent 0);
            background-size: 24px 24px;
        }

        /* Scrollbar halus */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Selection */
        ::selection { background: #4f46e5; color: #ffffff; }

        /* Input autofill */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-text-fill-color: #0f172a;
            -webkit-box-shadow: 0 0 0px 1000px #f8fafc inset;
            transition: background-color 5000s ease-in-out 0s;
        }

        /* Animasi fade in */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp 0.4s ease-out; }

        /* Safe Area untuk HP yang ada Notch (iPhone dll) */
        .pb-safe {
            padding-bottom: env(safe-area-inset-bottom);
        }

        /* =========================================
           DARK MODE OVERRIDES (Trik agar tidak perlu edit semua file)
           ========================================= */
        html.dark body { background-color: #0f172a !important; color: #f8fafc !important; }
        html.dark .bg-white { background-color: #1e293b !important; color: #f8fafc !important; }
        html.dark .bg-slate-50 { background-color: #0f172a !important; }
        html.dark .text-slate-900 { color: #f8fafc !important; }
        html.dark .text-slate-800 { color: #f1f5f9 !important; }
        html.dark .text-slate-500 { color: #94a3b8 !important; }
        html.dark .border-slate-100 { border-color: #334155 !important; }
        html.dark .border-slate-200 { border-color: #334155 !important; }
        html.dark .bg-slate-100 { background-color: #1e293b !important; }
        html.dark input, html.dark textarea, html.dark select {
            background-color: #0f172a !important;
            color: #f8fafc !important;
            border-color: #334155 !important;
        }
        html.dark nav.bg-white\/90 { background-color: rgba(15, 23, 42, 0.9) !important; }
        html.dark nav .text-slate-600 { color: #94a3b8 !important; }
        html.dark nav .text-slate-900 { color: #f8fafc !important; }
        html.dark .bg-slate-900\/40 { background-color: rgba(0, 0, 0, 0.7) !important; }
    </style>

    @stack('styles')

    <!-- Canvas Confetti -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>

    <!-- Global SFX Function -->
    <script>
        function playSound(soundName) {
            try {
                const audio = new Audio(`/audio/${soundName}.mp3`);
                audio.volume = 0.5;
                audio.play().catch(e => console.log('Audio block by browser:', e));
            } catch (error) {
                console.log('Error playing sound', error);
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen antialiased flex flex-col">
    
    <!-- Konten Utama -->
    <main class="flex-1 pb-16 md:pb-0">
        @yield('content')
    </main>

    {{-- ============ BOTTOM NAVIGATION (MOBILE ONLY) ============ --}}
    @auth
        @include('partials.bottom-nav')
    @endauth

    {{-- ============ FOOTER KADAS ============ --}}
    @auth
        <footer class="mt-12 py-6 border-t border-slate-100 bg-white dark:bg-slate-900">
            <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/kadas-logo.svg') }}" alt="Kadas" class="h-10 opacity-80">
                </div>
                <div class="text-xs text-slate-400 text-center md:text-right">
                    © {{ date('Y') }} SMK Negeri 1 Pedan — Powered by <strong class="text-indigo-600 dark:text-indigo-400">Kadas</strong>
                </div>
            </div>
        </footer>
    @endauth

    {{-- ============ FLOATING MASCOT ============ --}}
    @auth
        @include('partials.mascot')
    @endauth

    {{-- ============ TOAST CONTAINER ============ --}}
    <div id="toast-container" class="fixed top-20 right-4 z-[9999] space-y-3 pointer-events-none"></div>

    {{-- ============ TOAST SCRIPT ============ --}}
    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = 'pointer-events-auto transform transition-all duration-300 ease-out translate-x-full opacity-0';

            const styles = {
                success: { bg: 'bg-emerald-50 dark:bg-emerald-900/50', border: 'border-emerald-200 dark:border-emerald-700', icon: 'bg-emerald-500', text: 'text-emerald-900 dark:text-emerald-100', svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>' },
                error:   { bg: 'bg-red-50 dark:bg-red-900/50',         border: 'border-red-200 dark:border-red-700',         icon: 'bg-red-500',     text: 'text-red-900 dark:text-red-100',     svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>' },
                warning: { bg: 'bg-amber-50 dark:bg-amber-900/50',     border: 'border-amber-200 dark:border-amber-700',     icon: 'bg-amber-500',   text: 'text-amber-900 dark:text-amber-100',   svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>' },
                info:    { bg: 'bg-blue-50 dark:bg-blue-900/50',       border: 'border-blue-200 dark:border-blue-700',       icon: 'bg-blue-500',    text: 'text-blue-900 dark:text-blue-100',    svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>' }
            };

            const s = styles[type] || styles.success;

            toast.innerHTML = `
                <div class="flex items-start gap-3 ${s.bg} border ${s.border} rounded-2xl shadow-2xl p-4 min-w-[320px] max-w-md backdrop-blur-sm">
                    <div class="w-8 h-8 ${s.icon} rounded-full flex items-center justify-center flex-shrink-0 shadow-lg">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${s.svg}
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold ${s.text} leading-snug">${message}</p>
                    </div>
                    <button onclick="this.closest('.pointer-events-auto').remove()" class="text-slate-400 hover:text-slate-600 transition flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;

            container.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            });

            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }
    </script>

    @stack('scripts')

    <!-- ============ DARK MODE SCRIPT ============ -->
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }

        document.addEventListener('DOMContentLoaded', () => {
            const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            if (themeToggleDarkIcon && themeToggleLightIcon) {
                if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    themeToggleLightIcon.classList.remove('hidden');
                } else {
                    themeToggleDarkIcon.classList.remove('hidden');
                }

                const themeToggleBtn = document.getElementById('theme-toggle');

                themeToggleBtn.addEventListener('click', function() {
                    themeToggleDarkIcon.classList.toggle('hidden');
                    themeToggleLightIcon.classList.toggle('hidden');

                    if (localStorage.getItem('color-theme')) {
                        if (localStorage.getItem('color-theme') === 'light') {
                            document.documentElement.classList.add('dark');
                            localStorage.setItem('color-theme', 'dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                            localStorage.setItem('color-theme', 'light');
                        }
                    } else {
                        if (document.documentElement.classList.contains('dark')) {
                            document.documentElement.classList.remove('dark');
                            localStorage.setItem('color-theme', 'light');
                        } else {
                            document.documentElement.classList.add('dark');
                            localStorage.setItem('color-theme', 'dark');
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>