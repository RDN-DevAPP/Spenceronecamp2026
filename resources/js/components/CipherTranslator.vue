<template>
  <div class="bg-white rounded-2xl border border-emerald-200/80 shadow-sm overflow-hidden">
    <!-- Header -->
    <div style="background: linear-gradient(135deg, #1B4332 0%, #2D6A4F 100%);" class="px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-400/20 border border-amber-300/40 flex items-center justify-center text-amber-300 shrink-0">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
          </svg>
        </div>
        <div>
          <h3 class="text-sm sm:text-base font-black text-white uppercase tracking-wide">
            Penerjemah Sandi (Cipher Translator)
          </h3>
          <p class="text-[11px] text-emerald-100/80">
            Konversi real-time teks biasa ke Sandi Kotak 1, Sandi Kotak 2, &amp; Sandi Angka
          </p>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <button
          type="button"
          @click="showPlainLabels = !showPlainLabels"
          class="px-3 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer border"
          :class="showPlainLabels ? 'bg-amber-400 text-emerald-950 border-amber-300' : 'bg-white/10 text-white border-white/20 hover:bg-white/20'"
        >
          {{ showPlainLabels ? 'Sembunyikan Huruf' : 'Tampilkan Huruf' }}
        </button>
      </div>
    </div>

    <div class="p-5 space-y-5">
      <!-- Input Box -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <div class="md:col-span-2">
          <label class="block text-xs font-black uppercase tracking-wider text-gray-600 mb-1.5">
            Ketik Teks Biasa (A-Z)
          </label>
          <input
            v-model="plainText"
            type="text"
            maxlength="80"
            placeholder="Contoh: SPENCERONE CAMP"
            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-[#1B4332] focus:ring-2 focus:ring-emerald-500/20 text-sm font-bold uppercase tracking-wider transition"
          />
        </div>
        <div>
          <label class="block text-xs font-black uppercase tracking-wider text-gray-600 mb-1.5">
            Decode Sandi Angka (Opsional)
          </label>
          <div class="flex gap-2">
            <input
              v-model="angkaInput"
              type="text"
              placeholder="16-18-1-13-21-11-1"
              class="w-full px-3 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-amber-500 text-xs font-mono font-bold"
            />
            <button
              type="button"
              @click="applyDecodeAngka"
              class="px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-black rounded-xl shrink-0 cursor-pointer transition"
            >
              Decode
            </button>
          </div>
        </div>
      </div>

      <!-- Mode Tabs -->
      <div class="flex flex-wrap gap-2 border-b border-gray-100 pb-3">
        <button
          v-for="tab in tabs"
          :key="tab.id"
          type="button"
          @click="selectedMode = tab.id"
          class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer"
          :class="selectedMode === tab.id ? 'bg-[#1B4332] text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
        >
          {{ tab.label }}
        </button>
      </div>

      <!-- Visual Output -->
      <div v-if="!plainText.trim()" class="py-8 text-center text-gray-400 text-xs font-semibold border-2 border-dashed border-gray-200 rounded-xl">
        Ketikkan teks pada kolom di atas untuk melihat visualisasi sandi secara langsung.
      </div>

      <div v-else class="space-y-4">
        <!-- Sandi Kotak 1 -->
        <div
          v-if="selectedMode === 'all' || selectedMode === 'kotak-1'"
          class="p-4 rounded-xl bg-[#F4EDE4]/50 border border-amber-200/80"
        >
          <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-black uppercase tracking-wider text-[#1B4332] flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
              Sandi Kotak 1
            </span>
            <span class="text-[10px] font-bold text-gray-500 bg-white px-2 py-0.5 rounded border border-gray-200">
              Pagar (#) A-R &amp; Silang (X) S-Z
            </span>
          </div>
          <div class="flex flex-wrap gap-1.5 items-center">
            <div
              v-for="tok in tokensKotak1"
              :key="`k1-${tok.index}`"
              class="bg-white border border-gray-200 rounded-lg p-1 shadow-2xs flex flex-col items-center"
              :class="tok.isSpace ? 'w-5 bg-transparent border-dashed border-gray-300' : ''"
            >
              <CipherCharacter type="kotak-1" :char="tok.char" size="md" :show-label="showPlainLabels" />
            </div>
          </div>
        </div>

        <!-- Sandi Kotak 2 -->
        <div
          v-if="selectedMode === 'all' || selectedMode === 'kotak-2'"
          class="p-4 rounded-xl bg-blue-50/50 border border-blue-200/80"
        >
          <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-black uppercase tracking-wider text-blue-900 flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-blue-600"></span>
              Sandi Kotak 2
            </span>
            <span class="text-[10px] font-bold text-gray-500 bg-white px-2 py-0.5 rounded border border-gray-200">
              Pagar (#) 3 Huruf/Kotak (0, 1, 2 Titik)
            </span>
          </div>
          <div class="flex flex-wrap gap-1.5 items-center">
            <div
              v-for="tok in tokensKotak2"
              :key="`k2-${tok.index}`"
              class="bg-white border border-blue-100 rounded-lg p-1 shadow-2xs flex flex-col items-center"
              :class="tok.isSpace ? 'w-5 bg-transparent border-dashed border-blue-200' : ''"
            >
              <CipherCharacter type="kotak-2" :char="tok.char" size="md" :show-label="showPlainLabels" />
            </div>
          </div>
        </div>

        <!-- Sandi Angka -->
        <div
          v-if="selectedMode === 'all' || selectedMode === 'angka'"
          class="p-4 rounded-xl bg-amber-50/60 border border-amber-200"
        >
          <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
            <span class="text-xs font-black uppercase tracking-wider text-amber-900 flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-amber-500"></span>
              Sandi Angka (A=1 .. Z=26)
            </span>
            <div class="flex items-center gap-2">
              <code class="text-xs font-mono font-black bg-white px-2.5 py-1 rounded-lg border border-amber-300 text-amber-900">
                {{ encodedAngkaText }}
              </code>
              <button
                type="button"
                @click="copyAngka"
                class="px-2.5 py-1 rounded-lg bg-amber-500 hover:bg-amber-600 text-white text-[11px] font-bold cursor-pointer transition"
              >
                {{ copied ? 'Tersalin!' : 'Salin' }}
              </button>
            </div>
          </div>
          <div class="flex flex-wrap gap-1.5 items-center">
            <div
              v-for="tok in tokensAngka"
              :key="`ka-${tok.index}`"
              class="bg-white border border-amber-200 rounded-lg p-1 shadow-2xs flex flex-col items-center"
              :class="tok.isSpace ? 'w-5 bg-transparent border-dashed border-amber-300' : ''"
            >
              <CipherCharacter type="angka" :char="tok.char" size="md" :show-label="showPlainLabels" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import CipherCharacter from './CipherCharacter.vue';
import {
  translateToCipherTokens,
  encodeAngkaString,
  decodeAngkaString,
} from '../utils/sandiHelper';

const plainText = ref('PRAMUKA SPENCERONE');
const angkaInput = ref('');
const selectedMode = ref('all');
const showPlainLabels = ref(true);
const copied = ref(false);

const tabs = [
  { id: 'all', label: 'Semua Sandi (3-in-1)' },
  { id: 'kotak-1', label: 'Sandi Kotak 1' },
  { id: 'kotak-2', label: 'Sandi Kotak 2' },
  { id: 'angka', label: 'Sandi Angka' },
];

const tokensKotak1 = computed(() => translateToCipherTokens('kotak-1', plainText.value));
const tokensKotak2 = computed(() => translateToCipherTokens('kotak-2', plainText.value));
const tokensAngka = computed(() => translateToCipherTokens('angka', plainText.value));
const encodedAngkaText = computed(() => encodeAngkaString(plainText.value));

function applyDecodeAngka() {
  if (!angkaInput.value.trim()) return;
  plainText.value = decodeAngkaString(angkaInput.value);
}

async function copyAngka() {
  if (!encodedAngkaText.value) return;
  try {
    await navigator.clipboard.writeText(encodedAngkaText.value);
    copied.value = true;
    setTimeout(() => {
      copied.value = false;
    }, 1500);
  } catch {
    // Fallback ignored
  }
}
</script>
