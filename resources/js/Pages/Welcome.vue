<script setup>
import { Link } from '@inertiajs/vue3';
import Countdown from '@/components/Countdown.vue';
import MataLombaCard from '@/components/MataLombaCard.vue';

defineProps({
  mataLomba: {
    type: Array,
    default: () => [],
  },
});

const timeline = [
  { day: 'Hari ke-1 (24 April 2026)', items: ['Mendirikan Tenda', 'Upacara Pembukaan (13:30 WITA)', 'LKBB Tongkat', 'Tapak Kemah'] },
  { day: 'Hari ke-2 (25 April 2026)', items: ['Lomba Masak Konvensional', 'Upcycle Art', 'Cerdas Cermat', 'Desain Poster Digital', 'Anjangsana', 'Upacara Penutupan'] },
];
</script>

<template>
  <div class="min-h-screen bg-scout-cream">
    <!-- Hero -->
    <header class="relative overflow-hidden bg-scout-brown px-4 py-16 sm:py-24 text-scout-cream">
      <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cpath fill=\'%23ffffff\' fill-opacity=\'0.05\' d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/svg%3E')] opacity-80"></div>
      <div class="relative mx-auto max-w-4xl text-center">
        <p class="text-sm font-medium uppercase tracking-widest text-scout-tan">Gugus Depan 03.059 - 03.060</p>
        <h1 class="mt-4 text-3xl font-bold tracking-tight sm:text-4xl md:text-5xl">
          Lomba Tingkat I (LT-I)
        </h1>
        <p class="mt-2 text-xl font-medium text-scout-gold">Spencerone Camp 2026</p>
        <p class="mt-4 text-lg text-scout-cream/90">
          "Scout for Future: Cerdas, Tangkas, dan Berkarakter di Era Digital"
        </p>
        <p class="mt-2 text-sm text-scout-cream/70">Lingkungan SMP Negeri 1 Cerbon • 24–25 April 2026</p>
        <div class="mt-8 flex flex-wrap justify-center gap-4">
          <Link
            href="/login"
            class="inline-flex items-center rounded-lg bg-scout-gold px-6 py-3 font-semibold text-scout-brown shadow-lg transition hover:bg-scout-tan"
          >
            Login Sistem
          </Link>
          <Link
            href="/informasi-lomba"
            class="inline-flex items-center rounded-lg border-2 border-scout-cream/50 px-6 py-3 font-semibold text-scout-cream transition hover:bg-scout-cream/10"
          >
            Informasi Ketentuan Lomba
          </Link>
          <a
            href="#"
            class="inline-flex items-center rounded-lg border-2 border-scout-cream/50 px-6 py-3 font-semibold text-scout-cream transition hover:bg-scout-cream/10"
          >
            Unduh Juknis (PDF)
          </a>
        </div>
      </div>
    </header>

    <!-- Countdown -->
    <section class="px-4 py-12 sm:py-16">
      <div class="mx-auto max-w-2xl text-center">
        <h2 class="text-xl font-semibold text-scout-brown">Hitung Mundur</h2>
        <div class="mt-6">
          <Countdown />
        </div>
      </div>
    </section>

    <!-- Mata Lomba Grid -->
    <section class="border-t border-scout-tan/30 bg-white px-4 py-12 sm:py-16">
      <div class="mx-auto max-w-6xl">
        <h2 class="text-center text-2xl font-bold text-scout-brown">Mata Lomba</h2>
        <p class="mt-2 text-center text-scout-brown/70">Enam bidang kompetisi LT-I Spencerone Camp 2026</p>
        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
          <MataLombaCard
            v-for="m in mataLomba"
            :key="m.id"
            :title="m.nama"
            :slug="m.slug"
          />
        </div>
      </div>
    </section>

    <!-- Timeline -->
    <section class="border-t border-scout-tan/30 px-4 py-12 sm:py-16">
      <div class="mx-auto max-w-4xl">
        <h2 class="text-center text-2xl font-bold text-scout-brown">Jadwal Kegiatan</h2>
        <p class="mt-2 text-center text-scout-brown/70">Timeline dua hari LT-I</p>
        <div class="mt-8 space-y-8">
          <div
            v-for="(block, i) in timeline"
            :key="i"
            class="rounded-xl border-2 border-scout-tan/40 bg-white p-6 shadow-sm"
          >
            <h3 class="text-lg font-semibold text-scout-brown">{{ block.day }}</h3>
            <ul class="mt-4 space-y-2">
              <li
                v-for="(item, j) in block.items"
                :key="j"
                class="flex items-center gap-2 text-scout-brown/90"
              >
                <span class="h-2 w-2 rounded-full bg-scout-gold"></span>
                {{ item }}
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-scout-tan/30 bg-scout-brown px-4 py-10 text-scout-cream">
      <div class="mx-auto max-w-4xl text-center">
        <p class="font-semibold">Gugus Depan 03.059 - 03.060</p>
        <p class="mt-1">SMP Negeri 1 Cerbon</p>
        <p class="mt-4 text-sm text-scout-cream/80">Lomba Tingkat I (LT-I) Spencerone Camp 2026</p>
        <p class="mt-2 text-sm text-scout-cream/60">Scout for Future: Cerdas, Tangkas, dan Berkarakter di Era Digital</p>
      </div>
    </footer>
  </div>
</template>
