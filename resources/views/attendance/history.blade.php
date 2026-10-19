@extends('layouts.app')

@section('title', 'Riwayat Absensi')

@section('content')
<div class="min-h-screen bg-slate-50">
    @include('partials.navbar')

    <div class="max-w-7xl mx-auto px-4 py-8">

        <!-- ============ HEADER ============ -->
        @include('partials.hero', [
    'title' => 'Riwayat Absensi',
    'subtitle' => 'Catatan kehadiran Anda',
    'showUser' => true,
])

              <!-- ============ STATISTIK ============ -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4 mb-6">
            <!-- Total Hadir -->
            <div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-5 overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-blue-100 to-blue-50 rounded-full -mr-12 -mt-12 opacity-60 group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 bg-blue-50 ring-1 ring-blue-600/10 rounded-xl flex items-center justify-center group-hover:bg-blue-600 transition-colors duration-300">
                            <svg class="w-4 h-4 text-blue-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Hadir</div>
                    <div class="text-3xl font-extrabold text-slate-900">{{ $totalHadir }}</div>
                    <div class="text-xs text-slate-400 mt-1">hari</div>
                </div>
            </div>

            <!-- Terlambat -->
            <div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-5 overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-orange-100 to-orange-50 rounded-full -mr-12 -mt-12 opacity-60 group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 bg-orange-50 ring-1 ring-orange-600/10 rounded-xl flex items-center justify-center group-hover:bg-orange-500 transition-colors duration-300">
                            <svg class="w-4 h-4 text-orange-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Terlambat</div>
                    <div class="text-3xl font-extrabold text-slate-900">{{ $totalTerlambat }}</div>
                    <div class="text-xs text-slate-400 mt-1">hari</div>
                </div>
            </div>

            <!-- Tepat Waktu -->
            <div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-5 overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-full -mr-12 -mt-12 opacity-60 group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 bg-emerald-50 ring-1 ring-emerald-600/10 rounded-xl flex items-center justify-center group-hover:bg-emerald-600 transition-colors duration-300">
                            <svg class="w-4 h-4 text-emerald-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tepat Waktu</div>
                    <div class="text-3xl font-extrabold text-slate-900">{{ $totalHadir - $totalTerlambat }}</div>
                    <div class="text-xs text-slate-400 mt-1">hari</div>
                </div>
            </div>

            <!-- Izin -->
            <div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-5 overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-amber-100 to-amber-50 rounded-full -mr-12 -mt-12 opacity-60 group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 bg-amber-50 ring-1 ring-amber-600/10 rounded-xl flex items-center justify-center group-hover:bg-amber-500 transition-colors duration-300">
                            <svg class="w-4 h-4 text-amber-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Izin</div>
                    <div class="text-3xl font-extrabold text-slate-900">{{ $totalIzin }}</div>
                    <div class="text-xs text-slate-400 mt-1">hari</div>
                </div>
            </div>

            <!-- Sakit -->
            <div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-5 overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-red-100 to-red-50 rounded-full -mr-12 -mt-12 opacity-60 group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 bg-red-50 ring-1 ring-red-600/10 rounded-xl flex items-center justify-center group-hover:bg-red-500 transition-colors duration-300">
                            <svg class="w-4 h-4 text-red-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Sakit</div>
                    <div class="text-3xl font-extrabold text-slate-900">{{ $totalSakit }}</div>
                    <div class="text-xs text-slate-400 mt-1">hari</div>
                </div>
            </div>

            <!-- Cuti -->
            <div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-5 overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-violet-100 to-violet-50 rounded-full -mr-12 -mt-12 opacity-60 group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 bg-violet-50 ring-1 ring-violet-600/10 rounded-xl flex items-center justify-center group-hover:bg-violet-600 transition-colors duration-300">
                            <svg class="w-4 h-4 text-violet-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Cuti</div>
                    <div class="text-3xl font-extrabold text-slate-900">{{ $totalCuti }}</div>
                    <div class="text-xs text-slate-400 mt-1">hari</div>
                </div>
            </div>
        </div>

        <!-- ============ FILTER ============ -->
        <div class="bg-white rounded-2xl shadow-sm p-4 mb-4">
            <form method="GET" class="flex flex-col md:flex-row gap-3">
                <select name="bulan" class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>

                <select name="tahun" class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    <option value="">Semua Tahun</option>
                    @foreach(range(date('Y'), date('Y') - 3) as $y)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>

                <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 active:scale-95 font-semibold text-sm transition-all duration-150 shadow-lg shadow-indigo-500/20 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter
                </button>

                @if(request()->hasAny(['bulan', 'tahun']))
                    <a href="{{ route('attendance.history') }}"
                       class="px-6 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 active:scale-95 font-semibold text-sm transition-all duration-150 text-center flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- ============ TABEL RIWAYAT ============ -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden ring-1 ring-slate-100">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                            <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Keterangan</th>
                            <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Masuk</th>
                            <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Pulang</th>
                            <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Jarak</th>
                            <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Foto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($attendances as $absen)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3.5">
                                    <div class="font-semibold text-slate-900">
                                        {{ $absen->tanggal->translatedFormat('d M Y') }}
                                    </div>
                                    <div class="text-xs text-slate-400 mt-0.5">
                                        {{ $absen->tanggal->translatedFormat('l') }}
                                    </div>
                                </td>
                                <td class="px-4 py-3.5">
                                    @php
                                        $ket = $absen->keterangan ?? 'hadir';
                                        $ketClass = match($ket) {
                                            'izin'  => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                            'sakit' => 'bg-red-50 text-red-700 ring-red-600/20',
                                            'cuti'  => 'bg-violet-50 text-violet-700 ring-violet-600/20',
                                            default => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full ring-1 {{ $ketClass }} capitalize">
                                        {{ $ket }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5">
                                    @if($absen->waktu_masuk)
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-mono font-semibold text-slate-900">{{ $absen->waktu_masuk->format('H:i:s') }}</span>
                                            @if($absen->status_masuk === 'valid')
                                                <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5">
                                    @if($absen->waktu_pulang)
                                        <span class="font-mono font-semibold text-slate-900">{{ $absen->waktu_pulang->format('H:i:s') }}</span>
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5">
                                    @if($absen->jarak_meter !== null)
                                        <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-600">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            </svg>
                                            {{ $absen->jarak_meter }} m
                                        </span>
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex gap-2">
                                        @if($absen->foto_masuk)
                                            <a href="{{ Storage::url($absen->foto_masuk) }}" target="_blank"
                                               class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                Masuk
                                            </a>
                                        @endif
                                        @if($absen->foto_pulang)
                                            <a href="{{ Storage::url($absen->foto_pulang) }}" target="_blank"
                                               class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                Pulang
                                            </a>
                                        @endif
                                        @if(!$absen->foto_masuk && !$absen->foto_pulang)
                                            <span class="text-slate-300 text-xs">—</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-16 text-center">
                                    <div class="w-16 h-16 mx-auto mb-3 bg-slate-100 rounded-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-900 mb-1">Belum ada riwayat absensi</p>
                                    <p class="text-xs text-slate-500 mb-4">Mulai absen untuk melihat catatan kehadiran Anda</p>
                                    <a href="{{ route('attendance.index') }}"
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 active:scale-95 font-semibold text-sm transition-all duration-150 shadow-lg shadow-indigo-500/20">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        Absen Sekarang
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($attendances->hasPages())
                <div class="px-4 py-3 border-t border-slate-100 bg-slate-50">
                    {{ $attendances->withQueryString()->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection