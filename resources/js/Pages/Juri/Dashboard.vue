<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import DashboardLayout from '@/Pages/Layouts/DashboardLayout.vue';
import ScoreModal from '@/components/ScoreModal.vue';

const page = usePage();
const flash = computed(() => page.props.flash);
const props = defineProps({
  mataLomba: { type: Array, default: () => [] },
  regu: { type: Array, default: () => [] },
});

const modalOpen = ref(false);
const selectedRegu = ref(null);

const openModal = (r) => {
  selectedRegu.value = r;
  modalOpen.value = true;
};
const closeModal = () => {
  modalOpen.value = false;
  selectedRegu.value = null;
};

const reguPutra = computed(() => props.regu.filter((r) => r.jenis === 'putra'));
const reguPutri = computed(() => props.regu.filter((r) => r.jenis === 'putri'));
</script>

<template>
  <DashboardLayout>
    <div class="space-y-6">
      <div>
        <h1 class="text-xl font-bold text-scout-brown">Penilaian LT-I</h1>
        <p class="mt-1 text-sm text-scout-brown/70">Beri nilai per mata lomba untuk setiap regu (0–100). Mobile-friendly untuk penggunaan di lapangan.</p>
      </div>

      <div v-if="flash?.success" class="rounded-lg bg-green-50 p-4 text-sm text-green-800">
        {{ flash.success }}
      </div>

      <!-- List Regu - 4 Putra -->
      <section>
        <h2 class="mb-3 text-lg font-semibold text-scout-brown">Regu Putra</h2>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
          <div
            v-for="r in reguPutra"
            :key="r.id"
            class="rounded-xl border-2 border-scout-tan/40 bg-white p-4 shadow-sm transition hover:border-scout-brown/40 hover:shadow"
          >
            <div class="flex items-center justify-between gap-2">
              <div>
                <h3 class="font-semibold text-scout-brown">{{ r.nama_regu }}</h3>
                <p class="text-sm text-scout-brown/70">Putra {{ r.nomor_regu }}</p>
              </div>
              <button
                type="button"
                class="rounded-lg bg-scout-brown px-4 py-2 text-sm font-medium text-white hover:bg-scout-brown/90"
                @click="openModal(r)"
              >
                Beri Nilai
              </button>
            </div>
          </div>
        </div>
      </section>

      <!-- List Regu - 4 Putri -->
      <section>
        <h2 class="mb-3 text-lg font-semibold text-scout-brown">Regu Putri</h2>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
          <div
            v-for="r in reguPutri"
            :key="r.id"
            class="rounded-xl border-2 border-scout-tan/40 bg-white p-4 shadow-sm transition hover:border-scout-brown/40 hover:shadow"
          >
            <div class="flex items-center justify-between gap-2">
              <div>
                <h3 class="font-semibold text-scout-brown">{{ r.nama_regu }}</h3>
                <p class="text-sm text-scout-brown/70">Putri {{ r.nomor_regu }}</p>
              </div>
              <button
                type="button"
                class="rounded-lg bg-scout-brown px-4 py-2 text-sm font-medium text-white hover:bg-scout-brown/90"
                @click="openModal(r)"
              >
                Beri Nilai
              </button>
            </div>
          </div>
        </div>
      </section>
    </div>

    <ScoreModal
      :show="modalOpen"
      :regu="selectedRegu"
      :mata-lomba="mataLomba"
      @close="closeModal"
    />
  </DashboardLayout>
</template>
