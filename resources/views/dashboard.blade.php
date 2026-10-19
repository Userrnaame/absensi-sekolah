@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $isAdmin = $user->role === 'admin';
    $persen  = max(0, min(100, (int) ($persentaseKehadiran ?? 0)));

    $roleColor = match ($user->role) {
        'admin' => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-900/30 dark:text-red-400',
        'guru'  => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-900/30 dark:text-emerald-400',
        default => 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-900/30 dark:text-blue-400',
    };

    $motivasi = match (true) {
        $persen >= 90 => ['🎉', 'Luar biasa! Kehadiran Anda sangat baik.'],
        $persen >= 75 => ['👍', 'Bagus! Pertahankan kehadiran Anda.'],
        $persen >= 50 => ['⚠️', 'Tingkatkan kehadiran Anda ya.'],
        default       => ['💪', 'Ayo lebih rajin masuk sekolah!'],
    };

    $ket = $todayAttendance->keterangan ?? null;
    $showBanner = $todayAttendance && in_array($ket, ['izin', 'sakit', 'cuti']);
    $bannerStyle = match ($ket) {
        'izin'  => ['wrap' => 'bg-amber-50 border-amber-200 text-amber-900 dark:bg-amber-900/20 dark:border-amber-700/60 dark:text-amber-200',  'icon' => 'bg-amber-100 dark:bg-amber-800/40',  'emoji' => '📝'],
        'sakit' => ['wrap' => 'bg-rose-50 border-rose-200 text-rose-900 dark:bg-rose-900/20 dark:border-rose-700/60 dark:text-rose-200',        'icon' => 'bg-rose-100 dark:bg-rose-800/40',    'emoji' => '🤒'],
        'cuti'  => ['wrap' => 'bg-violet-50 border-violet-200 text-violet-900 dark:bg-violet-900/20 dark:border-violet-700/60 dark:text-violet-200', 'icon' => 'bg-violet-100 dark:bg-violet-800/40', 'emoji' => '🏖️'],
        default => ['wrap' => 'bg-blue-50 border-blue-200 text-blue-900 dark:bg-blue-900/20 dark:border-blue-700/60 dark:text-blue-200',        'icon' => 'bg-blue-100 dark:bg-blue-800/40',    'emoji' => 'ℹ️'],
    };

    // Kartu statistik (class ditulis penuh agar aman dari purge Tailwind)
    $statCards = [
        ['label' => 'Hadir', 'value' => $stats['hadir'] ?? 0, 'note' => 'Total bulan ini',
         'accent' => 'border-l-emerald-500', 'iconBg' => 'bg-emerald-50 dark:bg-emerald-500/10', 'iconText' => 'text-emerald-600 dark:text-emerald-400',
         'path' => 'M5 13l4 4L19 7'],
        ['label' => 'Izin', 'value' => $stats['izin'] ?? 0, 'note' => 'Izin disetujui',
         'accent' => 'border-l-amber-500', 'iconBg' => 'bg-amber-50 dark:bg-amber-500/10', 'iconText' => 'text-amber-600 dark:text-amber-400',
         'path' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ['label' => 'Sakit', 'value' => $stats['sakit'] ?? 0, 'note' => 'Surat sakit',
         'accent' => 'border-l-rose-500', 'iconBg' => 'bg-rose-50 dark:bg-rose-500/10', 'iconText' => 'text-rose-600 dark:text-rose-400',
         'path' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
        // CATATAN: sebelumnya memakai $stats['cuti'] untuk "Alpha". Sesuaikan key ini dengan controller Anda.
        ['label' => 'Alpha', 'value' => $stats['alpha'] ?? $stats['cuti'] ?? 0, 'note' => 'Tanpa keterangan',
         'accent' => 'border-l-slate-400', 'iconBg' => 'bg-slate-100 dark:bg-slate-700/50', 'iconText' => 'text-slate-600 dark:text-slate-400',
         'path' => 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636'],
    ];

    $dayColors = [
        'emerald' => ['bg-emerald-50 dark:bg-emerald-900/20', 'ring-emerald-600/20', 'text-emerald-600 dark:text-emerald-400'],
        'amber'   => ['bg-amber-50 dark:bg-amber-900/20',     'ring-amber-600/20',   'text-amber-600 dark:text-amber-400'],
        'red'     => ['bg-red-50 dark:bg-red-900/20',         'ring-red-600/20',     'text-red-600 dark:text-red-400'],
        'violet'  => ['bg-violet-50 dark:bg-violet-900/20',   'ring-violet-600/20',  'text-violet-600 dark:text-violet-400'],
        'blue'    => ['bg-blue-50 dark:bg-blue-900/20',       'ring-blue-600/20',    'text-blue-600 dark:text-blue-400'],
        'slate'   => ['bg-slate-50 dark:bg-slate-900/50',     'ring-slate-300/60 dark:ring-slate-700', 'text-slate-400 dark:text-slate-500'],
    ];

    $legend = [
        ['Hadir', 'bg-emerald-400'], ['Izin', 'bg-amber-400'], ['Sakit', 'bg-red-400'],
        ['Alpha', 'bg-slate-400'],   ['Hari ini', 'bg-blue-400'], ['Libur', 'bg-slate-300 dark:bg-slate-600'],
    ];

    $badgeColors = [
        'amber'   => 'bg-amber-50 text-amber-800 border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-700/50',
        'emerald' => 'bg-emerald-50 text-emerald-800 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-700/50',
        'pink'    => 'bg-pink-50 text-pink-800 border-pink-200 dark:bg-pink-900/30 dark:text-pink-300 dark:border-pink-700/50',
        'yellow'  => 'bg-yellow-50 text-yellow-800 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-700/50',
    ];
    $badgeDefault = 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700';

    $podium = [
        ['idx' => 1, 'rank' => 2, 'ring' => 'border-slate-300',  'chip' => 'bg-slate-300 text-slate-900', 'bar' => 'h-16 md:h-20 border-slate-300/60',  'img' => 'w-12 h-12 md:w-16 md:h-16', 'name' => 'text-[11px] md:text-xs', 'num' => 'text-emerald-300'],
        ['idx' => 0, 'rank' => 1, 'ring' => 'border-yellow-400', 'chip' => 'bg-yellow-400 text-yellow-900', 'bar' => 'h-20 md:h-24 border-yellow-400 bg-gradient-to-t from-yellow-400/20 to-white/10', 'img' => 'w-16 h-16 md:w-20 md:h-20', 'name' => 'text-xs md:text-sm font-extrabold', 'num' => 'text-yellow-300', 'crown' => true],
        ['idx' => 2, 'rank' => 3, 'ring' => 'border-amber-600',  'chip' => 'bg-amber-600 text-white',     'bar' => 'h-12 md:h-16 border-amber-600/60',  'img' => 'w-12 h-12 md:w-16 md:h-16', 'name' => 'text-[11px] md:text-xs', 'num' => 'text-emerald-300'],
    ];
    $hasPodium = !empty($top3Kelas) && count($top3Kelas) > 0;
@endphp

<div class="min-h-screen bg-slate-50 dark:bg-slate-900">
    @include('partials.navbar')

    <main class="mx-auto max-w-7xl px-4 py-6 pb-28 md:py-8 md:pb-10 sm:px-6 lg:px-8"
          x-data="dashboard({{ $persen }})">

        {{-- ============ HEADER ============ --}}
        <header class="mb-6 animate-stagger" style="animation-delay: 0ms;">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div class="min-w-0">
                    <p class="mb-1 text-xs font-semibold uppercase tracking-widest text-slate-400">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </p>
                    <h1 class="truncate text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white md:text-3xl">
                        Halo, {{ $user->name }} <span aria-hidden="true">👋</span>
                    </h1>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Selamat datang kembali di Sistem Absensi Digital
                    </p>
                </div>
                <span class="inline-flex w-fit items-center rounded-full px-3 py-1.5 text-xs font-bold uppercase tracking-wider ring-1 {{ $roleColor }}">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
        </header>

        {{-- ============ BANNER IZIN / SAKIT / CUTI ============ --}}
        @if($showBanner)
        <section class="mb-6 rounded-2xl border p-4 shadow-sm animate-stagger sm:p-5 {{ $bannerStyle['wrap'] }}"
                 style="animation-delay: 50ms;" role="status">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl text-2xl sm:h-14 sm:w-14 sm:text-3xl {{ $bannerStyle['icon'] }}" aria-hidden="true">
                    {{ $bannerStyle['emoji'] }}
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="mb-0.5 text-base font-bold sm:text-lg">Anda sedang {{ ucfirst($ket) }} hari ini</h3>
                    <p class="text-sm opacity-90">Tombol absensi GPS dikunci. Anda tidak perlu absen hari ini.</p>
                </div>
            </div>
        </section>
        @endif

        {{-- ============ SISWA / GURU ============ --}}
        @unless($isAdmin)

        <section class="mb-6 grid gap-4 lg:grid-cols-5" aria-label="Ringkasan kehadiran">

            {{-- Hero: circular progress --}}
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 via-blue-600 to-indigo-700 p-5 text-white shadow-xl shadow-indigo-500/20 animate-stagger sm:p-6 lg:col-span-2"
                 style="animation-delay: 100ms;">
                <div class="pointer-events-none absolute -right-20 -top-20 h-48 w-48 rounded-full bg-white/10 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-24 -left-12 h-56 w-56 rounded-full bg-violet-400/20 blur-3xl"></div>

                <div class="relative flex h-full flex-col">
                    <div class="mb-2 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20 ring-1 ring-white/30 backdrop-blur-sm">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-blue-100">Kehadiran Bulan Ini</span>
                    </div>

                    <div class="my-4 flex flex-1 items-center justify-center">
                        <div class="relative h-40 w-40 md:h-44 md:w-44"
                             role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $persen }}"
                             aria-label="Persentase kehadiran bulan ini">
                            <svg class="h-full w-full -rotate-90 transform" viewBox="0 0 100 100" aria-hidden="true">
                                <circle cx="50" cy="50" r="42" fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="7"/>
                                <circle cx="50" cy="50" r="42" fill="none" stroke="url(#progressGradient)" stroke-width="7" stroke-linecap="round"
                                        :stroke-dasharray="circumference" :stroke-dashoffset="strokeOffset"/>
                                <defs>
                                    <linearGradient id="progressGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#34d399"/>
                                        <stop offset="100%" stop-color="#10b981"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <div class="flex items-end leading-none">
                                    <span class="text-4xl font-extrabold tracking-tight tabular-nums md:text-5xl" x-text="displayPercent">{{ $persen }}</span>
                                    <span class="mb-1 text-xl font-bold text-blue-100 md:text-2xl">%</span>
                                </div>
                                <span class="mt-1 text-[10px] font-semibold uppercase tracking-wider text-blue-200">Kehadiran</span>
                            </div>
                        </div>
                    </div>

                    <p class="mb-4 text-center text-sm text-blue-100">
                        <span class="text-lg font-bold text-white">{{ $stats['hadir'] ?? 0 }}</span>
                        <span class="opacity-70">dari</span>
                        <span class="text-lg font-bold text-white">{{ $hariSekolahBulanIni ?? 0 }}</span>
                        <span class="opacity-70">hari sekolah</span>
                    </p>

                    <div class="border-t border-white/20 pt-4">
                        <p class="text-center text-xs italic text-blue-100">
                            <span aria-hidden="true">{{ $motivasi[0] }}</span> {{ $motivasi[1] }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Timeline 7 hari --}}
            <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm animate-stagger dark:border-slate-700 dark:bg-slate-800 sm:p-6 lg:col-span-3"
                 style="animation-delay: 200ms;">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">7 Hari Terakhir</h2>
                        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Tren kehadiran harian Anda</p>
                    </div>
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 ring-1 ring-indigo-700/10 dark:bg-indigo-900/30 dark:ring-indigo-400/20" aria-hidden="true">
                        <svg class="h-4 w-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>

                <ol class="grid grid-cols-7 gap-1.5 sm:gap-3">
                    @foreach($timeline7Hari as $day)
                    @php
                        [$bg, $ring, $text] = $dayColors[$day['color']] ?? $dayColors['slate'];
                        $isToday = $day['tanggal']->isToday();
                    @endphp
                    <li class="group text-center" title="{{ $day['tanggal']->translatedFormat('l, d F Y') }} — {{ $day['label'] }}">
                        <div class="mb-1.5 text-[10px] font-semibold uppercase text-slate-400">{{ $day['hari'] }}</div>
                        <div class="flex aspect-square items-center justify-center rounded-xl ring-1 transition duration-200 group-hover:-translate-y-0.5 group-hover:shadow-md {{ $bg }} {{ $isToday ? 'ring-2 ring-indigo-500' : $ring }}">
                            <span class="text-sm font-bold leading-none sm:text-base {{ $text }}">{{ $day['icon'] }}</span>
                        </div>
                        <div class="mt-1.5 text-[10px] font-semibold {{ $isToday ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}">
                            {{ $day['tanggal_num'] }}
                        </div>
                        <span class="sr-only">{{ $day['label'] }}</span>
                    </li>
                    @endforeach
                </ol>

                <ul class="mt-5 grid grid-cols-3 gap-x-2 gap-y-2 border-t border-slate-100 pt-4 text-[11px] dark:border-slate-700 sm:grid-cols-6">
                    @foreach($legend as [$label, $dot])
                    <li class="flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full {{ $dot }}"></span>
                        <span class="text-slate-600 dark:text-slate-400">{{ $label }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </section>

        {{-- Kartu statistik --}}
        <section class="mb-6 grid grid-cols-2 gap-3 animate-stagger md:grid-cols-4 md:gap-4" style="animation-delay: 300ms;" aria-label="Statistik kehadiran">
            @foreach($statCards as $card)
            <article class="group rounded-2xl border border-slate-100 border-l-4 bg-white p-4 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg dark:border-slate-700 dark:bg-slate-800/80 sm:p-5 {{ $card['accent'] }}">
                <div class="mb-3 flex items-start justify-between gap-2">
                    <h3 class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 sm:text-xs">{{ $card['label'] }}</h3>
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl transition-transform duration-300 group-hover:scale-110 sm:h-10 sm:w-10 {{ $card['iconBg'] }}" aria-hidden="true">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 {{ $card['iconText'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $card['path'] }}"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold tabular-nums text-slate-900 dark:text-white sm:text-4xl">{{ $card['value'] }}</div>
                <div class="mt-1 text-xs font-medium text-slate-500 dark:text-slate-400">{{ $card['note'] }}</div>
            </article>
            @endforeach
        </section>

        {{-- Badges --}}
        @if(!empty($badges) && count($badges) > 0)
        <section class="mb-6 animate-stagger" style="animation-delay: 320ms;" aria-label="Lencana">
            <ul class="flex flex-wrap gap-2">
                @foreach($badges as $badge)
                <li class="inline-flex cursor-default items-center gap-1.5 rounded-full border px-3 py-1.5 shadow-sm transition-transform hover:scale-105 {{ $badgeColors[$badge['color']] ?? $badgeDefault }}"
                    title="{{ $badge['desc'] }}">
                    <span class="text-sm" aria-hidden="true">{{ $badge['icon'] }}</span>
                    <span class="text-xs font-bold">{{ $badge['name'] }}</span>
                </li>
                @endforeach
            </ul>
        </section>
        @endif

        {{-- Papan kehormatan --}}
        <section class="mb-6 animate-stagger" style="animation-delay: 350ms;" aria-label="Papan kehormatan kelas">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 to-purple-700 shadow-lg">
                <div class="pattern-dots pointer-events-none absolute inset-0 opacity-20"></div>

                <div class="relative p-5 md:p-6">
                    <div class="mb-6 flex flex-col justify-between gap-3 md:flex-row md:items-center">
                        <div>
                            <h2 class="flex items-center gap-2 text-lg font-extrabold tracking-tight text-white md:text-xl">
                                <span aria-hidden="true">🏆</span> Papan Kehormatan Kelas
                            </h2>
                            <p class="mt-0.5 text-xs text-indigo-200">Top 3 siswa paling rajin di {{ $user->kelas }}</p>
                        </div>

                        <div class="flex w-fit items-center gap-4 rounded-xl border border-white/20 bg-white/10 px-4 py-2 backdrop-blur-md">
                            <div>
                                <div class="text-[9px] font-semibold uppercase tracking-wider text-indigo-200">Posisi</div>
                                <div class="mt-0.5 text-lg font-black leading-none text-white">#{{ $peringkatKelas ?? '-' }}</div>
                            </div>
                            <div class="h-6 w-px bg-white/20"></div>
                            <div>
                                <div class="text-[9px] font-semibold uppercase tracking-wider text-indigo-200">Total</div>
                                <div class="mt-0.5 text-xs font-bold leading-none text-white">{{ $totalSiswaKelas ?? 0 }} Siswa</div>
                            </div>
                        </div>
                    </div>

                    @if($hasPodium)
                    <ol class="flex items-end justify-center gap-3 pt-6 md:gap-6">
                        @foreach($podium as $p)
                            @if(isset($top3Kelas[$p['idx']]))
                            @php $s = $top3Kelas[$p['idx']]; @endphp
                            <li class="group relative flex w-24 flex-col items-center md:w-32">
                                @if(!empty($p['crown']))
                                <span class="absolute -top-7 text-2xl md:text-3xl motion-safe:animate-bounce" aria-hidden="true">👑</span>
                                @endif
                                <div class="relative mb-3">
                                    <img src="{{ $s->foto_siswa_url }}" alt="Foto {{ $s->name }}" loading="lazy"
                                         class="rounded-full border-[3px] object-cover shadow-md transition-transform group-hover:scale-110 {{ $p['img'] }} {{ $p['ring'] }}">
                                    <span class="absolute -bottom-2 left-1/2 -translate-x-1/2 rounded-full px-2 py-0.5 text-[10px] font-black shadow-sm {{ $p['chip'] }}">#{{ $p['rank'] }}</span>
                                </div>
                                <div class="flex w-full flex-col justify-end rounded-t-lg border-t-2 bg-white/10 p-2 text-center backdrop-blur-sm {{ $p['bar'] }}">
                                    <div class="w-full truncate text-white {{ $p['name'] }} font-bold">{{ $s->name }}</div>
                                    <div class="mt-0.5 text-xs font-black {{ $p['num'] }}">
                                        {{ $s->total_hadir }} <span class="text-[9px] font-normal text-indigo-200">Hari</span>
                                    </div>
                                </div>
                            </li>
                            @endif
                        @endforeach
                    </ol>
                    @else
                    <p class="py-6 text-center text-sm text-indigo-200">Belum ada data peringkat bulan ini.</p>
                    @endif
                </div>
            </div>
        </section>

        @endunless

        {{-- ============ ADMIN ============ --}}
        @if($isAdmin && !empty($adminStats))

        <section class="mb-8 animate-stagger" style="animation-delay: 100ms;">
            <div class="mb-4">
                <h2 class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">Statistik Sekolah</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Data pengguna &amp; pengajuan izin</p>
            </div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:gap-4 lg:grid-cols-3">
                <article class="flex items-center justify-between rounded-2xl border border-slate-100 border-l-4 border-l-indigo-500 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg dark:border-slate-700 dark:bg-slate-800">
                    <div>
                        <div class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Siswa</div>
                        <div class="text-3xl font-extrabold tabular-nums text-slate-900 dark:text-white">{{ $adminStats['total_siswa'] }}</div>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-2xl ring-1 ring-indigo-700/10 dark:bg-indigo-900/30" aria-hidden="true">👥</div>
                </article>

                <article class="flex items-center justify-between rounded-2xl border border-slate-100 border-l-4 border-l-emerald-500 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg dark:border-slate-700 dark:bg-slate-800">
                    <div>
                        <div class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Guru</div>
                        <div class="text-3xl font-extrabold tabular-nums text-slate-900 dark:text-white">{{ $adminStats['total_guru'] }}</div>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-2xl ring-1 ring-emerald-600/10 dark:bg-emerald-900/30" aria-hidden="true">👨‍🏫</div>
                </article>

                @php $pending = $adminStats['izin_pending'] > 0; @endphp
                <a href="{{ route('admin.izin.index') }}"
                   class="flex items-center justify-between rounded-2xl border border-slate-100 border-l-4 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-500 dark:border-slate-700 dark:bg-slate-800 sm:col-span-2 lg:col-span-1 {{ $pending ? 'border-l-amber-500' : 'border-l-slate-300' }}">
                    <div>
                        <div class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Izin Pending</div>
                        <div class="text-3xl font-extrabold tabular-nums {{ $pending ? 'text-amber-600' : 'text-slate-900 dark:text-white' }}">{{ $adminStats['izin_pending'] }}</div>
                        @if($pending)
                        <div class="mt-1 text-xs font-semibold text-amber-600">Perlu di-approve →</div>
                        @endif
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-2xl ring-1 ring-amber-600/10 dark:bg-amber-900/30" aria-hidden="true">⏳</div>
                </a>
            </div>
        </section>

        <section class="mb-8 animate-stagger" style="animation-delay: 200ms;">
            <div class="mb-4">
                <h2 class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">Absensi Hari Ini</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Ringkasan kehadiran siswa hari ini</p>
            </div>

            @php
                $belumAbsen = max(0, $adminStats['total_siswa'] - $adminStats['hadir_hari_ini'] - $adminStats['izin_hari_ini']);
                $absensiCards = [
                    ['Hadir',       $adminStats['hadir_hari_ini'], 'border-l-emerald-500'],
                    ['Izin/Sakit',  $adminStats['izin_hari_ini'],  'border-l-amber-500'],
                    ['Belum Absen', $belumAbsen,                   'border-l-slate-400'],
                ];
            @endphp
            <div class="grid grid-cols-3 gap-3 md:gap-4">
                @foreach($absensiCards as [$label, $value, $accent])
                <article class="rounded-2xl border border-slate-100 border-l-4 bg-white p-4 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg dark:border-slate-700 dark:bg-slate-800 sm:p-5 {{ $accent }}">
                    <div class="mb-2 text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 sm:text-xs">{{ $label }}</div>
                    <div class="text-2xl font-extrabold tabular-nums text-slate-900 dark:text-white sm:text-3xl">{{ $value }}</div>
                </article>
                @endforeach
            </div>
        </section>

        <section class="mb-6 animate-stagger" style="animation-delay: 300ms;">
            <div class="mb-4">
                <h2 class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">Menu Admin</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Pengaturan sistem &amp; manajemen data</p>
            </div>

            <div class="grid grid-cols-1 gap-3 md:grid-cols-2 md:gap-4">
                <a href="{{ route('admin.users.index') }}"
                   class="group flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-indigo-300 hover:shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 active:scale-[0.98] dark:border-slate-700 dark:bg-slate-800 dark:hover:border-indigo-600 sm:p-5">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 ring-1 ring-indigo-700/10 transition-colors duration-300 group-hover:bg-indigo-600 dark:bg-indigo-900/30" aria-hidden="true">
                        <svg class="h-7 w-7 text-indigo-600 transition-colors duration-300 group-hover:text-white dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="font-bold text-slate-900 transition-colors group-hover:text-indigo-700 dark:text-white dark:group-hover:text-indigo-400">Manajemen User</div>
                        <div class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Kelola akun siswa, guru &amp; admin</div>
                    </div>
                    <svg class="h-5 w-5 shrink-0 text-slate-300 transition-all group-hover:translate-x-1 group-hover:text-indigo-600 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

                <a href="{{ route('admin.settings.index') }}"
                   class="group flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-300 hover:shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 active:scale-[0.98] dark:border-slate-700 dark:bg-slate-800 dark:hover:border-emerald-600 sm:p-5">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 ring-1 ring-emerald-600/10 transition-colors duration-300 group-hover:bg-emerald-600 dark:bg-emerald-900/30" aria-hidden="true">
                        <svg class="h-7 w-7 text-emerald-600 transition-colors duration-300 group-hover:text-white dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="font-bold text-slate-900 transition-colors group-hover:text-emerald-700 dark:text-white dark:group-hover:text-emerald-400">Setting Lokasi</div>
                        <div class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Atur koordinat &amp; radius absensi</div>
                    </div>
                    <svg class="h-5 w-5 shrink-0 text-slate-300 transition-all group-hover:translate-x-1 group-hover:text-emerald-600 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </section>

        @endif
    </main>
</div>
@endsection

@push('styles')
<style>
    @keyframes staggerFadeIn {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .animate-stagger {
        opacity: 0;
        animation: staggerFadeIn .6s cubic-bezier(.4, 0, .2, 1) forwards;
    }
    .pattern-dots {
        background-image: radial-gradient(rgba(255,255,255,.55) 1px, transparent 1px);
        background-size: 18px 18px;
    }
    circle[stroke="url(#progressGradient)"] {
        transition: stroke-dashoffset 1.5s cubic-bezier(.4, 0, .2, 1);
    }
    @media (prefers-reduced-motion: reduce) {
        .animate-stagger { animation: none; opacity: 1; }
        circle[stroke="url(#progressGradient)"] { transition: none; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dashboard', (targetPercent = 0) => ({
            displayPercent: 0,
            targetPercent: Math.max(0, Math.min(100, Number(targetPercent) || 0)),
            circumference: 2 * Math.PI * 42, // ≈ 263.89

            get strokeOffset() {
                return this.circumference * (1 - this.displayPercent / 100);
            },

            // Alpine memanggil init() otomatis — jangan tambahkan x-init="init()" lagi.
            init() {
                if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    this.displayPercent = this.targetPercent;
                    return;
                }

                setTimeout(() => {
                    const duration = 1500;
                    const start = performance.now();

                    const tick = (now) => {
                        const t = Math.min((now - start) / duration, 1);
                        const eased = 1 - Math.pow(1 - t, 3); // ease-out cubic
                        this.displayPercent = Math.round(eased * this.targetPercent);
                        if (t < 1) requestAnimationFrame(tick);
                    };
                    requestAnimationFrame(tick);
                }, 400);
            },
        }));
    });
</script>
@endpush