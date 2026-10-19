@extends('layouts.app')

@section('title', 'Absensi')

@section('content')
@php
    $ket      = $today->keterangan ?? null;
    $masuk    = $today?->waktu_masuk;
    $pulang   = $today?->waktu_pulang;
    $locked   = $today && in_array($ket, ['izin', 'sakit', 'cuti']);

    // Mode halaman: locked | masuk | pulang | selesai
    $mode = match (true) {
        $locked  => 'locked',
        !$masuk  => 'masuk',
        !$pulang => 'pulang',
        default  => 'selesai',
    };
    $canCapture = in_array($mode, ['masuk', 'pulang']);
    $jamMasuk   = substr((string) $setting->jam_masuk, 0, 5);

    $bannerStyle = match ($ket) {
        'izin'  => ['wrap' => 'bg-amber-50 border-amber-200 text-amber-900 dark:bg-amber-900/20 dark:border-amber-700/60 dark:text-amber-200',  'icon' => 'bg-amber-100 dark:bg-amber-800/40',  'emoji' => '📝'],
        'sakit' => ['wrap' => 'bg-rose-50 border-rose-200 text-rose-900 dark:bg-rose-900/20 dark:border-rose-700/60 dark:text-rose-200',        'icon' => 'bg-rose-100 dark:bg-rose-800/40',    'emoji' => '🤒'],
        'cuti'  => ['wrap' => 'bg-violet-50 border-violet-200 text-violet-900 dark:bg-violet-900/20 dark:border-violet-700/60 dark:text-violet-200', 'icon' => 'bg-violet-100 dark:bg-violet-800/40', 'emoji' => '🏖️'],
        default => ['wrap' => 'bg-blue-50 border-blue-200 text-blue-900 dark:bg-blue-900/20 dark:border-blue-700/60 dark:text-blue-200',        'icon' => 'bg-blue-100 dark:bg-blue-800/40',    'emoji' => 'ℹ️'],
    };

    $tiles = [
        ['label' => 'Masuk',  'time' => $masuk?->format('H:i'),  'extra' => ($today && $today->jarak_meter) ? 'Jarak ' . $today->jarak_meter . ' m' : null],
        ['label' => 'Pulang', 'time' => $pulang?->format('H:i'), 'extra' => null],
    ];
@endphp

