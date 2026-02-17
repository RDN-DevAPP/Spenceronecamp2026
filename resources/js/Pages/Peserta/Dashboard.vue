<script setup>
import { computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import DashboardLayout from '@/Pages/Layouts/DashboardLayout.vue';

const page = usePage();
const flash = computed(() => page.props.flash);
const props = defineProps({
  regu: { type: Object, default: null },
  anggota: { type: Array, default: () => [] },
  scores: { type: Array, default: () => [] },
  scoreboard: { type: Array, default: () => [] },
});

const form = useForm({
  poster: null,
});

const uploadPoster = () => {
  form.post('/peserta/poster', {
    forceFormData: true,
    preserveScroll: true,
  });
};

const isCurrentRegu = (id) => props.regu && props.regu.id === id;
</script>

<template>
  <DashboardLayout>
    <div class="space-y-8">
      <div>
        <h1 class="text-xl font-bold text-scout-brown">Dashboard Peserta</h1>
        <p class="mt-1 text-sm text-scout-brown/70">Profil regu, klasemen, dan upload poster digital.</p>
      </div>

      <div v-if="flash?.success" class="rounded-lg bg-green-50 p-4 text-sm text-green-800">
        {{ flash.success }}
      </div>
      <div v-if="flash?.error" class="rounded-lg bg-red-50 p-4 text-sm text-red-800">
        {{ flash.error }}
      </div>

      <template v-if="regu">
        <!-- Profil Regu & Anggota -->
        <section class="rounded-xl border border-scout-tan/40 bg-white p-6 shadow-sm">
          <h2 class="text-lg font-semibold text-scout-brown">Profil Regu – {{ regu.nama_regu }}</h2>
          <p class="mt-1 text-sm text-scout-brown/70">{{ regu.jenis === 'putra' ? 'Putra' : 'Putri' }} {{ regu.nomor_regu }}</p>
          <h3 class="mt-4 font-medium text-scout-brown">Daftar Anggota (8–10 orang)</h3>
          <div class="mt-3 overflow-x-auto">
            <table class="w-full min-w-[280px] text-left text-sm">
              <thead>
                <tr class="border-b border-scout-tan/40">
                  <th class="pb-2 font-medium text-scout-brown">No</th>
                  <th class="pb-2 font-medium text-scout-brown">Nama</th>
                  <th class="pb-2 font-medium text-scout-brown">No. Punggung</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(a, i) in anggota" :key="a.id" class="border-b border-scout-tan/20">
                  <td class="py-2">{{ i + 1 }}</td>
                  <td class="py-2">{{ a.nama }}</td>
                  <td class="py-2">{{ a.nomor_punggung ?? '-' }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <p v-if="!anggota.length" class="mt-2 text-scout-brown/70">Belum ada data anggota. Hubungi panitia.</p>
        </section>

        <!-- Live Scoreboard -->
        <section class="rounded-xl border border-scout-tan/40 bg-white p-6 shadow-sm">
          <h2 class="text-lg font-semibold text-scout-brown">Klasemen Sementara</h2>
          <div class="mt-4 overflow-x-auto">
            <table class="w-full min-w-[280px] text-left text-sm">
              <thead>
                <tr class="border-b border-scout-tan/40">
                  <th class="pb-2 font-medium text-scout-brown">Posisi</th>
                  <th class="pb-2 font-medium text-scout-brown">Regu</th>
                  <th class="pb-2 font-medium text-scout-brown">Jenis</th>
                  <th class="pb-2 font-medium text-scout-brown">Total Nilai</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(s, i) in scoreboard"
                  :key="s.id"
                  class="border-b border-scout-tan/20"
                  :class="{ 'bg-scout-gold/15 font-medium': isCurrentRegu(s.id) }"
                >
                  <td class="py-2">{{ i + 1 }}</td>
                  <td class="py-2">{{ s.nama_regu }}</td>
                  <td class="py-2">{{ s.jenis }} {{ s.nomor_regu }}</td>
                  <td class="py-2">{{ Number(s.total_nilai).toFixed(2) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

        <!-- Upload Poster Digital -->
        <section class="rounded-xl border border-scout-tan/40 bg-white p-6 shadow-sm">
          <h2 class="text-lg font-semibold text-scout-brown">Upload Desain Poster Digital</h2>
          <p class="mt-1 text-sm text-scout-brown/70">Format: JPG, PNG, atau PDF. Maks. 5 MB.</p>
          <form @submit.prevent="uploadPoster" class="mt-4 flex flex-wrap items-end gap-4">
            <input
              type="file"
              accept=".jpg,.jpeg,.png,.pdf"
              class="block w-full max-w-xs text-sm text-scout-brown file:mr-4 file:rounded-lg file:border-0 file:bg-scout-brown file:px-4 file:py-2 file:text-white file:hover:bg-scout-brown/90"
              @change="form.poster = $event.target.files?.[0]"
            />
            <button
              type="submit"
              :disabled="!form.poster || form.processing"
              class="rounded-lg bg-scout-brown px-5 py-2.5 font-medium text-white hover:bg-scout-brown/90 disabled:opacity-60"
            >
              <span v-if="form.processing">Mengunggah...</span>
              <span v-else>Unggah</span>
            </button>
          </form>
          <p v-if="regu.poster_digital_path" class="mt-2 text-sm text-green-700">File poster sudah diunggah.</p>
          <p v-if="form.errors.poster" class="mt-2 text-sm text-red-600">{{ form.errors.poster }}</p>
        </section>
      </template>

      <div v-else class="rounded-xl border border-scout-tan/40 bg-white p-6 text-center text-scout-brown/80">
        Profil regu tidak ditemukan. Hubungi panitia.
      </div>
    </div>
  </DashboardLayout>
</template>
