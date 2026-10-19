@extends('layouts.app')

@section('title', 'Pengajuan Izin')

@section('content')
<div class="min-h-screen bg-slate-50">
    @include('partials.navbar')

    <div class="max-w-5xl mx-auto px-4 py-8">

        <!-- ============ HEADER ============ -->
       <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 via-blue-600 to-indigo-700 p-6 md:p-8 shadow-xl shadow-indigo-500/10 mb-6">
    <div class="absolute inset-0 opacity-[0.08] bg-cover bg-center"
         style="background-image: url('{{ asset('images/hero-sekolah.jpg') }}');"></div>
    <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>

    <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-blue-200 mb-1">Pengajuan Izin</p>
            <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">Ajukan Izin / Sakit / Cuti</h1>
        </div>
        <a href="{{ route('izin.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-indigo-700 rounded-xl hover:bg-blue-50 active:scale-95 font-semibold text-sm transition-all duration-150 shadow-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Pengajuan
        </a>
    </div>
</div>
            
        <!-- ============ ALERT SUCCESS ============ -->
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

        <!-- ============ LIST PENGAJUAN ============ -->
        @if($requests->count() > 0)
            <div class="space-y-3">
                @foreach($requests as $izin)
                    @php
                        $statusStyle = match($izin->status) {
                            'pending'    => ['bg-amber-50',  'text-amber-700',  'ring-amber-600/20',  '⏳', 'Menunggu'],
                            'disetujui'  => ['bg-emerald-50','text-emerald-700','ring-emerald-600/20','✓', 'Disetujui'],
                            'ditolak'    => ['bg-red-50',    'text-red-700',    'ring-red-600/20',    '✗', 'Ditolak'],
                            default      => ['bg-slate-50',  'text-slate-700',  'ring-slate-400/20',  '?', 'Unknown'],
                        };

                        $jenisStyle = match($izin->jenis) {
                            'izin'  => ['bg-blue-50',  'text-blue-700',  'ring-blue-600/20',  '📝'],
                            'sakit' => ['bg-red-50',   'text-red-700',   'ring-red-600/20',   '🤒'],
                            'cuti'  => ['bg-violet-50','text-violet-700','ring-violet-600/20','🏖️'],
                            default => ['bg-slate-50', 'text-slate-700', 'ring-slate-400/20', 'ℹ️'],
                        };

                        $isPending = $izin->status === 'pending';
                    @endphp

                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 border border-slate-100 overflow-hidden">
                        <div class="p-5">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">

                                <div class="flex items-start gap-4 flex-1 min-w-0">
                                    <div class="w-12 h-12 {{ $jenisStyle[0] }} ring-1 {{ $jenisStyle[2] }} rounded-2xl flex items-center justify-center text-2xl flex-shrink-0">
                                        {{ $jenisStyle[3] }}
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap mb-2">
                                            <h3 class="font-bold text-slate-900 capitalize">{{ $izin->jenis }}</h3>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-xs font-semibold rounded-full {{ $statusStyle[0] }} {{ $statusStyle[1] }} ring-1 {{ $statusStyle[2] }}">
                                                {{ $statusStyle[3] }} {{ $statusStyle[4] }}
                                            </span>
                                        </div>

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

                                        <p class="text-sm text-slate-600 line-clamp-2">
                                            <span class="text-slate-400">Alasan:</span> {{ $izin->alasan }}
                                        </p>

                                        @if($izin->lampiran)
                                            <a href="{{ Storage::url($izin->lampiran) }}" target="_blank"
                                               class="inline-flex items-center gap-1.5 mt-2 text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                                </svg>
                                                Lihat Lampiran
                                            </a>
                                        @endif

                                        @if($izin->catatan_admin && $izin->status !== 'pending')
                                            <div class="mt-3 p-3 {{ $statusStyle[0] }} rounded-xl ring-1 {{ $statusStyle[2] }}">
                                                <div class="text-xs font-semibold {{ $statusStyle[1] }} mb-1">Catatan Admin:</div>
                                                <p class="text-sm {{ $statusStyle[1] }}">{{ $izin->catatan_admin }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if($isPending)
                                    <form method="POST" action="{{ route('izin.destroy', $izin) }}"
                                          onsubmit="return confirm('Yakin batalkan pengajuan ini?')"
                                          class="flex-shrink-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 active:scale-95 font-semibold text-sm transition-all duration-150 ring-1 ring-red-600/10">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Batalkan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <div class="px-5 py-3 bg-slate-50 border-t border-slate-100 text-xs text-slate-500 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Diajukan {{ $izin->created_at->diffForHumans() }}
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
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center ring-1 ring-slate-100">
                <div class="w-20 h-20 mx-auto mb-4 bg-indigo-50 ring-1 ring-indigo-700/10 rounded-2xl flex items-center justify-center">
                    <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">Belum ada pengajuan izin</h3>
                <p class="text-sm text-slate-500 mb-6">Ajukan izin, sakit, atau cuti lewat tombol di bawah</p>
                <a href="{{ route('izin.create') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 active:scale-95 font-semibold text-sm transition-all duration-150 shadow-lg shadow-indigo-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Pengajuan Sekarang
                </a>
            </div>
        @endif

    </div>
</div>
@endsection