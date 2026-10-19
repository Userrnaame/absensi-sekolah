<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — SMK Negeri 1 Pedan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'] },
                    colors: {
                        'smk-blue': '#2d3fa0',
                        'smk-blue-dark': '#1e2d7a',
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50">

    <div class="min-h-screen grid lg:grid-cols-2">
        <div class="hidden lg:flex relative overflow-hidden bg-slate-900">
            <div class="absolute inset-0 bg-cover bg-center"
                 style="background-image: url('{{ asset('images/hero-sekolah.jpg') }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/95 via-indigo-800/85 to-blue-800/80"></div>
            <div class="absolute -top-32 -right-32 w-96 h-96 bg-blue-400/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-20 w-96 h-96 bg-violet-400/20 rounded-full blur-3xl"></div>

            <div class="relative z-10 flex flex-col justify-between p-12 text-white w-full">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-smkn1pedan.png') }}"
                         alt="Logo SMKN 1 Pedan"
                         class="w-14 h-14 object-contain drop-shadow-lg">
                    <div>
                        <div class="font-bold text-lg leading-tight">SMK Negeri 1 Pedan</div>
                        <div class="text-xs text-blue-200">Portal Absensi Digital</div>
                    </div>
                </div>

                <div>
                    <h1 class="text-5xl font-black text-white leading-tight mb-4 tracking-tight">
                        Absensi<br>Sekolah
                    </h1>
                    <p class="text-blue-100 text-base leading-relaxed max-w-md">
                        Sistem absensi digital berbasis lokasi & selfie.
                        Cepat, akurat, dan mudah digunakan untuk siswa, guru, dan admin.
                    </p>

                    <div class="mt-8 space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center ring-1 ring-white/20">
                                <svg class="w-5 h-5 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-sm">Validasi GPS</div>
                                <div class="text-xs text-blue-200">Absen hanya dalam area sekolah</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center ring-1 ring-white/20">
                                <svg class="w-5 h-5 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-sm">Selfie Kehadiran</div>
                                <div class="text-xs text-blue-200">Bukti visual setiap absensi</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center ring-1 ring-white/20">
                                <svg class="w-5 h-5 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-sm">Pengajuan Izin Online</div>
                                <div class="text-xs text-blue-200">Izin, sakit, dan cuti lebih mudah</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-xs text-blue-200">
                    © {{ date('Y') }} SMK Negeri 1 Pedan — All rights reserved
                </div>
            </div>
        </div>

        <div class="flex items-center justify-center p-4 md:p-8 bg-slate-50">
            <div class="w-full max-w-md">
                <div class="lg:hidden flex items-center justify-center gap-3 mb-6">
                    <img src="{{ asset('images/logo-smkn1pedan.png') }}" alt="Logo" class="w-12 h-12 object-contain">
                    <div>
                        <div class="font-bold text-smk-blue text-lg">SMK Negeri 1 Pedan</div>
                        <div class="text-xs text-slate-500">Portal Absensi Digital</div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-2xl shadow-indigo-500/10 p-8 md:p-10">
                    <div class="hidden lg:flex justify-center mb-6">
                        <img src="{{ asset('images/logo-smkn1pedan.png') }}"
                             alt="Logo Sekolah"
                             class="w-20 h-20 object-contain">
                    </div>

                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-extrabold text-slate-900 mb-2 tracking-tight">
                            Selamat Datang
                        </h2>
                        <p class="text-sm text-slate-500">
                            Silakan login dengan NIS Anda
                        </p>
                    </div>

                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-5 text-sm ring-1 ring-red-600/10 flex items-start gap-2">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="nis" class="block text-sm font-semibold text-slate-700 mb-2">NIS</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <input type="text" id="nis" name="nis" value="{{ old('nis') }}"
                                       placeholder="Masukkan NIS" required autofocus
                                       class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-xl placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                            <div class="relative" x-data="{ show: false }">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input :type="show ? 'text' : 'password'" id="password" name="password"
                                       placeholder="Masukkan Password" required
                                       class="w-full pl-11 pr-11 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-xl placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                                <button type="button" @click="show = !show"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition">
                                    <template x-if="!show">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </template>
                                    <template x-if="show">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                    </template>
                                </button>
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-all duration-150 active:scale-95 shadow-lg shadow-indigo-500/30 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Masuk
                        </button>
                    </form>

                    <div class="mt-6 text-center">
                        <a href="{{ route('portal.index') }}"
                           class="text-sm text-slate-500 hover:text-indigo-600 transition inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke Portal Sekolah
                        </a>
                    </div>
                </div>

                <div class="text-center text-xs text-slate-400 mt-6 lg:hidden">
                    © {{ date('Y') }} SMK Negeri 1 Pedan
                </div>
            </div>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>