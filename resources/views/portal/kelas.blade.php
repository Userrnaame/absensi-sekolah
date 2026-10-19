<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $namaKelas }} — SMK Negeri 1 Pedan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'] },
                    colors: {
                        'smk-blue': '#2d3fa0',
                        'smk-blue-dark': '#1e2d7a',
                        'smk-blue-light': '#4a5fc1',
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { color-scheme: light; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .hero-gradient { background: linear-gradient(135deg, #2446c8 0%, #1e2d7a 58%, #172554 100%); }
        .soft-grid {
            background-image: radial-gradient(rgba(255,255,255,.18) 1px, transparent 1px);
            background-size: 22px 22px;
        }
        .surface-card { box-shadow: 0 10px 30px rgba(15, 23, 42, .055); }
        .student-card { transform: translateY(0); }
        .student-card:hover { transform: translateY(-4px); }
        .modal-scroll { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
        [x-cloak] { display: none !important; }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; animation-duration: .01ms !important; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">

    <!-- NAVBAR -->
    <nav class="bg-white/90 backdrop-blur-xl shadow-sm sticky top-0 z-40 border-b border-slate-200/70">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('portal.index') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo-smkn1pedan.png') }}" alt="Logo" class="w-12 h-12 object-contain">
                <div>
                    <div class="font-bold text-smk-blue text-base md:text-lg leading-tight">SMK Negeri 1 Pedan</div>
                    <div class="text-xs text-slate-500 hidden md:block">Portal Informasi Kelas</div>
                </div>
            </a>
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-smk-blue text-white rounded-xl hover:bg-smk-blue-dark active:scale-95 transition-all duration-150 font-semibold text-sm shadow-lg shadow-smk-blue/25">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Absen
            </a>
        </div>
    </nav>

    <!-- BREADCRUMB -->
    <div class="bg-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <nav class="flex items-center gap-2 text-sm text-slate-500">
                <a href="{{ route('portal.index') }}" class="hover:text-smk-blue flex items-center gap-1 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Beranda
                </a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="font-semibold text-smk-blue">{{ $namaKelas }}</span>
            </nav>
        </div>
    </div>

    <!-- HERO KELAS -->
    <section class="hero-gradient text-white py-14 md:py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 soft-grid">
            <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="w-full h-full">
                <polygon fill="white" points="0,100 100,0 100,100"/>
            </svg>
        </div>
        <div class="max-w-7xl mx-auto px-4 text-center relative">
            <div class="inline-block px-4 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-semibold mb-4">
                Tahun Ajaran 2026/2027
            </div>
            <h1 class="text-3xl md:text-5xl font-extrabold mb-2 tracking-tight">{{ $namaKelas }}</h1>
            <p class="text-blue-100 text-sm md:text-base">
                {{ $jurusan }} — {{ $semuaSiswa->count() }} Siswa
            </p>
        </div>
    </section>

    <!-- WALI KELAS -->
    @if($waliKelas)
    <section class="py-10">
        <div class="max-w-3xl mx-auto px-4">
            <div class="bg-white rounded-2xl surface-card p-5 sm:p-6 border border-slate-100 border-l-4 border-l-smk-blue hover:shadow-lg transition-shadow">
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Wali Kelas</div>
                <div class="flex items-center gap-4">
                    <img src="{{ $waliKelas->foto_siswa_url }}" alt="{{ $waliKelas->name }}"
                         class="w-16 h-16 rounded-full object-cover bg-slate-100 border-2 border-smk-blue/20">
                    <div>
                        <div class="font-bold text-lg text-slate-900">{{ $waliKelas->name }}</div>
                        <div class="text-sm text-slate-500">Guru Pendamping Kelas</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- STRUKTUR KEPENGURUSAN -->
    @if($pengurus->count() > 0)
    <section class="pb-10">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mb-1 tracking-tight">Struktur Kepengurusan</h2>
                <p class="text-slate-500 text-sm">Pengurus kelas {{ $namaKelas }}</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($pengurus as $p)
                    @php
                        $jabatan = strtolower($p->jabatan_kelas ?? '');
                        $badgeClass = (str_contains($jabatan, 'ketua') && !str_contains($jabatan, 'wakil'))
                            ? 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-700/10'
                            : (str_contains($jabatan, 'wakil')
                                ? 'bg-violet-50 text-violet-700 ring-1 ring-violet-700/10'
                                : 'bg-slate-100 text-slate-600 ring-1 ring-slate-400/10');
                    @endphp
                    <div class="student-card bg-white rounded-2xl surface-card hover:shadow-xl hover:border-indigo-300 transition-all duration-300 p-4 text-center border border-slate-100 cursor-pointer">
                        <img src="{{ $p->foto_siswa_url }}" alt="{{ $p->name }}"
                             class="w-20 h-20 rounded-full object-cover mx-auto mb-3 bg-slate-100 border-2 border-indigo-100">
                        <div class="inline-block {{ $badgeClass }} rounded-full px-2.5 py-1 text-xs font-semibold mb-2">
                            {{ $p->jabatan_kelas }}
                        </div>
                        <div class="font-semibold text-slate-900 text-sm leading-tight">{{ $p->name }}</div>
                        <div class="text-xs text-slate-400 mt-1">No. {{ $p->nomor_absen }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- ============ ANGGOTA KELAS + SEARCH & FILTER ============ -->
    @php
        $siswaJson = $semuaSiswa->map(fn($s) => [
            'name'        => $s->name,
            'nis'         => $s->nis,
            'nomor_absen' => $s->nomor_absen,
            'jabatan'     => $s->jabatan_kelas ?? '',
            'jurusan'     => $s->jurusan,
            'kelas'       => $s->kelas,
            'foto'        => $s->foto_siswa_url,
            'bio'         => $s->bio_siswa ?? '',
            'role_kelas'  => $s->role_kelas,
            'lagu_url'    => $s->lagu_url,
            'lagu_judul'  => $s->lagu_judul ?? '',
            'lagu_artis'  => $s->lagu_artis ?? '',
        ])->values()->toArray();
    @endphp

    <section class="pb-16 sm:pb-20" x-data="kelasGrid(@js($siswaJson))">

        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mb-1 tracking-tight">Anggota Kelas</h2>
                <p class="text-slate-500 text-sm">
                    <span class="font-semibold text-slate-900" x-text="filteredSiswa.length"></span>
                    dari {{ $semuaSiswa->count() }} siswa
                </p>
            </div>

            <!-- SEARCH & FILTER BAR -->
            <div class="max-w-3xl mx-auto mb-8">
                <div class="flex flex-col md:flex-row gap-3">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" x-model="search" placeholder="Cari nama atau NIS..."
                               class="w-full pl-11 pr-10 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-smk-blue/20 focus:border-smk-blue transition">
                        <button type="button" x-show="search" @click="search = ''"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <select x-model="filter"
                            class="px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-smk-blue/20 focus:border-smk-blue transition cursor-pointer">
                        <option value="semua">Semua Siswa</option>
                        <option value="pengurus">Hanya Pengurus</option>
                        <option value="anggota">Hanya Anggota</option>
                    </select>
                </div>

                <div x-show="search || filter !== 'semua'" class="mt-3 flex items-center justify-center gap-2 text-xs text-slate-500">
                    <span>Filter aktif:</span>
                    <template x-if="search">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded-full font-medium ring-1 ring-indigo-700/10">
                            "<span x-text="search"></span>"
                        </span>
                    </template>
                    <template x-if="filter !== 'semua'">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded-full font-medium ring-1 ring-indigo-700/10"
                              x-text="filter === 'pengurus' ? 'Pengurus' : 'Anggota'"></span>
                    </template>
                    <button @click="search = ''; filter = 'semua'"
                            class="text-slate-400 hover:text-slate-700 underline">Reset</button>
                </div>
            </div>

            <!-- GRID SISWA -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 md:gap-4">
                <template x-for="siswa in filteredSiswa" :key="siswa.nis">
                    <button type="button" @click="showModal(siswa)"
                            class="student-card group bg-white rounded-2xl surface-card hover:shadow-xl hover:border-indigo-300 transition-all duration-300 p-3 sm:p-4 text-center border border-slate-100 cursor-pointer active:scale-[.97] focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">

                        <div class="relative inline-block">
                            <img :src="siswa.foto" :alt="siswa.name"
                                 :class="siswa.role_kelas === 'ketua_kelas' ? 'border-amber-300' : 'border-slate-100 group-hover:border-indigo-200'"
                                 class="w-20 h-20 md:w-24 md:h-24 rounded-full object-cover mx-auto bg-slate-100 border-2 transition">

                            <div class="absolute -top-1 -right-1 w-6 h-6 bg-smk-blue text-white rounded-full text-xs font-bold flex items-center justify-center shadow-md"
                                 x-text="siswa.nomor_absen"></div>

                            <template x-if="siswa.jabatan && siswa.role_kelas === 'ketua_kelas'">
                                <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-amber-400 rounded-full flex items-center justify-center shadow-md text-xs">
                                    ⭐
                                </div>
                            </template>

                            <template x-if="siswa.lagu_url">
                                <div class="absolute -top-1 -left-1 w-6 h-6 bg-gradient-to-br from-pink-500 to-violet-500 rounded-full flex items-center justify-center shadow-md">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                                    </svg>
                                </div>
                            </template>
                        </div>

                        <div class="mt-3">
                            <div class="font-semibold text-slate-900 text-xs md:text-sm leading-tight line-clamp-2 min-h-[32px]"
                                 x-text="siswa.name"></div>
                            <template x-if="siswa.jabatan">
                                <div :class="badgeClass(siswa.jabatan)"
                                     class="inline-block mt-1.5 rounded-full px-2 py-0.5 text-[10px] font-semibold"
                                     x-text="siswa.jabatan"></div>
                            </template>
                        </div>
                    </button>
                </template>
            </div>

            <!-- EMPTY STATE -->
            <div x-show="filteredSiswa.length === 0"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="text-center py-16"
                 style="display: none;">
                <div class="w-20 h-20 mx-auto mb-4 bg-slate-100 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">Siswa tidak ditemukan</h3>
                <p class="text-sm text-slate-500 mb-4">Coba kata kunci lain atau reset filter</p>
                <button @click="search = ''; filter = 'semua'"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-smk-blue text-white rounded-xl hover:bg-smk-blue-dark active:scale-95 transition-all text-sm font-semibold">
                    Reset Filter
                </button>
            </div>
        </div>

        <!-- ====== MODAL PROFIL SISWA + MUSIC PLAYER RESPONSIVE ====== -->
        <div x-cloak x-show="selected"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click.self="closeModal()"
             @keydown.escape.window="closeModal()"
             class="fixed inset-0 bg-slate-950/60 backdrop-blur-md z-[60] flex items-center justify-center p-3 sm:p-5 md:p-8 overflow-y-auto"
             style="display: none;">

            <div x-show="selected"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-[.97] translate-y-3"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="relative w-full max-w-2xl my-auto rounded-3xl bg-white shadow-2xl shadow-slate-950/30 overflow-hidden max-h-[94vh] overflow-y-auto modal-scroll">

                <!-- Profile hero -->
                <div class="relative isolate overflow-hidden bg-gradient-to-br from-blue-700 via-blue-700 to-indigo-900 px-5 sm:px-8 pt-6 sm:pt-8 pb-6 sm:pb-7 text-white">
                    <div class="absolute inset-0 -z-10 opacity-20 soft-grid"></div>
                    <div class="absolute -right-12 -top-16 w-56 h-56 rounded-full bg-cyan-300/20 blur-3xl -z-10"></div>
                    <div class="absolute right-24 -bottom-20 w-44 h-44 rounded-full bg-indigo-300/20 blur-3xl -z-10"></div>

                    <button @click="closeModal()" aria-label="Tutup profil"
                            class="absolute top-4 right-4 z-30 w-9 h-9 flex items-center justify-center rounded-full bg-white/15 border border-white/20 hover:bg-white/25 active:scale-90 transition backdrop-blur-md focus:outline-none focus-visible:ring-2 focus-visible:ring-white">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    <div class="flex items-center gap-3 mb-7 pr-10">
                        <div class="w-11 h-11 rounded-2xl bg-white/15 border border-white/20 flex items-center justify-center shrink-0 backdrop-blur-sm">
                            <img src="{{ asset('images/logo-smkn1pedan.png') }}" alt="Logo SMK Negeri 1 Pedan" class="w-8 h-8 object-contain">
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-sm sm:text-base leading-tight">SMK Negeri 1 Pedan</p>
                            <p class="text-blue-100 text-xs sm:text-sm mt-1">Portal Informasi Siswa</p>
                        </div>
                    </div>

                    <div class="relative min-h-[135px] sm:min-h-[155px] flex items-end">
                        <div class="relative z-10 w-[68%] sm:w-[65%] pb-1">
                            <div class="inline-flex items-center gap-1.5 rounded-full bg-white/15 border border-white/20 px-3 py-1 text-[10px] sm:text-xs font-semibold text-blue-50 mb-3 backdrop-blur-sm">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 01.894.553l2.03 4.114 4.54.66a1 1 0 01.554 1.706l-3.285 3.202.776 4.522a1 1 0 01-1.451 1.054L10 15.676l-4.058 2.135a1 1 0 01-1.45-1.054l.775-4.522L1.982 9.033a1 1 0 01.554-1.706l4.54-.66 2.03-4.114A1 1 0 0110 2z"/></svg>
                                Profil Siswa
                            </div>
                            <h3 class="text-xl sm:text-3xl font-extrabold leading-tight tracking-tight break-words" x-text="selected?.name"></h3>
                            <p class="text-blue-100 text-xs sm:text-sm mt-2">Siswa Kelas <span class="font-bold" x-text="selected?.kelas"></span></p>
                            <template x-if="selected?.jabatan">
                                <div class="inline-flex mt-3 px-3 py-1 bg-white/15 border border-white/20 rounded-full text-[10px] sm:text-xs font-semibold text-white" x-text="selected.jabatan"></div>
                            </template>
                        </div>
                        <div class="absolute right-0 bottom-[-1.75rem] sm:bottom-[-2rem] w-[36%] sm:w-[34%] h-[175px] sm:h-[205px] flex items-end justify-center pointer-events-none">
                            <img :src="selected?.foto" :alt="selected?.name"
                                 class="max-h-full max-w-full object-contain object-bottom drop-shadow-[0_12px_18px_rgba(0,0,0,.3)]">
                        </div>
                    </div>
                </div>

                <!-- Student information -->
                <div class="px-4 sm:px-8 py-5 sm:py-7 space-y-4 bg-white">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-base sm:text-lg">Informasi Siswa</h4>
                            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Data diri dan informasi kelas</p>
                        </div>
                        <span class="hidden sm:inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-100 rounded-full px-3 py-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Data siswa
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-2xl p-3 sm:p-4 bg-slate-50 border border-slate-100 hover:border-blue-200 transition-colors min-w-0">
                            <div class="flex items-center gap-2 text-slate-500 text-xs mb-2"><span class="w-7 h-7 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13H4V6a1 1 0 011-1z"/></svg></span>NIS</div>
                            <div class="font-bold text-slate-900 text-sm sm:text-base font-mono break-all" x-text="selected?.nis || '-' "></div>
                        </div>
                        <div class="rounded-2xl p-3 sm:p-4 bg-slate-50 border border-slate-100 hover:border-blue-200 transition-colors min-w-0">
                            <div class="flex items-center gap-2 text-slate-500 text-xs mb-2"><span class="w-7 h-7 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m6-10a4 4 0 100-8 4 4 0 000 8zm10 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg></span>No. Absen</div>
                            <div class="font-bold text-slate-900 text-sm sm:text-base" x-text="selected?.nomor_absen ?? '-' "></div>
                        </div>
                        <div class="rounded-2xl p-3 sm:p-4 bg-slate-50 border border-slate-100 min-w-0">
                            <div class="text-xs text-slate-500 mb-1.5">Kelas</div>
                            <div class="font-bold text-slate-900 text-sm sm:text-base break-words" x-text="selected?.kelas || '-' "></div>
                        </div>
                        <div class="rounded-2xl p-3 sm:p-4 bg-slate-50 border border-slate-100 min-w-0">
                            <div class="text-xs text-slate-500 mb-1.5">Jurusan</div>
                            <div class="font-bold text-slate-900 text-sm sm:text-base break-words" x-text="selected?.jurusan || '-' "></div>
                        </div>
                    </div>

                    <template x-if="selected?.bio">
                        <div class="rounded-2xl p-4 bg-indigo-50/80 border border-indigo-100">
                            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-indigo-700 mb-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8M8 14h5m-8 6l-2 1 1-4a9 9 0 111.5 1.5L5 20z"/></svg> Tentang Saya
                            </div>
                            <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line" x-text="selected.bio"></p>
                        </div>
                    </template>

                    <!-- Music player: selalu di bawah informasi, baik desktop maupun mobile -->
                    <template x-if="selected?.lagu_url && currentLagu">
                        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 text-white border border-slate-800 shadow-lg shadow-slate-900/10">
                            <div class="absolute -right-12 -top-16 w-40 h-40 rounded-full bg-violet-500/20 blur-3xl pointer-events-none"></div>
                            <div class="relative p-4 sm:p-5">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-700 flex items-center justify-center shrink-0 shadow-lg shadow-emerald-950/30">
                                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3a9 9 0 100 18 9 9 0 000-18zm4.2 13.3a.55.55 0 01-.76.18c-2.08-1.27-4.7-1.56-7.79-.85a.55.55 0 11-.25-1.07c3.38-.77 6.28-.43 8.62 1.01.26.16.34.5.18.73zm1.08-2.4a.69.69 0 01-.95.23c-2.38-1.46-6.02-1.88-8.84-1.03a.69.69 0 11-.4-1.32c3.22-.98 7.22-.51 9.96 1.17.32.2.42.62.23.95zm.09-2.5c-2.86-1.7-7.58-1.86-10.31-1.03a.83.83 0 11-.48-1.59c3.14-.95 8.36-.77 11.66 1.19a.83.83 0 01-.87 1.42z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-[10px] sm:text-xs text-emerald-300 font-bold uppercase tracking-[.18em] mb-1">♫ Lagu Favorit</div>
                                        <div class="font-bold text-sm sm:text-base truncate" x-text="currentLagu?.lagu_judul || selected?.lagu_judul || 'Tanpa judul'"></div>
                                        <div class="text-xs sm:text-sm text-slate-400 truncate mt-0.5" x-text="currentLagu?.lagu_artis || selected?.lagu_artis || 'Artis tidak diketahui'"></div>
                                    </div>
                                    <span class="hidden sm:inline-flex text-[10px] text-slate-400 border border-slate-700 rounded-full px-2.5 py-1">MUSIC PLAYER</span>
                                </div>

                                <div class="mt-5">
                                    <div class="h-1.5 rounded-full bg-white/15 cursor-pointer group/bar" @click="seekAudio($event)" role="slider" aria-label="Posisi lagu">
                                        <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-cyan-300 relative" :style="`width: ${progressPercent}%`">
                                            <span class="absolute right-0 top-1/2 -translate-y-1/2 w-3 h-3 rounded-full bg-white shadow"></span>
                                        </div>
                                    </div>
                                    <div class="flex justify-between text-[10px] sm:text-xs text-slate-400 font-mono mt-2">
                                        <span x-text="formatTime(currentTime)">0:00</span>
                                        <span x-text="formatTime(duration)">0:00</span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-center gap-5 sm:gap-7 mt-3">
                                    <button type="button" @click="prevLagu()" aria-label="Lagu sebelumnya" class="w-9 h-9 flex items-center justify-center rounded-full text-slate-300 hover:text-white hover:bg-white/10 transition active:scale-90">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 6h2v12H6zm3.5 6l8.5 6V6z"/></svg>
                                    </button>
                                    <button type="button" @click="togglePlay()" :aria-label="isPlaying ? 'Jeda lagu' : 'Putar lagu'" class="w-12 h-12 rounded-full bg-white text-slate-950 flex items-center justify-center shadow-lg hover:scale-105 active:scale-95 transition-transform">
                                        <template x-if="!isPlaying"><svg class="w-6 h-6 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></template>
                                        <template x-if="isPlaying"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M6 4h4v16H6zm8 0h4v16h-4z"/></svg></template>
                                    </button>
                                    <button type="button" @click="nextLagu()" aria-label="Lagu berikutnya" class="w-9 h-9 flex items-center justify-center rounded-full text-slate-300 hover:text-white hover:bg-white/10 transition active:scale-90">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M16 6h2v12h-2zM6 18l8.5-6L6 6v12z"/></svg>
                                    </button>
                                    <button type="button" @click="stopLagu()" aria-label="Hentikan lagu" class="w-9 h-9 flex items-center justify-center rounded-full text-slate-400 hover:text-rose-300 hover:bg-white/10 transition active:scale-90">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div class="flex flex-col-reverse sm:flex-row gap-2 sm:gap-3 pt-1">
                        <button @click="closeModal()" class="w-full sm:w-auto sm:flex-1 py-3 px-5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 active:scale-[.99] transition-all font-bold text-sm">Tutup</button>
                        <a href="{{ route('login') }}" class="w-full sm:flex-1 inline-flex items-center justify-center gap-2 py-3 px-5 bg-gradient-to-r from-blue-700 to-indigo-700 text-white rounded-xl hover:from-blue-800 hover:to-indigo-800 active:scale-[.99] transition-all font-bold text-sm shadow-lg shadow-blue-700/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3zm-7 9a7 7 0 0114 0H5z"/></svg>
                            Ke halaman absensi
                        </a>
                    </div>
                </div>
                <audio x-ref="audioPlayer" @timeupdate="updateProgress()" @loadedmetadata="onLoadedMetadata()" @ended="onAudioEnded()" preload="metadata"></audio>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-slate-900 text-slate-400 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="flex items-center justify-center gap-3 mb-3">
                <img src="{{ asset('images/logo-smkn1pedan.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                <span class="font-bold text-white">SMK Negeri 1 Pedan</span>
            </div>
            <p class="text-xs">Portal Informasi Kelas & Sistem Absensi Digital</p>
            <p class="text-xs mt-3">© {{ date('Y') }} SMK Negeri 1 Pedan. All rights reserved.</p>
        </div>
    </footer>

    <!-- ====== ALPINE COMPONENT ====== -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('kelasGrid', (siswaData) => ({
                semuaSiswa: siswaData,
                search: '',
                filter: 'semua',
                selected: null,

                currentLagu: null,
                isPlaying: false,
                progressPercent: 0,
                currentTime: 0,
                duration: 0,

                get filteredSiswa() {
                    const keyword = this.search.toLowerCase().trim();
                    return this.semuaSiswa.filter(siswa => {
                        const cocokSearch = !keyword
                            || siswa.name.toLowerCase().includes(keyword)
                            || String(siswa.nis).toLowerCase().includes(keyword);
                        const cocokFilter =
                            this.filter === 'semua' ||
                            (this.filter === 'pengurus' && siswa.jabatan) ||
                            (this.filter === 'anggota' && !siswa.jabatan);
                        return cocokSearch && cocokFilter;
                    });
                },

                badgeClass(jabatan) {
                    if (!jabatan) return '';
                    const j = jabatan.toLowerCase();
                    if (j.includes('ketua') && !j.includes('wakil')) {
                        return 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-700/10';
                    }
                    if (j.includes('wakil')) {
                        return 'bg-violet-50 text-violet-700 ring-1 ring-violet-700/10';
                    }
                    return 'bg-slate-100 text-slate-600 ring-1 ring-slate-400/10';
                },

                showModal(siswa) {
                    this.selected = siswa;
                    if (siswa.lagu_url) {
                        this.loadLagu(siswa);
                    } else if (this.currentLagu) {
                        this.stopLagu();
                    }
                },

                closeModal() {
                    this.selected = null;
                    const audio = this.$refs.audioPlayer;
                    if (audio && !audio.paused) {
                        audio.pause();
                        this.isPlaying = false;
                    }
                },

                loadLagu(siswa) {
                    if (!siswa.lagu_url) return;
                    if (this.currentLagu?.nis === siswa.nis) return;

                    this.currentLagu = siswa;
                    const audio = this.$refs.audioPlayer;
                    if (!audio) return;
                    audio.pause();
                    audio.src = siswa.lagu_url;
                    audio.load();
                    audio.currentTime = 0;

                    this.isPlaying = false;
                    this.progressPercent = 0;
                    this.currentTime = 0;
                    this.duration = 0;
                },

                togglePlay() {
                    const audio = this.$refs.audioPlayer;
                    if (!audio || !this.currentLagu) return;

                    if (this.isPlaying) {
                        audio.pause();
                        this.isPlaying = false;
                    } else {
                        audio.play().then(() => {
                            this.isPlaying = true;
                        }).catch(err => console.log(err));
                    }
                },

                stopLagu() {
                    const audio = this.$refs.audioPlayer;
                    if (audio) {
                        audio.pause();
                        audio.currentTime = 0;
                    }
                    this.currentLagu = null;
                    this.isPlaying = false;
                    this.progressPercent = 0;
                    this.currentTime = 0;
                    this.duration = 0;
                },

                nextLagu() {
                    const denganLagu = this.semuaSiswa.filter(s => s.lagu_url);
                    if (denganLagu.length === 0) return;
                    const currentIdx = denganLagu.findIndex(s => s.nis === this.currentLagu?.nis);
                    const nextIdx = (currentIdx + 1) % denganLagu.length;
                    this.currentLagu = null;
                    this.loadLagu(denganLagu[nextIdx]);
                    setTimeout(() => this.togglePlay(), 100);
                },

                prevLagu() {
                    const denganLagu = this.semuaSiswa.filter(s => s.lagu_url);
                    if (denganLagu.length === 0) return;
                    const currentIdx = denganLagu.findIndex(s => s.nis === this.currentLagu?.nis);
                    const prevIdx = currentIdx <= 0 ? denganLagu.length - 1 : currentIdx - 1;
                    this.currentLagu = null;
                    this.loadLagu(denganLagu[prevIdx]);
                    setTimeout(() => this.togglePlay(), 100);
                },

                updateProgress() {
                    const audio = this.$refs.audioPlayer;
                    if (audio && audio.duration) {
                        this.currentTime = audio.currentTime;
                        this.progressPercent = (audio.currentTime / audio.duration) * 100;
                    }
                },

                onLoadedMetadata() {
                    const audio = this.$refs.audioPlayer;
                    if (audio && audio.duration) {
                        this.duration = audio.duration;
                    }
                },

                onAudioEnded() {
                    this.isPlaying = false;
                    this.progressPercent = 0;
                    this.currentTime = 0;
                    setTimeout(() => this.nextLagu(), 500);
                },

                seekAudio(event) {
                    const audio = this.$refs.audioPlayer;
                    if (!audio || !audio.duration) return;
                    const rect = event.currentTarget.getBoundingClientRect();
                    const clickX = event.clientX - rect.left;
                    const percent = clickX / rect.width;
                    audio.currentTime = percent * audio.duration;
                },

                formatTime(seconds) {
                    if (!seconds || isNaN(seconds)) return '0:00';
                    const mins = Math.floor(seconds / 60);
                    const secs = Math.floor(seconds % 60);
                    return `${mins}:${secs.toString().padStart(2, '0')}`;
                }
            }));
        });
    </script>

</body>
</html>