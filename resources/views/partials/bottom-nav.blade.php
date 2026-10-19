<!-- ============ BOTTOM NAVIGATION (MOBILE ONLY) ============ -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white/90 dark:bg-slate-900/90 backdrop-blur-lg border-t border-slate-200 dark:border-slate-800 z-50 pb-safe shadow-[0_-5px_20px_-5px_rgba(0,0,0,0.05)]">
    <div class="flex justify-around items-center h-[65px] px-2">
        
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('dashboard') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400' }} transition-colors">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('dashboard') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-[10px] font-semibold">Dashboard</span>
        </a>

        <!-- Absensi -->
        <a href="{{ route('attendance.index') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('attendance.index') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400' }} transition-colors">
            <div class="relative">
                <svg class="w-6 h-6" fill="{{ request()->routeIs('attendance.index') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <!-- Badge Merah (Opsional) -->
               @if(empty($todayAttendance))
                <span class="absolute -top-1 -right-1 flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 border-2 border-white dark:border-slate-900"></span>
                </span>
                @endif
            </div>
            <span class="text-[10px] font-semibold">Absensi</span>
        </a>

        <!-- Riwayat -->
        <a href="{{ route('attendance.history') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('attendance.history') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400' }} transition-colors">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('attendance.history') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="text-[10px] font-semibold">Riwayat</span>
        </a>

        <!-- Izin -->
        @if(auth()->user()->role !== 'admin')
        <a href="{{ route('izin.index') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('izin.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400' }} transition-colors">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('izin.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="text-[10px] font-semibold">Izin</span>
        </a>
        @else
        <a href="{{ route('admin.izin.index') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('admin.izin.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400' }} transition-colors">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('admin.izin.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-[10px] font-semibold">Kelola Izin</span>
        </a>
        @endif

        <!-- Profil -->
        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('profile.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400' }} transition-colors">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('profile.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="text-[10px] font-semibold">Profil</span>
        </a>
    </div>
</nav>