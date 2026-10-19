@extends('layouts.app')

@section('title', 'Kelola Pengajuan Izin')

@section('content')
<div class="min-h-screen bg-slate-50">
    @include('partials.navbar')

    <div class="max-w-7xl mx-auto px-4 py-8">

        <!-- HEADER -->
        <div class="mb-6">
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-2">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-50 text-red-700 rounded-full font-semibold ring-1 ring-red-600/10">
                    ADMIN
                </span>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Kelola Pengajuan Izin</h1>
            <p class="text-sm text-slate-500 mt-1">Setujui atau tolak pengajuan izin siswa & guru</p>
        </div>

        <!-- ALERT -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl mb-4 ring-1 ring-emerald-600/10 flex items-center gap-3">
                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-2xl mb-4 ring-1 ring-red-600/10 flex items-center gap-3">
                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold">{{ session('error') }}</span>
            </div>
        @endif

        <!-- FILTER BAR -->
        <div class="bg-white rounded-2xl shadow-sm p-4 mb-4 ring-1 ring-slate-100">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <!-- Search -->
                <div class="relative md:col-span-2">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama atau NIS..."
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                </div>

                <!-- Status -->
                <select name="status"
                        class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>

                <!-- Jenis -->
                <select name="jenis"
                        class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    <option value="">Semua Jenis</option>
                    <option value="izin" {{ request('jenis') === 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ request('jenis') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="cuti" {{ request('jenis') === 'cuti' ? 'selected' : '' }}>Cuti</option>
                </select>

                <div class="flex gap-2 md:col-span-4">
                    <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 active:scale-95 font-semibold text-sm transition-all duration-150 shadow-lg shadow-indigo-500/20 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter
                    </button>
                    @if(request()->hasAny(['status', 'jenis', 'search']))
                        <a href="{{ route('admin.izin.index') }}"
                           class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 active:scale-95 font-semibold text-sm transition-all duration-150">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- LIST -->
        @if($requests->count() > 0)
            <div class="space-y-3">
                @foreach($requests as $izin)
                    @php
                        $statusStyle = match($izin->status) {
                            'pending'   => ['bg-amber-50',  'text-amber-700',  'ring-amber-600/20',  '⏳', 'Menunggu'],
                            'disetujui' => ['bg-emerald-50','text-emerald-700','ring-emerald-600/20','✓', 'Disetujui'],
                            'ditolak'   => ['bg-red-50',    'text-red-700',    'ring-red-600/20',    '✗', 'Ditolak'],
                            default     => ['bg-slate-50',  'text-slate-700',  'ring-slate-400/20',  '?', 'Unknown'],
                        };
                        $jenisStyle = match($izin->jenis) {
                            'izin'  => ['bg-blue-50',  'text-blue-700',  'ring-blue-600/20',  '📝'],
                            'sakit' => ['bg-red-50',   'text-red-700',   'ring-red-600/20',   '🤒'],
                            'cuti'  => ['bg-violet-50','text-violet-700','ring-violet-600/20','🏖️'],
                            default => ['bg-slate-50', 'text-slate-700', 'ring-slate-400/20', 'ℹ️'],
                        };
                    @endphp

                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 border border-slate-100 overflow-hidden">
                        <div class="p-5">
                            <div class="flex flex-col md:flex-row md:items-start gap-4">

                                <!-- Avatar & Info User -->
                                <div class="flex items-center gap-3 flex-shrink-0">
                                    <img src="{{ $izin->user->foto_profil ? Storage::url($izin->user->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($izin->user->name) . '&size=80&background=4f46e5&color=fff&bold=true' }}"
                                         class="w-12 h-12 rounded-full object-cover border-2 border-indigo-100"
                                         alt="{{ $izin->user->name }}">
                                    <div class="md:hidden">
                                        <div class="font-semibold text-slate-900 text-sm">{{ $izin->user->name }}</div>
                                        <div class="text-xs text-slate-500 font-mono">{{ $izin->user->nis }}</div>
                                    </div>
                                </div>

                                <!-- Detail -->
                                <div class="flex-1 min-w-0">
                                    <!-- User (desktop) -->
                                    <div class="hidden md:block mb-2">
                                        <div class="font-semibold text-slate-900">{{ $izin->user->name }}</div>
                                        <div class="text-xs text-slate-500">
                                            NIS: <span class="font-mono">{{ $izin->user->nis }}</span>
                                            @if($izin->user->kelas)
                                                <span class="text-slate-300 mx-1">•</span>
                                                <span>{{ $izin->user->kelas }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Badges -->
                                    <div class="flex items-center gap-2 flex-wrap mb-2">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-xs font-semibold rounded-full {{ $jenisStyle[0] }} {{ $jenisStyle[1] }} ring-1 {{ $jenisStyle[2] }}">
                                            {{ $jenisStyle[3] }} {{ ucfirst($izin->jenis) }}
                                        </span>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-xs font-semibold rounded-full {{ $statusStyle[0] }} {{ $statusStyle[1] }} ring-1 {{ $statusStyle[2] }}">
                                            {{ $statusStyle[3] }} {{ $statusStyle[4] }}
                                        </span>
                                    </div>

                                    <!-- Tanggal -->
                                    <div class="flex items-center gap-1.5 text-sm text-slate-600 mb-2">
                                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        @if($izin->tanggal_mulai->equalTo($izin->tanggal_selesai))
                                            <span class="font-medium">{{ $izin->tanggal_mulai->translatedFormat('d F Y') }}</span>
                                        @else
                                            <span class="font-medium">{{ $izin->tanggal_mulai->translatedFormat('d M Y') }}</span>
                                            <span class="text-slate-400">—</span>
                                            <span class="font-medium">{{ $izin->tanggal_selesai->translatedFormat('d M Y') }}</span>
                                        @endif
                                    </div>

                                    <!-- Alasan -->
                                    <p class="text-sm text-slate-600 line-clamp-2">
                                        <span class="text-slate-400">Alasan:</span> {{ $izin->alasan }}
                                    </p>
                                </div>

                                <!-- Action -->
                                <div class="flex md:flex-col gap-2 flex-shrink-0">
                                    <a href="{{ route('admin.izin.show', $izin) }}"
                                       class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 active:scale-95 font-semibold text-sm transition-all duration-150 shadow-lg shadow-indigo-500/20">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-5 py-3 bg-slate-50 border-t border-slate-100 text-xs text-slate-500 flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Diajukan {{ $izin->created_at->diffForHumans() }}
                            </div>
                            @if($izin->reviewer)
                                <div class="text-slate-400">
                                    Direview oleh <span class="font-semibold text-slate-600">{{ $izin->reviewer->name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if($requests->hasPages())
                <div class="mt-6">
                    {{ $requests->withQueryString()->links() }}
                </div>
            @endif
        @else
            <!-- EMPTY STATE -->
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center ring-1 ring-slate-100">
                <div class="w-20 h-20 mx-auto mb-4 bg-slate-100 rounded-2xl flex items-center justify-center">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">Tidak ada pengajuan</h3>
                <p class="text-sm text-slate-500">
                    @if(request()->hasAny(['status', 'jenis', 'search']))
                        Coba ubah filter atau reset pencarian
                    @else
                        Belum ada pengajuan izin dari siswa atau guru
                    @endif
                </p>
            </div>
        @endif

    </div>
</div>
@endsection