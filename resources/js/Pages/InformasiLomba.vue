<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const openId = ref(null);
const toggle = (id) => {
  openId.value = openId.value === id ? null : id;
};

const items = [
  {
    id: 'tapak-kemah',
    title: 'Tapak Kemah (Manajemen Perkemahan)',
    fokus: 'Menilai kerapian, kekokohan, dan kebersihan area perkemahan dengan prinsip Zero Waste.',
    unsur: [
      'Teknik pendirian tenda (kekokohan, kerapian)',
      'Tata letak barang (rak sepatu, jemuran, dan penataan peralatan)',
      'Administrasi regu (buku administrasi, dokumentasi)',
    ],
  },
  {
    id: 'masak-konvensional',
    title: 'Masak Konvensional (Kuliner Nusantara)',
    fokus: 'Memasak hidangan khas daerah dengan prinsip Gizi Seimbang (Isi Piringku).',
    unsur: [
      'Cita rasa otentik hidangan khas daerah',
      'Kebersihan proses memasak dan peralatan',
      'Kreativitas penyajian (plating)',
    ],
  },
  {
    id: 'upcycle-art',
    title: 'Upcycle Art (Lukisan dari Limbah)',
    fokus: 'Mengubah limbah anorganik menjadi karya seni lukis 2D (mozaik/kolase).',
    unsur: [
      'Keindahan visual karya',
      'Narasi filosofi terkait isu lingkungan',
    ],
  },
  {
    id: 'lkbb-tongkat',
    title: 'LKBB Tongkat',
    fokus: 'Ketangkasan baris-berbaris menggunakan tongkat pramuka.',
    unsur: [
      'Ketepatan gerakan sesuai aba-aba',
      'Semangat dan kekompakan regu',
      'Variasi formasi dinamis',
    ],
  },
  {
    id: 'cerdas-cermat',
    title: 'Cerdas Cermat (Ranking 1)',
    fokus: 'Adu wawasan kepramukaan dan pengetahuan umum dengan sistem gugur.',
    unsur: [
      'Kecepatan berpikir',
      'Ketepatan logika dan jawaban',
      'Penguasaan materi kepramukaan dan umum',
    ],
  },
  {
    id: 'desain-poster-digital',
    title: 'Desain Poster Digital',
    fokus: 'Media kampanye positif menggunakan smartphone atau laptop.',
    unsur: [
      'Orisinalitas ide',
      'Komposisi warna dan tata letak',
      'Efektivitas pesan visual',
    ],
  },
];
</script>

<template>
  <div class="min-h-screen bg-scout-cream">
    <!-- Header -->
    <header class="border-b border-scout-tan/30 bg-white shadow-sm">
      <div class="mx-auto max-w-4xl px-4 py-6">
        <h1 class="text-2xl font-bold text-scout-brown sm:text-3xl">
          Informasi Ketentuan Lomba
        </h1>
        <p class="mt-1 text-scout-brown/80">
          Aturan teknis keenam mata lomba prestasi LT-I Spencerone Camp 2026
        </p>
      </div>
    </header>

    <main class="mx-auto max-w-4xl px-4 py-8">
      <!-- Actions -->
      <div class="mb-8 flex flex-wrap items-center gap-3">
        <Link
          href="/"
          class="inline-flex items-center gap-2 rounded-lg border border-scout-tan/60 bg-white px-4 py-2.5 text-sm font-medium text-scout-brown shadow-sm transition hover:bg-scout-tan/20"
        >
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Kembali ke Beranda
        </Link>
        <a
          href="#"
          class="inline-flex items-center gap-2 rounded-lg bg-scout-brown px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-scout-brown/90"
        >
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          Download Juknis Lengkap (PDF)
        </a>
      </div>

      <!-- Accordion -->
      <div class="space-y-2">
        <div
          v-for="item in items"
          :key="item.id"
          class="overflow-hidden rounded-xl border border-scout-tan/40 bg-white shadow-sm transition-shadow hover:shadow"
        >
          <button
            type="button"
            class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left font-semibold text-scout-brown transition hover:bg-scout-cream/50"
            :aria-expanded="openId === item.id"
            :aria-controls="`content-${item.id}`"
            :id="`accordion-${item.id}`"
            @click="toggle(item.id)"
          >
            <span class="text-base sm:text-lg">{{ item.title }}</span>
            <span
              class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-scout-brown text-white transition-transform"
              :class="{ 'rotate-180': openId === item.id }"
            >
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </span>
          </button>
          <div
            :id="`content-${item.id}`"
            :aria-labelledby="`accordion-${item.id}`"
            role="region"
            class="border-t border-scout-tan/30 bg-scout-cream/30"
            :class="openId === item.id ? 'block' : 'hidden'"
          >
            <div class="px-5 py-4">
              <!-- Prose-style typography: readable line length & spacing -->
              <div class="prose prose-scout max-w-none">
                <p class="text-base leading-relaxed text-scout-brown/90">
                  <span class="font-semibold text-scout-brown">Fokus:</span> {{ item.fokus }}
                </p>
                <div class="mt-4">
                  <p class="font-semibold text-scout-brown">Unsur Penilaian:</p>
                  <ul class="mt-2 list-inside list-disc space-y-1.5 text-base leading-relaxed text-scout-brown/90">
                    <li v-for="(u, i) in item.unsur" :key="i">{{ u }}</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer note -->
      <p class="mt-8 text-center text-sm text-scout-brown/70">
        Gugus Depan 03.059 - 03.060 SMP Negeri 1 Cerbon • LT-I Spencerone Camp 2026
      </p>
    </main>
  </div>
</template>

<style scoped>
/* Readability: comfortable line length for narrative text */
.prose-scout p,
.prose-scout ul {
  max-width: 65ch;
}
</style>
