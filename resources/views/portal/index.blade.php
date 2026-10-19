<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Sekolah — SMK Negeri 1 Pedan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                    },
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .hero-gradient { background: linear-gradient(135deg, #2d3fa0 0%, #1e2d7a 100%); }
        
        /* Smooth Scroll & Offset untuk Sticky Navbar */
        html {
            scroll-behavior: smooth;
            scroll-padding-top: 85px;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">

    <!-- NAVBAR -->
    <nav class="bg-white/90 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-slate-100" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            
            <!-- Logo -->
            <a href="#beranda" class="flex items-center gap-3">
                <img src="{{ asset('images/logo-smkn1pedan.png') }}" alt="Logo" class="w-10 h-10 md:w-12 md:h-12 object-contain">
                <div>
                    <div class="font-bold text-smk-blue text-base md:text-lg leading-tight">SMK Negeri 1 Pedan</div>
                    <div class="text-xs text-slate-500 hidden md:block">Portal Informasi Kelas</div>
                </div>
            </a>

            <!-- Menu Desktop (Smooth Scroll) -->
            <div class="hidden md:flex items-center gap-8">
                <a href="#beranda" class="text-sm font-semibold text-slate-600 hover:text-smk-blue transition">Beranda</a>
                <a href="#daftar-kelas" class="text-sm font-semibold text-slate-600 hover:text-smk-blue transition">Daftar Kelas</a>
            </div>

            <!-- Kanan: Tombol Absen & Hamburger -->
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 md:px-5 md:py-2.5 bg-smk-blue text-white rounded-xl hover:bg-smk-blue-dark active:scale-95 transition-all duration-150 font-semibold text-sm shadow-lg shadow-smk-blue/25">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Absen
                </a>
                
                <!-- Tombol Hamburger Mobile -->
                <button @click="open = !open" class="md:hidden w-10 h-10 flex items-center justify-center rounded-xl hover:bg-slate-100 active:scale-95 transition text-slate-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Menu Mobile Dropdown -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden absolute top-full left-0 w-full bg-white border-b border-slate-100 shadow-lg py-3 px-4 flex flex-col gap-2"
             style="display: none;">
            <a href="#beranda" @click="open = false" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 rounded-lg">Beranda</a>
            <a href="#daftar-kelas" @click="open = false" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 rounded-lg">Daftar Kelas</a>
        </div>
    </nav>

    <!-- HERO -->
    <section id="beranda" class="hero-gradient text-white py-16 md:py-24 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="w-full h-full">
                <polygon fill="white" points="0,100 100,0 100,100"/>
            </svg>
        </div>
        <div class="max-w-7xl mx-auto px-4 relative">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <img src="{{ asset('images/logo-smkn1pedan.png') }}" alt="Logo SMKN 1 Pedan" class="w-32 md:w-40 drop-shadow-2xl">
                <div class="text-center md:text-left">
                    <h1 class="text-3xl md:text-5xl font-extrabold mb-3 tracking-tight">Selamat Datang di</h1>
                    <h2 class="text-2xl md:text-4xl font-bold mb-4 text-blue-100">SMK Negeri 1 Pedan</h2>
                    <p class="text-blue-100 max-w-2xl text-sm md:text-base">
                        Portal informasi kelas & struktur kepengurusan siswa.
                        Tahun Ajaran {{ $tahunAjaran }} — Angkatan {{ $angkatan }}.
                    </p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- VISI & MISI -->
    <section class="py-12 md:py-16">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-bold text-slate-900 text-center mb-2 tracking-tight">Visi & Misi</h2>
            <p class="text-center text-slate-500 mb-10 text-sm">Komitmen kami untuk pendidikan yang berkualitas</p>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Visi -->
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border-l-4 border-smk-blue">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center ring-1 ring-indigo-700/10">
                            <svg class="w-5 h-5 text-smk-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg text-slate-900">Visi</h3>
                    </div>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Mewujudkan tamatan yang <strong class="text-slate-900">berkarakter kuat</strong>, <strong class="text-slate-900">cerdas</strong>, <strong class="text-slate-900">kompeten</strong>,
                        <strong class="text-slate-900">peduli lingkungan</strong>, dan <strong class="text-slate-900">berdaya saing global</strong>.
                    </p>
                </div>

                <!-- Misi -->
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border-l-4 border-smk-blue-light">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center ring-1 ring-indigo-700/10">
                            <svg class="w-5 h-5 text-smk-blue-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg text-slate-900">Misi</h3>
                    </div>
                    <ul class="text-slate-600 text-sm space-y-2">
                        <li class="flex gap-2"><span class="text-smk-blue">•</span> Penanaman karakter (religius, jujur, mandiri, disiplin)</li>
                        <li class="flex gap-2"><span class="text-smk-blue">•</span> Peningkatan kompetensi relevan dunia kerja</li>
                        <li class="flex gap-2"><span class="text-smk-blue">•</span> Efektivitas pembelajaran</li>
                        <li class="flex gap-2"><span class="text-smk-blue">•</span> Pelestarian lingkungan</li>
                        <li class="flex gap-2"><span class="text-smk-blue">•</span> Penguasaan bahasa asing untuk daya saing global</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- PROGRAM KEAHLIAN -->
    <section class="py-12 bg-white border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-bold text-slate-900 text-center mb-2 tracking-tight">Program Keahlian</h2>
            <p class="text-center text-slate-500 mb-10 text-sm">6 jurusan unggulan SMK Negeri 1 Pedan</p>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                @php
                    $jurusanList = [
                        ['AKL',  'Akuntansi Keuangan Lembaga'],
                        ['MPLB', 'Manajemen Perkantoran & Layanan Bisnis'],
                        ['PM',   'Pemasaran'],
                        ['PPLG', 'Pengembangan Perangkat Lunak & Gim'],
                        ['TJKT', 'Teknik Komputer Jaringan & Telekomunikasi'],
                        ['BCF',  'Broadcasting & Perfilman'],
                    ];
                @endphp

                @foreach($jurusanList as [$kode, $nama])
                    <div class="bg-gradient-to-br from-indigo-50 to-white border border-indigo-100 rounded-xl p-4 text-center hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                        <div class="font-extrabold text-smk-blue text-lg mb-1">{{ $kode }}</div>
                        <div class="text-xs text-slate-600 leading-tight">{{ $nama }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- DAFTAR KELAS -->
    <section class="py-12 md:py-16" id="daftar-kelas">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mb-2 tracking-tight">Daftar Kelas</h2>
                <p class="text-slate-500 text-sm">
                    Angkatan {{ $angkatan }} — Tahun Ajaran {{ $tahunAjaran }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($daftarKelas as $kelas)
                    <a href="{{ route('portal.kelas', $kelas['kode']) }}"
                       class="group bg-white rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden border border-slate-100 hover:border-indigo-300 cursor-pointer">

                        <!-- Header kartu -->
                        <div class="hero-gradient text-white p-4 text-center">
                            <div class="text-xl font-bold tracking-tight">{{ $kelas['nama'] }}</div>
                            <div class="text-xs opacity-90 mt-1">{{ $kelas['jurusan'] }}</div>
                        </div>

                        <!-- Body kartu -->
                        <div class="p-4">
                            @if($kelas['wali_kelas'])
                                <div class="flex items-center gap-2 mb-3 pb-3 border-b border-slate-100">
                                    <img src="{{ $kelas['wali_kelas']->foto_siswa_url }}"
                                         alt="{{ $kelas['wali_kelas']->name }}"
                                         class="w-10 h-10 rounded-full object-cover bg-slate-100">
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs text-slate-400">Wali Kelas</div>
                                        <div class="text-sm font-semibold text-slate-900 truncate">{{ $kelas['wali_kelas']->name }}</div>
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center gap-1.5 text-slate-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="font-semibold text-slate-900">{{ $kelas['jumlah_siswa'] }}</span> siswa
                                </div>
                                <div class="text-smk-blue font-semibold text-xs flex items-center gap-1 group-hover:gap-2 transition-all">
                                    Lihat
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
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

</body>
</html>