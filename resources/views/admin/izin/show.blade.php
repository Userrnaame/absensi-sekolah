@extends('layouts.app')

@section('title', 'Detail Pengajuan Izin')

@section('content')
<div class="min-h-screen bg-slate-50">
    @include('partials.navbar')

    <div class="max-w-4xl mx-auto px-4 py-8">

        <!-- BREADCRUMB -->
        <div class="mb-6">
            <a href="{{ route('admin.izin.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-indigo-600 transition mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Kelola Izin
            </a>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Detail Pengajuan</h1>
            <p class="text-sm text-slate-500 mt-1">Review dan proses pengajuan izin</p>
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

        @php
            $statusStyle = match($izin->status) {
                'pending'   => ['bg-amber-50',  'text-amber-700',  'ring-amber-600/20',  '⏳', 'Menunggu Persetujuan'],
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

        <!-- ============ CARD USER PENGAJU ============ -->
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-4 ring-1 ring-slate-100">
            <div class="flex items-center gap-4">
                <img src="{{ $izin->user->foto_profil ? Storage::url($izin->user->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($izin->user->name) . '&size=100&background=4f46e5&color=fff&bold=true' }}"
                     class="w-16 h-16 rounded-2xl object-cover border-2 border-indigo-100"
                     alt="{{ $izin->user->name }}">
                <div class="flex-1">
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Pengaju</div>
                    <div class="font-bold text-lg text-slate-900">{{ $izin->user->name }}</div>
                    <div class="text-sm text-slate-500 mt-0.5">
                        NIS: <span class="font-mono">{{ $izin->user->nis }}</span>
                        @if($izin->user->kelas)
                            <span class="text-slate-300 mx-1">•</span>
                            <span>{{ $izin->user->kelas }}</span>
                        @endif
                        @if($izin->user->jurusan)
                            <span class="text-slate-300 mx-1">•</span>
                            <span>{{ $izin->user->jurusan }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ============ CARD DETAIL PENGAJUAN ============ -->
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-4 ring-1 ring-slate-100">

            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-3 mb-6 pb-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 {{ $jenisStyle[0] }} ring-1 {{ $jenisStyle[2] }} rounded-2xl flex items-center justify-center text-2xl flex-shrink-0">
                        {{ $jenisStyle[3] }}
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Jenis Pengajuan</div>
                        <div class="font-bold text-lg text-slate-900 capitalize">{{ $izin->jenis }}</div>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-bold rounded-xl {{ $statusStyle[0] }} {{ $statusStyle[1] }} ring-1 {{ $statusStyle[2] }}">
                    <span class="text-base">{{ $statusStyle[3] }}</span>
                    {{ $statusStyle[4] }}
                </span>
            </div>

            <!-- Info Grid -->
            <div class="grid md:grid-cols-2 gap-4 mb-6">
                <div class="bg-slate-50 rounded-xl p-4 ring-1 ring-slate-200/50">
                    <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tanggal Mulai</div>
                    <div class="font-bold text-slate-900">{{ $izin->tanggal_mulai->translatedFormat('d F Y') }}</div>
                    <div class="text-xs text-slate-500 mt-1">{{ $izin->tanggal_mulai->translatedFormat('l') }}</div>
                </div>
                <div class="bg-slate-50 rounded-xl p-4 ring-1 ring-slate-200/50">
                    <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tanggal Selesai</div>
                    <div class="font-bold text-slate-900">{{ $izin->tanggal_selesai->translatedFormat('d F Y') }}</div>
                    <div class="text-xs text-slate-500 mt-1">{{ $izin->tanggal_selesai->translatedFormat('l') }}</div>
                </div>
            </div>

            <!-- Durasi -->
            @php
                $durasi = $izin->tanggal_mulai->diffInDays($izin->tanggal_selesai) + 1;
            @endphp
            <div class="bg-indigo-50 rounded-xl p-4 ring-1 ring-indigo-700/10 mb-6">
                <div class="flex items-center gap-2 text-indigo-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-semibold">Total: {{ $durasi }} hari</span>
                </div>
            </div>

            <!-- Alasan -->
            <div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Alasan Pengajuan</div>
                <div class="bg-slate-50 rounded-xl p-4 ring-1 ring-slate-200/50 text-sm text-slate-700 leading-relaxed">
                    {{ $izin->alasan }}
                </div>
            </div>

            <!-- Lampiran -->
            @if($izin->lampiran)
                <div class="mt-6">
                    <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Lampiran</div>
                    @php
                        $ext = strtolower(pathinfo($izin->lampiran, PATHINFO_EXTENSION));
                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                    @endphp

                    @if($isImage)
                        <a href="{{ Storage::url($izin->lampiran) }}" target="_blank"
                           class="block rounded-xl overflow-hidden ring-1 ring-slate-200 hover:ring-indigo-300 transition">
                            <img src="{{ Storage::url($izin->lampiran) }}"
                                 alt="Lampiran"
                                 class="w-full max-h-96 object-contain bg-slate-50">
                        </a>
                    @else
                        <a href="{{ Storage::url($izin->lampiran) }}" target="_blank"
                           class="inline-flex items-center gap-2 px-4 py-3 bg-slate-50 rounded-xl ring-1 ring-slate-200 hover:ring-indigo-300 hover:bg-indigo-50 text-sm font-semibold text-slate-700 hover:text-indigo-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Buka Lampiran ({{ strtoupper($ext) }})
                        </a>
                    @endif
                </div>
            @endif
        </div>

        <!-- ============ REVIEWER INFO (Kalau Sudah Diproses) ============ -->
        @if($izin->reviewer && $izin->status !== 'pending')
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-4 ring-1 ring-slate-100 border-l-4 {{ $izin->status === 'disetujui' ? 'border-emerald-500' : 'border-red-500' }}">
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Review Admin</div>
                <div class="flex items-start gap-3">
                    <img src="{{ $izin->reviewer->foto_profil ? Storage::url($izin->reviewer->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($izin->reviewer->name) . '&size=80&background=4f46e5&color=fff&bold=true' }}"
                         class="w-12 h-12 rounded-full object-cover border-2 border-indigo-100"
                         alt="{{ $izin->reviewer->name }}">
                    <div class="flex-1">
                        <div class="font-semibold text-slate-900">{{ $izin->reviewer->name }}</div>
                        <div class="text-xs text-slate-500">Diproses {{ $izin->updated_at->diffForHumans() }}</div>
                        @if($izin->catatan_admin)
                            <div class="mt-3 p-3 {{ $statusStyle[0] }} rounded-xl ring-1 {{ $statusStyle[2] }}">
                                <div class="text-xs font-semibold {{ $statusStyle[1] }} mb-1">Catatan:</div>
                                <p class="text-sm {{ $statusStyle[1] }}">{{ $izin->catatan_admin }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- ============ FORM APPROVE / REJECT (Kalau Pending) ============ -->
        @if($izin->status === 'pending')
            <div class="bg-white rounded-2xl shadow-sm p-6 ring-1 ring-slate-100">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900">Proses Pengajuan</h3>
                </div>

                <p class="text-sm text-slate-500 mb-4">
                    Berikan catatan (opsional) dan pilih untuk menyetujui atau menolak pengajuan ini.
                </p>

                <!-- Catatan Admin -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Catatan Admin <span class="text-xs font-normal text-slate-500">(opsional)</span>
                    </label>
                    <textarea id="catatan_admin_input" rows="3" maxlength="500"
                              placeholder="Contoh: Disetujui, semoga lekas sembuh..."
                              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none"></textarea>
                    <p class="text-xs text-slate-500 mt-1">Maks 500 karakter</p>
                </div>

                <!-- Tombol Action -->
                <div class="grid grid-cols-2 gap-3">
                    <!-- Tolak -->
                    <form method="POST" action="{{ route('admin.izin.reject', $izin) }}"
                          onsubmit="return confirm('Yakin tolak pengajuan ini?')"
                          id="form-reject">
                        @csrf
                        <input type="hidden" name="catatan_admin" id="catatan_reject">
                        <button type="submit"
                                class="w-full py-3 bg-red-50 text-red-700 rounded-xl hover:bg-red-100 active:scale-95 font-semibold text-sm transition-all duration-150 ring-1 ring-red-600/20 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Tolak Pengajuan
                        </button>
                    </form>

                    <!-- Setujui -->
                    <form method="POST" action="{{ route('admin.izin.approve', $izin) }}"
                          onsubmit="return confirm('Setujui pengajuan ini?')"
                          id="form-approve">
                        @csrf
                        <input type="hidden" name="catatan_admin" id="catatan_approve">
                        <button type="submit"
                                class="w-full py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 active:scale-95 font-semibold text-sm transition-all duration-150 shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Setujui
                        </button>
                    </form>
                </div>
            </div>

            @push('scripts')
            <script>
                // Copy catatan dari textarea ke form sebelum submit
                document.getElementById('form-approve').addEventListener('submit', function () {
                    document.getElementById('catatan_approve').value = document.getElementById('catatan_admin_input').value;
                });
                document.getElementById('form-reject').addEventListener('submit', function () {
                    document.getElementById('catatan_reject').value = document.getElementById('catatan_admin_input').value;
                });
            </script>
            @endpush
        @endif

    </div>
</div>
@endsection