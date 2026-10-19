<div x-data="{ open: false }">

   <nav class="bg-white/90 backdrop-blur-md shadow-sm sticky top-0 z-40 relative">
    <!-- Gradient accent line -->
    <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-indigo-500 via-blue-500 to-violet-500"></div>
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex justify-between items-center">

                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 shrink-0">
                    <img src="{{ asset('images/kadas-icon.svg') }}" alt="Kadas" class="w-9 h-9 object-contain drop-shadow-sm">
                    <span class="font-bold text-slate-900 text-sm md:text-base tracking-tight">Absensi Sekolah</span>
                </a>

                <!-- MENU DESKTOP -->
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}"
                       class="px-3 py-2 text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('attendance.index') }}"
                       class="px-3 py-2 text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('attendance.index') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        Absensi
                    </a>
                    <a href="{{ route('attendance.history') }}"
                       class="px-3 py-2 text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('attendance.history') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        Riwayat
                    </a>

                    @if(auth()->user()->role !== 'admin')
                        <a href="{{ route('izin.index') }}"
                           class="px-3 py-2 text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('izin.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            Izin
                        </a>
                    @else
                        <a href="{{ route('admin.izin.index') }}"
                           class="px-3 py-2 text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('admin.izin.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            Kelola Izin
                        </a>
                    @endif

                    <a href="{{ route('profile.edit') }}"
                       class="px-3 py-2 text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('profile.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        Profil
                    </a>

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.users.index') }}"
                           class="px-3 py-2 text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            User
                        </a>
                        <a href="{{ route('admin.settings.index') }}"
                           class="px-3 py-2 text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            Lokasi
                        </a>
                    @endif
                </div>

                <!-- USER AREA + HAMBURGER -->
                           <div class="flex items-center gap-2">
                
                <!-- Tombol Dark Mode -->
                <button id="theme-toggle" type="button" class="text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-white transition-colors mr-2">
                    <!-- Ikon Bulan (Tampil saat terang) -->
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <!-- Ikon Matahari (Tampil saat gelap) -->
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                </button>

                <!-- User (desktop) -->
                <div class="hidden md:flex items-center gap-3 pl-3 border-l border-slate-200 dark:border-slate-700">
                    <div class="text-right">
                        <div class="text-sm font-semibold text-slate-900 leading-tight">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-slate-500">{{ ucfirst(auth()->user()->role) }}</div>
                    </div>
                        <img src="{{ auth()->user()->foto_profil ? Storage::url(auth()->user()->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&size=80&background=4f46e5&color=fff&bold=true' }}"
                             class="w-9 h-9 rounded-full object-cover border-2 border-indigo-100 shadow-sm"
                             alt="{{ auth()->user()->name }}">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800 active:scale-95 font-medium transition">
                                Logout
                            </button>
                        </form>
                    </div>

                    <!-- Tombol Hamburger (mobile) -->
                    <button @click="open = true"
                            class="md:hidden w-10 h-10 flex items-center justify-center rounded-xl hover:bg-slate-100 active:scale-95 transition"
                            aria-label="Buka menu">
                        <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- ====== DRAWER MOBILE (di luar nav) ====== -->
    <!-- Overlay -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false"
         class="md:hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[60]"
         style="display: none;"></div>

    <!-- Drawer Panel -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="md:hidden fixed top-0 right-0 h-screen w-80 max-w-[85vw] bg-white shadow-2xl z-[70] overflow-y-auto"
         style="display: none;">

        <!-- Drawer Header -->
        <div class="bg-gradient-to-br from-indigo-600 via-blue-600 to-indigo-700 text-white p-5">
            <div class="flex justify-between items-start mb-4">
                <div class="text-xs font-semibold uppercase tracking-wider opacity-80">Menu</div>
                <button @click="open = false"
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/20 active:scale-90 transition"
                        aria-label="Tutup menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- User Info -->
            <div class="flex items-center gap-3">
                <img src="{{ auth()->user()->foto_profil ? Storage::url(auth()->user()->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&size=100&background=ffffff&color=4f46e5&bold=true' }}"
                     class="w-12 h-12 rounded-full object-cover border-2 border-white/30"
                     alt="{{ auth()->user()->name }}">
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-sm truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs opacity-90 font-mono">{{ auth()->user()->nis }}</div>
                    <div class="text-xs opacity-80 mt-0.5">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
            </div>
        </div>

        <!-- Menu Items -->
        <div class="p-3 space-y-1">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-3 rounded-xl transition {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="text-sm">Dashboard</span>
            </a>

            <a href="{{ route('attendance.index') }}"
               class="flex items-center gap-3 px-3 py-3 rounded-xl transition {{ request()->routeIs('attendance.index') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="text-sm">Absensi</span>
            </a>

            <a href="{{ route('attendance.history') }}"
               class="flex items-center gap-3 px-3 py-3 rounded-xl transition {{ request()->routeIs('attendance.history') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span class="text-sm">Riwayat</span>
            </a>

            @if(auth()->user()->role !== 'admin')
                <a href="{{ route('izin.index') }}"
                   class="flex items-center gap-3 px-3 py-3 rounded-xl transition {{ request()->routeIs('izin.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="text-sm">Pengajuan Izin</span>
                </a>
            @endif

            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-3 px-3 py-3 rounded-xl transition {{ request()->routeIs('profile.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-sm">Profil Saya</span>
            </a>

            @if(auth()->user()->role === 'admin')
                <div class="pt-3 mt-3 border-t border-slate-100">
                    <div class="px-3 py-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">Admin Menu</div>

                    <a href="{{ route('admin.izin.index') }}"
                       class="flex items-center gap-3 px-3 py-3 rounded-xl transition {{ request()->routeIs('admin.izin.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm">Kelola Izin</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center gap-3 px-3 py-3 rounded-xl transition {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span class="text-sm">Manajemen User</span>
                    </a>

                    <a href="{{ route('admin.settings.index') }}"
                       class="flex items-center gap-3 px-3 py-3 rounded-xl transition {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-sm">Setting Lokasi</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- Logout -->
        <div class="p-3 mt-2 border-t border-slate-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-3 rounded-xl text-red-600 hover:bg-red-50 active:scale-95 transition font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="text-sm">Logout</span>
                </button>
            </form>
        </div>
    </div>

</div>