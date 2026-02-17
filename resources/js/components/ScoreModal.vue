<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  show: Boolean,
  regu: { type: Object, default: null },
  mataLomba: { type: Array, default: () => [] },
});

const emit = defineEmits(['close']);

const form = useForm({
  regu_profile_id: null,
  mata_lomba_id: '',
  nilai: '',
  catatan: '',
});

const currentScores = ref({}); // mata_lomba_id -> { nilai, catatan }

watch(
  () => props.regu,
  (r) => {
    if (!r) return;
    form.regu_profile_id = r.id;
    form.mata_lomba_id = props.mataLomba[0]?.id?.toString() || '';
    form.nilai = '';
    form.catatan = '';
    currentScores.value = {};
  },
  { immediate: true }
);

watch(
  () => form.mata_lomba_id,
  (id) => {
    const c = currentScores.value[id];
    if (c) {
      form.nilai = c.nilai;
      form.catatan = c.catatan || '';
    }
  }
);

const submit = () => {
  const num = parseFloat(form.nilai);
  if (num < 0 || num > 100) return;
  form.post('/juri/scores', {
    preserveScroll: true,
    onSuccess: () => {
      currentScores.value[form.mata_lomba_id] = { nilai: form.nilai, catatan: form.catatan };
    },
  });
};

const close = () => emit('close');
</script>

<template>
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" @click="close"></div>
        <div
          class="relative w-full max-w-md rounded-2xl border border-scout-tan/40 bg-white p-6 shadow-xl"
          role="dialog"
          aria-modal="true"
        >
          <div class="flex items-center justify-between border-b border-scout-tan/30 pb-4">
            <h3 class="text-lg font-semibold text-scout-brown">
              Beri Nilai – {{ regu?.nama_regu }}
            </h3>
            <button
              type="button"
              class="rounded-lg p-1 text-scout-brown/70 hover:bg-scout-tan/20 hover:text-scout-brown"
              @click="close"
            >
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <form @submit.prevent="submit" class="mt-4 space-y-4">
            <div>
              <label class="block text-sm font-medium text-scout-brown">Mata Lomba</label>
              <select
                v-model="form.mata_lomba_id"
                required
                class="mt-1 block w-full rounded-lg border border-scout-tan/60 bg-white px-4 py-2.5 text-scout-brown focus:border-scout-brown focus:ring-2 focus:ring-scout-brown/20"
              >
                <option v-for="m in mataLomba" :key="m.id" :value="String(m.id)">{{ m.nama }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-scout-brown">Nilai (0–100)</label>
              <input
                v-model="form.nilai"
                type="number"
                min="0"
                max="100"
                step="0.01"
                required
                class="mt-1 block w-full rounded-lg border border-scout-tan/60 bg-white px-4 py-2.5 text-scout-brown focus:border-scout-brown focus:ring-2 focus:ring-scout-brown/20"
                placeholder="0"
              />
              <p v-if="form.errors.nilai" class="mt-1 text-sm text-red-600">{{ form.errors.nilai }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-scout-brown">Catatan (opsional)</label>
              <input
                v-model="form.catatan"
                type="text"
                maxlength="500"
                class="mt-1 block w-full rounded-lg border border-scout-tan/60 bg-white px-4 py-2.5 text-scout-brown focus:border-scout-brown focus:ring-2 focus:ring-scout-brown/20"
                placeholder="Catatan untuk regu"
              />
            </div>
            <div class="flex gap-3 pt-2">
              <button
                type="button"
                class="flex-1 rounded-lg border border-scout-tan/60 py-2.5 font-medium text-scout-brown hover:bg-scout-tan/20"
                @click="close"
              >
                Batal
              </button>
              <button
                type="submit"
                :disabled="form.processing"
                class="flex-1 rounded-lg bg-scout-brown py-2.5 font-semibold text-white transition hover:bg-scout-brown/90 disabled:opacity-70"
              >
                <span v-if="form.processing">Menyimpan...</span>
                <span v-else>Simpan Nilai</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.2s ease;
}
.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}
.modal-enter-active .relative,
.modal-leave-active .relative {
  transition: transform 0.2s ease;
}
.modal-enter-from .relative,
.modal-leave-to .relative {
  transform: scale(0.95);
}
</style>
