@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="min-h-screen bg-slate-50">
    @include('partials.navbar')

    <div class="max-w-3xl mx-auto px-4 py-8">

        <!-- BREADCRUMB -->
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-indigo-600 transition mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Manajemen User
            </a>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Tambah User Baru</h1>
            <p class="text-sm text-slate-500 mt-1">Buat akun siswa, guru, atau admin baru</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-2xl mb-4 ring-1 ring-red-600/10">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-semibold text-sm">Ada kesalahan:</span>
                </div>
                <ul class="list-disc list-inside text-sm ml-7">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}"
              class="bg-white rounded-2xl shadow-sm p-6 space-y-5 ring-1 ring-slate-100">
            @csrf

            <!-- Role -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Role <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-3 gap-3">
                    @foreach(['siswa' => '👤', 'guru' => '👨‍🏫', 'admin' => '🛡️'] as $role => $icon)
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="{{ $role }}" class="peer sr-only"
                                   {{ old('role', 'siswa') === $role ? 'checked' : '' }}>
                            <div class="text-center py-4 border-2 rounded-xl transition-all duration-200
                                        peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 peer-checked:shadow-lg peer-checked:shadow-indigo-500/10
                                        border-slate-200 text-slate-600 hover:border-slate-300">
                                <div class="text-2xl mb-1">{{ $icon }}</div>
                                <div class="font-semibold text-sm capitalize">{{ $role }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Nama -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                       placeholder="Contoh: Budi Santoso">
            </div>

            <!-- NIS + Generate -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">NIS <span class="text-red-500">*</span></label>
                <div class="flex gap-2">
                    <input type="text" name="nis" id="nis" value="{{ old('nis') }}" required
                           class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                           placeholder="Contoh: 2026001">
                    <button type="button" id="btn-generate"
                            data-url="{{ route('admin.users.generate-nis') }}"
                            class="px-4 py-2.5 bg-slate-800 text-white rounded-xl hover:bg-slate-900 active:scale-95 font-semibold text-sm whitespace-nowrap transition-all duration-150 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Generate
                    </button>
                </div>
                <p class="text-xs text-slate-500 mt-1">Klik "Generate" untuk buat NIS otomatis sesuai role.</p>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Password <span class="text-red-500">*</span></label>
                <input type="text" name="password" id="password" value="{{ old('password', 'sekolah123') }}" required
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                <p class="text-xs text-slate-500 mt-1">Default: <code class="bg-slate-100 px-1.5 py-0.5 rounded font-mono">sekolah123</code>. User bisa ubah sendiri di halaman profil.</p>
            </div>

            <!-- Bio -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Bio <span class="text-xs font-normal text-slate-500">(opsional)</span></label>
                <textarea name="bio" rows="3"
                          class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none"
                          placeholder="Keterangan tambahan...">{{ old('bio') }}</textarea>
            </div>

            <!-- Submit -->
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 active:scale-95 font-semibold text-sm transition-all duration-150 shadow-lg shadow-indigo-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan User
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="px-6 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 active:scale-95 font-semibold text-sm transition-all duration-150">
                    Batal
                </a>
            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('btn-generate').addEventListener('click', async function () {
        const role = document.querySelector('input[name="role"]:checked').value;
        const btn = this;
        const url = document.getElementById('btn-generate').dataset.url;
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        btn.disabled = true;
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ role: role })
            });

            const data = await response.json();
            document.getElementById('nis').value = data.nis;

            if (typeof showToast === 'function') {
                showToast('NIS berhasil di-generate: ' + data.nis, 'success');
            }
        } catch (error) {
            if (typeof showToast === 'function') {
                showToast('Gagal generate NIS: ' + error.message, 'error');
            } else {
                alert('Gagal: ' + error.message);
            }
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        }
    });
</script>
@endpush