{{-- ============ FLOATING MASCOT WITH LOTTIE ============ --}}
<div id="mascot-container" class="fixed bottom-6 right-4 md:right-6 z-[55] flex items-end gap-2 select-none"
     x-data="mascot()"
     x-init="init()">

    <!-- Bubble Chat -->
    <div x-show="showBubble"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 scale-90 translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-90"
         @click="hideBubble()"
         class="relative bg-white rounded-2xl shadow-2xl border border-slate-100 p-3 max-w-[220px] md:max-w-[260px] cursor-pointer hover:shadow-xl transition-shadow"
         style="display: none;">

        <!-- Bubble tail -->
        <div class="absolute right-0 top-1/2 translate-x-1/2 -translate-y-1/2 w-3 h-3 bg-white border-r border-t border-slate-100 rotate-45"></div>

        <!-- Text -->
        <p class="text-sm font-medium text-slate-700 leading-snug relative z-10"
           x-text="message"></p>

        <!-- Close hint -->
        <div class="text-[10px] text-slate-400 mt-1 text-right relative z-10">klik untuk tutup</div>
    </div>

    <!-- Mascot Lottie -->
    <button type="button"
            @click="onMascotClick()"
            @mouseenter="hovering = true"
            @mouseleave="hovering = false"
            :class="hovering ? 'scale-110' : 'scale-100'"
            class="w-20 h-20 md:w-24 md:h-24 transition-all duration-300 ease-out active:scale-95 cursor-pointer"
            style="filter: drop-shadow(0 10px 20px rgba(79, 70, 229, 0.3));">

        <!-- Container Lottie -->
        <div id="lottie-mascot" class="w-full h-full"></div>
    </button>

    <!-- Pulse ring (muncul kalau bubble hidden) -->
    <div x-show="!showBubble"
         x-transition:enter="transition ease-out duration-500 delay-2000"
         x-transition:enter-start="opacity-0 scale-50"
         x-transition:enter-end="opacity-100 scale-100"
         class="absolute bottom-0 right-0 w-20 h-20 md:w-24 md:h-24 rounded-full bg-yellow-400/30 animate-ping pointer-events-none"
         style="display: none; animation-duration: 3s;"></div>
</div>

{{-- Lottie Web Library --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>

@push('scripts')
<script>
    // ============ LOAD LOTTIE ANIMATION ============
   document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('lottie-mascot');
    if (container && typeof lottie !== 'undefined') {
        window.mascotAnimation = lottie.loadAnimation({
            container: container,
            renderer: 'svg',
            loop: false,        // ← Tidak loop
            autoplay: false,    // ← Tidak auto-play
            path: '{{ asset("lottie/mascot.json") }}'
        });

      // Setelah animasi dimuat, auto-play 1x (melambai)
window.mascotAnimation.addEventListener('DOMLoaded', function () {
    setTimeout(() => {
        window.mascotAnimation.goToAndPlay(0, true);
    }, 500); // Delay sedikit biar halaman selesai render
});

        // Setelah animasi selesai diputar (sekali), balik diam
        window.mascotAnimation.addEventListener('complete', function () {
            setTimeout(() => {
                window.mascotAnimation.goToAndStop(0, true);
            }, 800);
        });
    }
});

    // ============ MASCOT ALPINE COMPONENT ============
    document.addEventListener('alpine:init', () => {
        Alpine.data('mascot', () => ({
            showBubble: false,
            message: '',
            hovering: false,
            messageIndex: 0,
            _hideTimeout: null,

            messages: [
                'Tetap semangat belajar ya! 💪',
                'Jangan lupa absen hari ini! 📸',
                'Adududuh... jangan lupa istirahat 🥺',
                'Kamu keren hari ini! ✨',
                'Semangat! Kamu pasti bisa! 🚀',
                'Jangan lupa minum air ya 💧',
                'Istirahat yang cukup ya! 😴',
                'Rajin pangkal pandai! 📚',
                'Tetap jaga kesehatan! 💊',
                'SMKN 1 Pedan jaya! 🏫',
                'Ayo rajin absen biar nilai bagus! ✨',
                'Semangat, calon profesional! 🌾',
                'Jangan lupa bahagia hari ini 😊',
                'Sekolah itu menyenangkan! 🏫',
                'Jangan lupa sholat ya 🕌',
            ],

           init() {
    // Welcome bubble setelah 1 detik
    setTimeout(() => {
        const nama = '{{ auth()->check() ? explode(" ", auth()->user()->name)[0] : "" }}';
        this.message = nama ? `Halo, ${nama}! Selamat datang 👋` : 'Halo! Selamat datang 👋';
        this.showBubble = true;

        setTimeout(() => { this.showBubble = false; }, 5000);
    }, 1000);

    // ============ AUTO-MELAMBAI SETIAP 30 DETIK ============
    setInterval(() => {
        // Play animasi Lottie (melambai)
        if (window.mascotAnimation) {
            window.mascotAnimation.goToAndPlay(0, true);
        }
    }, 30000); // 30.000 ms = 30 detik
},

            onMascotClick() {
                // Pesan random (tidak mengulang)
                let newIndex;
                do {
                    newIndex = Math.floor(Math.random() * this.messages.length);
                } while (newIndex === this.messageIndex && this.messages.length > 1);

                this.messageIndex = newIndex;
                this.message = this.messages[newIndex];
                this.showBubble = true;

                // Play bounce animation
                this.playBounce();

                // Auto hide
                clearTimeout(this._hideTimeout);
                this._hideTimeout = setTimeout(() => { this.showBubble = false; }, 4000);
            },

            hideBubble() {
                this.showBubble = false;
                clearTimeout(this._hideTimeout);
            },

           playBounce() {
    // Play animasi Lottie dari awal
    if (window.mascotAnimation) {
        window.mascotAnimation.goToAndPlay(0, true);
    }

    // Bonus: efek scale kecil
    const el = document.getElementById('lottie-mascot');
    if (el) {
        el.style.transform = 'scale(1.1)';
        setTimeout(() => { el.style.transform = 'scale(1)'; }, 200);
    }
},
        }));
    });
</script>

<style>
    #lottie-mascot {
        transition: transform 0.2s ease-out;
    }

    /* Supaya SVG dari Lottie tidak overflow */
    #lottie-mascot svg {
        width: 100% !important;
        height: 100% !important;
    }
</style>
@endpush