@props([
    'title' => '',
    'subtitle' => '',
    'showUser' => false,
])

<div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 via-blue-600 to-indigo-700 p-6 md:p-8 shadow-xl shadow-indigo-500/10 mb-6">
    <!-- Background foto gedung sekolah (subtle) -->
    <div class="absolute inset-0 opacity-[0.08] bg-cover bg-center"
         style="background-image: url('{{ asset('images/hero-sekolah.jpg') }}');"></div>

    <!-- Dekorasi blob -->
    <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-24 -left-16 w-72 h-72 bg-violet-400/20 rounded-full blur-3xl"></div>

    <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            @if($subtitle)
                <p class="text-xs font-semibold uppercase tracking-widest text-blue-200 mb-1">
                    {{ $subtitle }}
                </p>
            @endif
            <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">
                {{ $title }}
            </h1>
        </div>

        @if($showUser && auth()->check())
            @php
                $roleColor = match(auth()->user()->role) {
                    'admin' => 'bg-red-500/30 text-red-100 ring-red-400/40',
                    'guru'  => 'bg-emerald-500/30 text-emerald-100 ring-emerald-400/40',
                    default => 'bg-blue-500/30 text-blue-100 ring-blue-400/40',
                };
            @endphp
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-3 py-1.5 {{ $roleColor }} ring-1 rounded-full text-xs font-bold uppercase tracking-wider backdrop-blur-sm">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            </div>
        @endif
    </div>
</div>