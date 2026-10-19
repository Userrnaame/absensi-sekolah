@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="min-h-screen bg-slate-50">
    @include('partials.navbar')

    <div class="max-w-3xl mx-auto px-4 py-8">

        @include('partials.hero', [
            'title' => 'Profil Saya',
            'subtitle' => 'Kelola informasi akun & foto Anda',
            'showUser' => true,
        ])

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl mb-4 ring-1 ring-emerald-600/20">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-xl mb-4 ring-1 ring-red-600/20">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}"
              enctype="multipart/form-data"
              x-data="{ loading: false, autoRemoveBg: true, adaLagu: {{ $user->hasLagu() ? 'true' : 'false' }} }"
              @submit="if (!loading) { loading = true; }"
              class="bg-white rounded-2xl shadow-sm p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- ============ FOTO PROFIL (avatar) ============ -->
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6 pb-6 border-b border-slate-100">
                <div class="relative">
                    <img id="foto-preview"
                         src="{{ $user->foto_profil ? Storage::url($user->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=200&background=3b82f6&color=fff' }}"
                         alt="Foto Profil"
                         class="w-24 h-24 rounded-full object-cover border-4 border-indigo-100 shadow">
                    <label for="foto"
                           class="absolute bottom-0 right-0 w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center cursor-pointer hover:bg-indigo-700 transition shadow-lg active:scale-95">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </label>
                    <input type="file" id="foto" name="foto" accept="image/*" class="hidden">
                </div>
                <div class="flex-1 text-center md:text-left">
                    <h2 class="text-lg font-bold text-slate-900">Foto Profil</h2>
                    <p class="text-sm text-slate-500">Tampil di navbar & dashboard. Max 2 MB, JPG/PNG.</p>
                </div>
            </div>

            <!-- ============ FOTO PORTAL (setengah badan) ============ -->
            @if($user->role === 'siswa')
            <div class="pb-6 border-b border-slate-100">
                <div class="flex flex-col md:flex-row items-start gap-6">
                    <div class="relative mx-auto md:mx-0">
                        <img id="foto-siswa-preview"
                             src="{{ $user->foto_siswa_url }}"
                             alt="Foto Portal"
                             class="w-32 h-40 rounded-xl object-cover border-4 border-indigo-100 shadow bg-slate-100">
                        <label for="foto_siswa"
                               class="absolute -bottom-2 -right-2 w-9 h-9 bg-indigo-600 rounded-full flex items-center justify-center cursor-pointer hover:bg-indigo-700 transition shadow-lg active:scale-95">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </label>
                        <input type="file" id="foto_siswa" name="foto_siswa" accept="image/*" class="hidden">
                    </div>
                    <div class="flex-1 text-center md:text-left w-full">
                        <h2 class="text-lg font-bold text-slate-900">Foto Portal</h2>
                        <p class="text-sm text-slate-500 mb-2">
                            Foto <strong>setengah badan</strong> yang akan tampil di <strong>Portal Sekolah</strong>.
                        </p>
                        <ul class="text-xs text-slate-500 space-y-1 list-disc list-inside mb-3">
                            <li>Format JPG/PNG, max 5 MB</li>
                            <li>Rasio disarankan 3:4 (portrait)</li>
                            <li>Kalau belum upload, akan pakai avatar otomatis</li>
                        </ul>

                        <!-- CHECKBOX AUTO REMOVE BG -->
                        <label class="flex items-start gap-3 p-3 bg-indigo-50 rounded-xl cursor-pointer ring-1 ring-indigo-700/10 hover:ring-indigo-700/20 transition">
                            <input type="checkbox"
                                   name="auto_remove_bg"
                                   value="1"
                                   x-model="autoRemoveBg"
                                   class="mt-0.5 w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                            <div class="flex-1">
                                <div class="flex items-center gap-1.5 font-semibold text-sm text-slate-900">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                    </svg>
                                    Hapus background otomatis (AI)
                                </div>
                                <div class="text-xs text-slate-500 mt-0.5">
                                    <span x-show="autoRemoveBg">Foto akan diproses otomatis, ~10-15 detik. Hasil: background transparan.</span>
                                    <span x-show="!autoRemoveBg" x-cloak>Foto akan disimpan apa adanya (tanpa hapus background).</span>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            @endif

            <!-- ============ DATA DIRI ============ -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Bio (untuk dashboard)</label>
                <textarea name="bio" rows="2" maxlength="500"
                          class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition"
                          placeholder="Ceritakan sedikit tentang Anda...">{{ old('bio', $user->bio) }}</textarea>
            </div>

            @if($user->role === 'siswa')
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Bio Portal (tampil di popup profil)</label>
                <textarea name="bio_siswa" rows="2" maxlength="500"
                          class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition"
                          placeholder="Keterangan singkat untuk portal sekolah...">{{ old('bio_siswa', $user->bio_siswa) }}</textarea>
            </div>
            @endif

            <!-- ============ LAGU FAVORIT ============ -->
            @if($user->role === 'siswa')
            <div class="pt-4 border-t border-slate-100">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">Lagu Favorit</h3>
                        <p class="text-xs text-slate-500">Tampil di profil portal. Dipotong 30 detik otomatis.</p>
                    </div>
                </div>

                <!-- Preview lagu yang ada -->
                @if($user->hasLagu())
                    <div class="bg-gradient-to-br from-indigo-50 to-blue-50 border border-indigo-100 rounded-xl p-4 mb-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-slate-900 text-sm truncate">
                                    {{ $user->lagu_judul ?? 'Tanpa judul' }}
                                </div>
                                <div class="text-xs text-slate-500 truncate">
                                    {{ $user->lagu_artis ?? 'Unknown artist' }}
                                </div>
                                <div class="text-[10px] text-indigo-600 mt-1 font-medium">
                                    ✂️ Dipotong dari detik ke-{{ $user->lagu_start_detik ?? 30 }} (30 detik)
                                </div>
                            </div>
                        </div>
                        <audio controls class="w-full h-10">
                            <source src="{{ $user->lagu_url }}" type="audio/mpeg">
                            Browser Anda tidak mendukung audio.
                        </audio>
                    </div>
                @endif

                <!-- Upload form -->
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            {{ $user->hasLagu() ? 'Ganti Lagu' : 'Upload Lagu' }}
                            <span class="text-xs font-normal text-slate-500">(MP3/WAV/OGG/M4A, max 10 MB)</span>
                        </label>
                        <input type="file" name="lagu" accept="audio/*"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all">
                        <p class="text-xs text-slate-500 mt-1">
                            Lagu akan otomatis dipotong <strong>30 detik</strong> dari detik yang Anda pilih.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Judul Lagu</label>
                            <input type="text" name="lagu_judul" value="{{ old('lagu_judul', $user->lagu_judul) }}"
                                   placeholder="Besok Mungkin Kita Sampai"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Artis</label>
                            <input type="text" name="lagu_artis" value="{{ old('lagu_artis', $user->lagu_artis) }}"
                                   placeholder="Hindia"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Mulai dari Detik ke-
                            <span class="text-xs font-normal text-slate-500">(bagian yang mau diputar)</span>
                        </label>
                        <input type="number" name="lagu_start_detik" min="0" max="600"
                               value="{{ old('lagu_start_detik', $user->lagu_start_detik ?? 30) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <p class="text-xs text-slate-500 mt-1">
                            💡 <strong>Tips:</strong> Chorus biasanya di detik <strong>30-60</strong>. Coba beberapa kali sampai dapat bagian favorit.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- ============ UBAH PASSWORD ============ -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="font-semibold text-slate-900 mb-3">Ubah Password</h3>
                <p class="text-xs text-slate-500 mb-3">Kosongkan jika tidak ingin mengubah password.</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Password Baru</label>
                        <input type="password" name="password"
                               class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition"
                               placeholder="Minimal 6 karakter">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation"
                               class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition"
                               placeholder="Ulangi password baru">
                    </div>
                </div>
            </div>

            <!-- ============ SUBMIT ============ -->
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        :disabled="loading"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 active:scale-95 font-semibold transition-all duration-150 disabled:opacity-50 flex items-center gap-2 shadow-lg shadow-indigo-500/20">
                    <template x-if="loading">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </template>
                    <span x-text="loading ? 'Memproses...' : 'Simpan Perubahan'"></span>
                </button>
                <a href="{{ route('dashboard') }}"
                   class="px-6 py-2 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 active:scale-95 font-semibold transition-all duration-150">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <!-- LOADING OVERLAY -->
    <div x-show="loading"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full text-center">
            <div class="w-16 h-16 mx-auto mb-4 relative">
                <svg class="animate-spin w-16 h-16 text-indigo-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <h3 class="font-bold text-lg text-slate-900 mb-1">Menyimpan Perubahan...</h3>
            <p class="text-sm text-slate-500" x-show="autoRemoveBg">
                Menghapus background otomatis dengan AI.<br>
                Proses ini butuh ~10-15 detik. Mohon tunggu.
            </p>
            <p class="text-sm text-slate-500" x-show="!autoRemoveBg" x-cloak>
                Menyimpan perubahan Anda.
            </p>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@push('scripts')
<script>
// Preview foto profil
document.getElementById('foto').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (event) => {
            document.getElementById('foto-preview').src = event.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Preview foto siswa
const fotoSiswaInput = document.getElementById('foto_siswa');
if (fotoSiswaInput) {
    fotoSiswaInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                document.getElementById('foto-siswa-preview').src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
}
</script>
@endpush