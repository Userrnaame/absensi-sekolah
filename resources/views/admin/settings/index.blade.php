@extends('layouts.app')

@section('title', 'Setting Lokasi Sekolah')

@section('content')
<div class="min-h-screen bg-slate-50">
    @include('partials.navbar')

    <div class="max-w-3xl mx-auto px-4 py-8">

        <!-- HEADER -->
        <div class="mb-6">
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-2">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-50 text-red-700 rounded-full font-semibold ring-1 ring-red-600/10">
                    ADMIN
                </span>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Setting Lokasi Sekolah</h1>
            <p class="text-sm text-slate-500 mt-1">Atur koordinat dan radius area absensi siswa</p>
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

        <form method="POST" action="{{ route('admin.settings.update') }}"
              class="bg-white rounded-2xl shadow-sm p-6 space-y-5 ring-1 ring-slate-100">
            @csrf
            @method('PUT')

            <!-- Section: Koordinat -->
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">Koordinat Pusat</h3>
                        <p class="text-xs text-slate-500">Titik pusat area absensi (lokasi sekolah)</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Latitude <span class="text-red-500">*</span></label>
                        <input type="text" name="latitude" id="latitude"
                               value="{{ old('latitude', $setting->latitude) }}" required
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Longitude <span class="text-red-500">*</span></label>
                        <input type="text" name="longitude" id="longitude"
                               value="{{ old('longitude', $setting->longitude) }}" required
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>
                </div>

                <!-- Tombol Lokasi Saya -->
                <div class="mt-4 p-4 bg-indigo-50 rounded-xl ring-1 ring-indigo-700/10">
                    <div class="flex items-start gap-2 mb-3">
                        <svg class="w-5 h-5 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-indigo-900">
                            <strong>Tips:</strong> Buka Google Maps, klik kanan di lokasi sekolah, pilih koordinat untuk copy. Atau klik tombol di bawah untuk ambil lokasi Anda sekarang.
                        </p>
                    </div>
                    <button type="button" id="btn-my-loc"
                            class="w-full md:w-auto px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 active:scale-95 font-semibold text-sm transition-all duration-150 shadow-lg shadow-indigo-500/20 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Gunakan Lokasi Saya Sekarang
                    </button>
                    <p id="loc-status" class="text-xs text-indigo-700 mt-2"></p>
                </div>
            </div>

            <hr class="border-slate-100">

            <!-- Section: Radius -->
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">Radius Area</h3>
                        <p class="text-xs text-slate-500">Jarak maksimal siswa bisa absen dari titik pusat</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Radius (meter) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="radius_meters" id="radius_meters"
                           value="{{ old('radius_meters', $setting->radius_meters) }}"
                           min="10" max="10000" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    <p class="text-xs text-slate-500 mt-1">Minimal 10 meter, maksimal 10.000 meter.</p>

                    <!-- Preview -->
                    <div class="mt-4 bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl p-4 ring-1 ring-indigo-100">
                        <div class="flex items-center justify-between text-xs mb-2">
                            <span class="font-semibold text-slate-600">Preview Radius</span>
                            <span class="font-mono font-bold text-indigo-700"><span id="radius-display">{{ $setting->radius_meters }}</span> m</span>
                        </div>
                        <div class="relative h-40 bg-white rounded-lg overflow-hidden ring-1 ring-slate-200">
                            <svg viewBox="0 0 400 160" class="w-full h-full" preserveAspectRatio="xMidYMid meet">
                                <!-- Background grid -->
                                <defs>
                                    <pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse">
                                        <path d="M 20 0 L 0 0 0 20" fill="none" stroke="#e2e8f0" stroke-width="0.5"/>
                                    </pattern>
                                </defs>
                                <rect width="400" height="160" fill="url(#grid)"/>

                                <!-- Lingkaran radius -->
                                <circle cx="200" cy="80" r="55" fill="#4f46e5" opacity="0.15" stroke="#4f46e5" stroke-width="2" stroke-dasharray="4 4"/>

                                <!-- Titik pusat -->
                                <circle cx="200" cy="80" r="6" fill="#4f46e5"/>
                                <circle cx="200" cy="80" r="12" fill="none" stroke="#4f46e5" stroke-width="1.5" opacity="0.4"/>

                                <!-- Label -->
                                <text x="200" y="105" text-anchor="middle" fill="#1e293b" font-size="11" font-weight="bold">🏫 Sekolah</text>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <!-- Section: Jam -->
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">Jam Operasional</h3>
                        <p class="text-xs text-slate-500">Jam masuk & pulang sekolah</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jam Masuk <span class="text-red-500">*</span></label>
                        <input type="time" name="jam_masuk" value="{{ old('jam_masuk', $setting->jam_masuk) }}" required
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jam Pulang <span class="text-red-500">*</span></label>
                        <input type="time" name="jam_pulang" value="{{ old('jam_pulang', $setting->jam_pulang) }}" required
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 active:scale-95 font-semibold text-sm transition-all duration-150 shadow-lg shadow-indigo-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Pengaturan
                </button>
                <a href="{{ route('dashboard') }}"
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
    // Tombol "Gunakan Lokasi Saya"
    document.getElementById('btn-my-loc').addEventListener('click', () => {
        if (!navigator.geolocation) {
            document.getElementById('loc-status').textContent = '❌ Browser tidak mendukung geolocation';
            return;
        }

        const status = document.getElementById('loc-status');
        const btn = document.getElementById('btn-my-loc');

        status.textContent = '⏳ Mendeteksi lokasi...';
        btn.disabled = true;

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                document.getElementById('latitude').value  = pos.coords.latitude.toFixed(7);
                document.getElementById('longitude').value = pos.coords.longitude.toFixed(7);
                status.textContent = '✅ Lokasi terisi (akurasi ±' + Math.round(pos.coords.accuracy) + ' meter)';
                btn.disabled = false;

                if (typeof showToast === 'function') {
                    showToast('Lokasi berhasil dideteksi', 'success');
                }
            },
            (err) => {
                status.textContent = '❌ Gagal deteksi lokasi: ' + err.message;
                btn.disabled = false;

                if (typeof showToast === 'function') {
                    showToast('Gagal deteksi lokasi: ' + err.message, 'error');
                }
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    });

    // Update preview radius saat input berubah
    document.getElementById('radius_meters').addEventListener('input', function () {
        document.getElementById('radius-display').textContent = this.value || '0';
    });
</script>
@endpush