<div class="min-h-screen bg-slate-50 dark:bg-slate-900">
    @include('partials.navbar')

    <main class="mx-auto max-w-5xl px-4 py-6 pb-28 sm:px-6 md:py-8 md:pb-10">

        {{-- ============ HEADER ============ --}}
        <header class="mb-6 flex items-end justify-between gap-4 animate-stagger" style="animation-delay: 0ms;">
            <div class="min-w-0">
                <p class="mb-1 text-xs font-semibold uppercase tracking-widest text-slate-400">{{ now()->translatedFormat('l, d F Y') }}</p>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white md:text-3xl">Absensi Hari Ini</h1>
            </div>
            <div class="shrink-0 text-right">
                <div id="clock" class="font-mono text-2xl font-bold tabular-nums text-indigo-600 dark:text-indigo-400 md:text-3xl" aria-label="Jam sekarang">--:--:--</div>
                <div class="text-[11px] font-medium text-slate-400">Jam masuk {{ $jamMasuk }} WIB</div>
            </div>
        </header>

        {{-- ============ BANNER IZIN / SAKIT / CUTI ============ --}}
        @if($locked)
        <section class="mb-6 rounded-2xl border p-4 shadow-sm animate-stagger sm:p-5 {{ $bannerStyle['wrap'] }}" style="animation-delay: 50ms;" role="status">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl text-2xl sm:h-14 sm:w-14 sm:text-3xl {{ $bannerStyle['icon'] }}" aria-hidden="true">{{ $bannerStyle['emoji'] }}</div>
                <div class="min-w-0 flex-1">
                    <h2 class="mb-0.5 text-base font-bold sm:text-lg">Anda sedang {{ ucfirst($ket) }} hari ini</h2>
                    <p class="text-sm opacity-90">Absensi GPS dikunci. Anda tidak perlu absen hari ini.</p>
                </div>
            </div>
        </section>
        @endif

        <div class="grid gap-4 lg:grid-cols-5">

            {{-- ============ STATUS MASUK / PULANG ============ --}}
            <section class="grid grid-cols-2 gap-3 animate-stagger lg:col-span-2 lg:col-start-4" style="animation-delay: 100ms;" aria-label="Status absensi">
                @foreach($tiles as $t)
                @php $done = (bool) $t['time']; @endphp
                <article class="rounded-2xl border border-slate-100 border-l-4 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 {{ $done ? 'border-l-emerald-500' : 'border-l-slate-300 dark:border-l-slate-600' }}">
                    <div class="mb-2 flex items-center justify-between gap-2">
                        <h3 class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ $t['label'] }}</h3>
                        @if($done)
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-700 ring-1 ring-emerald-600/20 dark:bg-emerald-500/20 dark:text-emerald-400">
                            <svg class="h-2.5 w-2.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Sudah
                        </span>
                        @else
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-500 dark:bg-slate-700 dark:text-slate-400">Belum</span>
                        @endif
                    </div>
                    <div class="text-3xl font-extrabold tabular-nums {{ $done ? 'text-slate-900 dark:text-white' : 'text-slate-300 dark:text-slate-600' }}">{{ $t['time'] ?? '--:--' }}</div>
                    @if($t['extra'])
                    <p class="mt-1 text-[11px] font-medium text-slate-500 dark:text-slate-400">{{ $t['extra'] }}</p>
                    @endif
                </article>
                @endforeach
            </section>

            {{-- ============ KAMERA ============ --}}
            @if($canCapture)
            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm animate-stagger dark:border-slate-700 dark:bg-slate-800/80 sm:p-5 lg:col-span-3 lg:col-start-1 lg:row-span-3 lg:row-start-1"
                     style="animation-delay: 150ms;" aria-label="Kamera selfie">
                <div class="mb-4 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 ring-1 ring-indigo-700/10 dark:bg-indigo-500/20" aria-hidden="true">
                        <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-900 dark:text-white">Ambil Selfie</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Posisikan wajah di dalam bingkai</p>
                    </div>
                </div>

                <div class="relative mx-auto aspect-[3/4] max-h-[70vh] w-full overflow-hidden rounded-2xl bg-slate-900 ring-1 ring-slate-300 dark:ring-slate-700 sm:aspect-[4/3]">
                    <video id="video" autoplay playsinline muted class="h-full w-full -scale-x-100 object-cover"></video>
                    <img id="preview" alt="Hasil foto selfie" class="absolute inset-0 hidden h-full w-full -scale-x-100 object-cover">
                    <canvas id="canvas" class="hidden"></canvas>

                    {{-- Bingkai wajah --}}
                    <svg id="face-guide" class="pointer-events-none absolute inset-0 h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                        <ellipse cx="50" cy="46" rx="25" ry="32" fill="none" stroke="rgba(255,255,255,.7)" stroke-width=".5" stroke-dasharray="2 2" vector-effect="non-scaling-stroke"/>
                    </svg>

                    {{-- Status pill --}}
                    <div class="absolute left-3 top-3 flex items-center gap-2 rounded-full bg-black/60 px-3 py-1.5 text-xs text-white ring-1 ring-white/20 backdrop-blur-sm" role="status" aria-live="polite">
                        <span id="cam-dot" class="h-2 w-2 rounded-full bg-amber-400"></span>
                        <span id="cam-status">Menyalakan kamera...</span>
                    </div>

                    {{-- Efek blitz --}}
                    <div id="flash" class="pointer-events-none absolute inset-0 bg-white opacity-0 transition-opacity duration-300"></div>

                    {{-- Error kamera --}}
                    <div id="cam-error" class="absolute inset-0 hidden flex-col items-center justify-center gap-3 bg-slate-900/95 p-6 text-center">
                        <div class="text-4xl" aria-hidden="true">📷</div>
                        <p id="cam-error-msg" class="max-w-xs text-sm text-slate-300">Kamera tidak dapat diakses.</p>
                        <button type="button" id="btn-start-cam" class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400">
                            Coba Lagi
                        </button>
                    </div>

                    {{-- Kontrol bawah --}}
                    <div class="absolute inset-x-0 bottom-4 flex items-center justify-center">
                        <button type="button" id="btn-shutter" disabled aria-label="Ambil foto"
                                class="flex h-16 w-16 items-center justify-center rounded-full border-4 border-white bg-white/20 backdrop-blur-sm transition active:scale-90 disabled:cursor-not-allowed disabled:opacity-40 focus:outline-none focus-visible:ring-4 focus-visible:ring-indigo-400">
                            <span class="h-11 w-11 rounded-full bg-white"></span>
                        </button>
                        <button type="button" id="btn-retake"
                                class="hidden items-center gap-2 rounded-full bg-white/90 px-4 py-2.5 text-sm font-semibold text-slate-800 shadow-lg transition hover:bg-white active:scale-95 focus:outline-none focus-visible:ring-4 focus-visible:ring-indigo-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Foto Ulang
                        </button>
                    </div>
                </div>
            </section>
            @endif

            {{-- ============ PANEL AKSI ============ --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm animate-stagger dark:border-slate-700 dark:bg-slate-800/80 sm:p-5 lg:col-span-2 lg:col-start-4" style="animation-delay: 200ms;" aria-label="Kirim absensi">
                @if($mode === 'selesai')
                <div class="py-4 text-center">
                    <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-3xl dark:bg-emerald-500/20" aria-hidden="true">✅</div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Absensi hari ini selesai</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Terima kasih! Sampai jumpa besok.</p>
                </div>
                @elseif($mode === 'locked')
                <div class="py-4 text-center">
                    <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-2xl dark:bg-slate-700" aria-hidden="true">🔒</div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Absensi dikunci</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Status Anda hari ini: {{ ucfirst($ket) }}.</p>
                </div>
                @else
                <div class="mb-4 flex items-center justify-between gap-2">
                    <h2 class="font-bold text-slate-900 dark:text-white">Persiapan {{ $mode === 'masuk' ? 'Absen Masuk' : 'Absen Pulang' }}</h2>
                    <button type="button" id="btn-loc" class="rounded-lg px-2 py-1 text-xs font-bold text-indigo-600 transition hover:bg-indigo-50 active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 dark:text-indigo-400 dark:hover:bg-indigo-500/10">
                        🔄 Deteksi Ulang
                    </button>
                </div>

                <ul class="mb-4 space-y-2.5" id="checklist">
                    <li data-check="photo" class="flex items-start gap-3">
                        <span class="check-icon mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold">1</span>
                        <div class="min-w-0"><div class="text-sm font-semibold text-slate-800 dark:text-slate-200">Foto selfie</div><div class="check-text text-xs text-slate-500 dark:text-slate-400">Belum diambil</div></div>
                    </li>
                    <li data-check="location" class="flex items-start gap-3">
                        <span class="check-icon mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold">2</span>
                        <div class="min-w-0"><div class="text-sm font-semibold text-slate-800 dark:text-slate-200">Lokasi GPS</div><div class="check-text text-xs text-slate-500 dark:text-slate-400">Mendeteksi...</div></div>
                    </li>
                    <li data-check="radius" class="flex items-start gap-3">
                        <span class="check-icon mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold">3</span>
                        <div class="min-w-0"><div class="text-sm font-semibold text-slate-800 dark:text-slate-200">Radius sekolah</div><div class="check-text text-xs text-slate-500 dark:text-slate-400">Maks. {{ $setting->radius_meters }} meter</div></div>
                    </li>
                </ul>

                @if($mode === 'masuk')
                <p id="late-note" class="mb-3 hidden rounded-xl bg-amber-50 px-3 py-2 text-xs font-medium text-amber-800 ring-1 ring-amber-600/20 dark:bg-amber-900/20 dark:text-amber-300">
                    ⏰ Sudah melewati jam masuk ({{ $jamMasuk }}). Absensi kemungkinan tercatat terlambat.
                </p>
                @endif

                <button type="button" id="btn-submit" data-tipe="{{ $mode }}" disabled
                        class="flex w-full items-center justify-center gap-2 rounded-xl py-4 font-bold text-white shadow-lg transition active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-50 disabled:shadow-none focus:outline-none focus-visible:ring-4
                               {{ $mode === 'masuk' ? 'bg-emerald-600 shadow-emerald-500/30 hover:bg-emerald-700 focus-visible:ring-emerald-300' : 'bg-orange-600 shadow-orange-500/30 hover:bg-orange-700 focus-visible:ring-orange-300' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        @if($mode === 'masuk')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        @endif
                    </svg>
                    <span id="btn-submit-label">{{ $mode === 'masuk' ? 'Absen Masuk' : 'Absen Pulang' }}</span>
                </button>
                <p id="submit-hint" class="mt-2 text-center text-xs text-slate-400">Lengkapi langkah di atas untuk mengaktifkan tombol.</p>
                @endif
            </section>

            {{-- ============ INFO SEKOLAH ============ --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm animate-stagger dark:border-slate-700 dark:bg-slate-800/80 sm:p-5 lg:col-span-2 lg:col-start-4" style="animation-delay: 250ms;" aria-label="Lokasi sekolah">
                <div class="mb-3 flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 ring-1 ring-indigo-600/10 dark:bg-indigo-500/20" aria-hidden="true">
                        <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h2 class="font-bold text-slate-900 dark:text-white">Lokasi Sekolah</h2>
                </div>

                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-900/50">
                        <div class="mb-1 text-slate-500 dark:text-slate-400">Radius maksimal</div>
                        <div class="font-bold text-slate-900 dark:text-white">{{ $setting->radius_meters }} meter</div>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-900/50">
                        <div class="mb-1 text-slate-500 dark:text-slate-400">Jam masuk</div>
                        <div class="font-bold text-slate-900 dark:text-white">{{ $jamMasuk }} WIB</div>
                    </div>
                </div>

                <details class="group mt-3 text-xs">
                    <summary class="flex cursor-pointer list-none items-center gap-1 font-semibold text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400">
                        <svg class="h-3.5 w-3.5 transition-transform group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        Lihat koordinat
                    </summary>
                    <div class="mt-2 grid grid-cols-2 gap-3">
                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-900/50">
                            <div class="mb-1 text-slate-500 dark:text-slate-400">Latitude</div>
                            <div class="break-all font-mono font-bold text-slate-900 dark:text-white">{{ $setting->latitude }}</div>
                        </div>
                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-900/50">
                            <div class="mb-1 text-slate-500 dark:text-slate-400">Longitude</div>
                            <div class="break-all font-mono font-bold text-slate-900 dark:text-white">{{ $setting->longitude }}</div>
                        </div>
                    </div>
                </details>
            </section>
        </div>
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

    /* Status checklist */
    .check-icon { background: rgb(226 232 240); color: rgb(100 116 139); transition: all .25s; }
    [data-state="ok"]    .check-icon { background: rgb(16 185 129); color: #fff; }
    [data-state="warn"]  .check-icon { background: rgb(245 158 11); color: #fff; }
    [data-state="error"] .check-icon { background: rgb(239 68 68);  color: #fff; }
    [data-state="loading"] .check-icon { background: rgb(199 210 254); color: rgb(79 70 229); animation: pulseSoft 1.2s ease-in-out infinite; }
    [data-state="ok"]    .check-text { color: rgb(5 150 105); }
    [data-state="warn"]  .check-text { color: rgb(217 119 6); }
    [data-state="error"] .check-text { color: rgb(220 38 38); }
    .dark .check-icon { background: rgb(51 65 85); color: rgb(148 163 184); }
    .dark [data-state="ok"]    .check-icon { background: rgb(16 185 129); color: #fff; }
    .dark [data-state="warn"]  .check-icon { background: rgb(245 158 11); color: #fff; }
    .dark [data-state="error"] .check-icon { background: rgb(239 68 68);  color: #fff; }
    .dark [data-state="ok"]    .check-text { color: rgb(52 211 153); }
    .dark [data-state="warn"]  .check-text { color: rgb(251 191 36); }
    .dark [data-state="error"] .check-text { color: rgb(248 113 113); }
    @keyframes pulseSoft { 50% { opacity: .5; } }

    details > summary::-webkit-details-marker { display: none; }

    @media (prefers-reduced-motion: reduce) {
        .animate-stagger { animation: none; opacity: 1; }
        .check-icon { animation: none !important; }
    }
</style>
@endpush

@push('scripts')
<script>
(() => {
    // ============== KONFIGURASI ==============
    const CSRF   = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const URL_STORE = @json(route('attendance.store'));
       const SCHOOL = {
        lat: parseFloat("{{ $setting->latitude }}"),
        lng: parseFloat("{{ $setting->longitude }}"),
        radius: parseInt("{{ $setting->radius_meters }}"),
        jamMasuk: "{{ $jamMasuk }}",
    };
    const MODE = @json($mode);

    // ============== HELPER ==============
    const $ = (id) => document.getElementById(id);
    const notify = (msg, type = 'success') =>
        typeof window.showToast === 'function' ? window.showToast(msg, type) : alert(msg);

    // Jam digital
    const clockEl = $('clock');
    const tickClock = () => {
        const d = new Date();
        const p = (n) => String(n).padStart(2, '0');
        clockEl.textContent = `${p(d.getHours())}:${p(d.getMinutes())}:${p(d.getSeconds())}`;
        const late = $('late-note');
        if (late && MODE === 'masuk') {
            late.classList.toggle('hidden', `${p(d.getHours())}:${p(d.getMinutes())}` <= SCHOOL.jamMasuk);
        }
    };
    tickClock();
    setInterval(tickClock, 1000);

    // Halaman tanpa kamera (selesai / izin) berhenti di sini
    if (MODE !== 'masuk' && MODE !== 'pulang') return;

    // ============== STATE ==============
    const state = { photo: null, lat: null, lng: null, accuracy: null, distance: null };
    let stream = null;

    const video   = $('video');
    const canvas  = $('canvas');
    const preview = $('preview');
    const shutter = $('btn-shutter');
    const retake  = $('btn-retake');
    const submit  = $('btn-submit');
    const guide   = $('face-guide');

    // Checklist: state = pending | loading | ok | warn | error
    function setCheck(name, st, text) {
        const li = document.querySelector(`[data-check="${name}"]`);
        if (!li) return;
        li.dataset.state = st;
        li.querySelector('.check-text').textContent = text;
        li.querySelector('.check-icon').textContent =
            st === 'ok' ? '✓' : st === 'error' ? '!' : st === 'warn' ? '!' :
            ({ photo: '1', location: '2', radius: '3' })[name];
    }

    function refreshSubmit() {
        const ready = state.photo && state.lat !== null;
        submit.disabled = !ready;
        $('submit-hint').textContent = ready
            ? 'Semua siap. Tekan tombol untuk mengirim.'
            : 'Lengkapi langkah di atas untuk mengaktifkan tombol.';
    }

    // ============== KAMERA ==============
    function setCamStatus(text, color) {
        $('cam-status').textContent = text;
        $('cam-dot').className = 'h-2 w-2 rounded-full ' + color;
    }

    function showCamError(msg) {
        $('cam-error-msg').textContent = msg;
        $('cam-error').classList.remove('hidden');
        $('cam-error').classList.add('flex');
        setCamStatus('Kamera tidak aktif', 'bg-red-500');
        shutter.disabled = true;
    }

    async function startCamera() {
        $('cam-error').classList.add('hidden');
        $('cam-error').classList.remove('flex');
        setCamStatus('Menyalakan kamera...', 'bg-amber-400');

        if (!navigator.mediaDevices?.getUserMedia) {
            showCamError('Browser tidak mendukung kamera, atau halaman tidak dibuka lewat HTTPS.');
            return;
        }
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user', width: { ideal: 960 }, height: { ideal: 1280 } },
                audio: false,
            });
            video.srcObject = stream;
            video.classList.remove('hidden');
            preview.classList.add('hidden');
            guide.classList.remove('hidden');
            shutter.classList.remove('hidden');
            retake.classList.add('hidden'); retake.classList.remove('flex');
            setCamStatus('Kamera aktif', 'bg-emerald-400');
        } catch (err) {
            const msg = err.name === 'NotAllowedError'
                ? 'Izin kamera ditolak. Aktifkan izin kamera di pengaturan browser, lalu coba lagi.'
                : err.name === 'NotFoundError'
                ? 'Kamera tidak ditemukan pada perangkat ini.'
                : 'Tidak bisa mengakses kamera: ' + err.message;
            showCamError(msg);
        }
    }

    function stopCamera() {
        stream?.getTracks().forEach((t) => t.stop());
        stream = null;
        video.srcObject = null;
    }

    video.addEventListener('loadedmetadata', () => { shutter.disabled = false; });

    shutter.addEventListener('click', () => {
        if (!video.videoWidth) return;
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);

        state.photo = canvas.toDataURL('image/jpeg', 0.8);
        preview.src = state.photo;

        const flash = $('flash');
        flash.style.opacity = '.9';
        setTimeout(() => (flash.style.opacity = '0'), 120);
        if (navigator.vibrate) navigator.vibrate(30);

        stopCamera();
        video.classList.add('hidden');
        preview.classList.remove('hidden');
        guide.classList.add('hidden');
        shutter.classList.add('hidden');
        retake.classList.remove('hidden'); retake.classList.add('flex');

        setCamStatus('Foto siap dikirim', 'bg-emerald-400');
        setCheck('photo', 'ok', 'Foto sudah diambil');
        refreshSubmit();
    });

    retake.addEventListener('click', () => {
        state.photo = null;
        setCheck('photo', 'pending', 'Belum diambil');
        refreshSubmit();
        startCamera();
    });

    $('btn-start-cam').addEventListener('click', startCamera);

    // ============== LOKASI ==============
    const toRad = (d) => (d * Math.PI) / 180;
    function haversine(lat1, lng1, lat2, lng2) {
        const R = 6371000;
        const dLat = toRad(lat2 - lat1), dLng = toRad(lng2 - lng1);
        const a = Math.sin(dLat / 2) ** 2 +
                  Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.sin(dLng / 2) ** 2;
        return 2 * R * Math.asin(Math.sqrt(a));
    }
    const fmtDist = (m) => (m >= 1000 ? (m / 1000).toFixed(1) + ' km' : Math.round(m) + ' meter');

    function getLocation() {
        state.lat = state.lng = null;
        setCheck('location', 'loading', 'Mendeteksi lokasi...');
        setCheck('radius', 'pending', `Maks. ${SCHOOL.radius} meter`);
        refreshSubmit();

        if (!navigator.geolocation) {
            setCheck('location', 'error', 'Browser tidak mendukung GPS');
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                state.lat = pos.coords.latitude;
                state.lng = pos.coords.longitude;
                state.accuracy = pos.coords.accuracy;
                state.distance = haversine(state.lat, state.lng, SCHOOL.lat, SCHOOL.lng);

                const acc = Math.round(state.accuracy);
                setCheck('location', acc > 100 ? 'warn' : 'ok',
                    acc > 100 ? `Akurasi rendah (±${acc} m). Coba di tempat terbuka.` : `Terdeteksi (akurasi ±${acc} m)`);

                const inside = state.distance <= SCHOOL.radius;
                setCheck('radius', inside ? 'ok' : 'error',
                    inside ? `Dalam jangkauan (${fmtDist(state.distance)} dari sekolah)`
                           : `Di luar radius (${fmtDist(state.distance)} dari sekolah). Absen kemungkinan ditolak.`);
                refreshSubmit();
            },
            (err) => {
                const msg = err.code === 1
                    ? 'Izin lokasi ditolak. Aktifkan di pengaturan browser.'
                    : err.code === 3 ? 'Waktu habis. Tekan "Deteksi Ulang".'
                    : 'Gagal mendeteksi lokasi.';
                setCheck('location', 'error', msg);
                refreshSubmit();
            },
            { enableHighAccuracy: true, timeout: 12000, maximumAge: 0 }
        );
    }
    $('btn-loc').addEventListener('click', getLocation);

    // ============== KIRIM ABSEN ==============
    const spinner = '<svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

    submit.addEventListener('click', async () => {
        if (!state.photo) return notify('Silakan ambil foto selfie dulu.', 'error');
        if (state.lat === null) return notify('Lokasi belum terdeteksi. Tekan "Deteksi Ulang".', 'error');

        const original = submit.innerHTML;
        submit.disabled = true;
        submit.innerHTML = spinner + '<span>Mengirim...</span>';

        try {
            const res = await fetch(URL_STORE, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    tipe: submit.dataset.tipe,
                    latitude: state.lat,
                    longitude: state.lng,
                    foto: state.photo,
                }),
            });
            const data = await res.json().catch(() => ({}));

            if (res.ok && data.success) {
                if (typeof confetti === 'function') {
                    confetti({ particleCount: 150, spread: 70, origin: { y: 0.6 }, colors: ['#4f46e5', '#10b981', '#f59e0b'] });
                }
                if (typeof playSound === 'function') playSound('success');
                notify(data.message || 'Absensi berhasil', 'success');
                submit.innerHTML = '<span>✓ Berhasil</span>';
                setTimeout(() => location.reload(), 2000);
            } else {
                notify(data.message || 'Gagal absen', 'error');
                submit.innerHTML = original;
                refreshSubmit();
            }
        } catch (e) {
            notify('Koneksi bermasalah: ' + e.message, 'error');
            submit.innerHTML = original;
            refreshSubmit();
        }
    });

    // ============== INIT ==============
    window.addEventListener('pagehide', stopCamera);
    setCheck('photo', 'pending', 'Belum diambil');
    startCamera();   // otomatis menyalakan kamera saat halaman dibuka
    getLocation();   // otomatis mendeteksi lokasi
})();
</script>
@endpush