<template>
  <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">
    <!-- Top Header -->
    <div style="background: linear-gradient(135deg, #1B4332 0%, #2D6A4F 100%);" class="text-white rounded-2xl p-5 shadow-lg flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <div class="flex items-center gap-2 mb-1">
          <a :href="routes.roadmap" class="text-xs font-bold text-amber-300 hover:underline">&larr; Kembali ke Roadmap</a>
          <span class="text-white/40">|</span>
          <span class="text-[11px] font-bold text-emerald-200">BABAK 2: CIPHER WORD-SEARCH</span>
        </div>
        <h1 class="text-xl sm:text-2xl font-black uppercase">{{ regu.nama_regu }}</h1>
      </div>

      <div class="flex items-center gap-3">
        <div class="bg-black/25 rounded-xl px-4 py-2 text-center border border-white/10">
          <div class="text-[10px] uppercase font-bold text-emerald-200">Sisa Kata Global</div>
          <div class="text-lg font-mono font-black">{{ remainingWordsCount }} / {{ totalWordsCount }}</div>
        </div>
        <div class="bg-amber-400 text-emerald-950 rounded-xl px-4 py-2 text-center shadow-md relative">
          <div class="text-[10px] uppercase font-black">Skor Babak 2</div>
          <div class="text-2xl font-mono font-black">{{ currentScore }}</div>
          <span
            v-if="pointGainBadge"
            class="absolute -top-4 -right-2 px-2 py-0.5 rounded-full bg-emerald-600 text-white text-xs font-black shadow-lg animate-bounce"
          >
            +{{ pointGainBadge }}
          </span>
        </div>
      </div>
    </div>

    <!-- Category Selector Tabs -->
    <div v-if="grids.length > 0" class="flex flex-wrap gap-2">
      <button
        v-for="g in grids"
        :key="g.id"
        type="button"
        @click="selectedGridId = g.id"
        class="px-4 py-2.5 rounded-xl text-xs font-black transition cursor-pointer flex items-center gap-2 border"
        :class="selectedGridId === g.id ? 'bg-[#1B4332] text-white border-[#1B4332] shadow-md' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'"
      >
        <span>{{ g.title }}</span>
        <span
          class="px-2 py-0.5 rounded-full text-[10px]"
          :class="selectedGridId === g.id ? 'bg-amber-400 text-emerald-950' : 'bg-gray-100 text-gray-600'"
        >
          {{ g.words.filter(w => !w.is_claimed).length }}/{{ g.words.length }}
        </span>
      </button>
    </div>

    <div v-if="!activeGrid" class="bg-white rounded-2xl border border-gray-200 p-10 text-center text-gray-500 font-semibold">
      Belum ada kategori Cipher Word-Search yang aktif.
    </div>

    <!-- Main Split Layout: Left Cipher Grid, Right Claim Form & Global Target Word List -->
    <div v-else class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
      <!-- Left: Cipher Grid Matrix -->
      <div class="lg:col-span-7 bg-white rounded-3xl border border-gray-200 shadow-sm p-4 sm:p-6">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
          <div>
            <h2 class="text-base font-black text-[#1B4332]">{{ activeGrid.title }}</h2>
            <p class="text-xs text-amber-700 font-bold">
              {{ cipherTypeLabel(activeGrid.cipher_type) }} • Matriks {{ activeGrid.grid_size }}×{{ activeGrid.grid_size }}
            </p>
          </div>
          <span class="text-[11px] font-bold text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
            Horizontal • Vertikal • Diagonal
          </span>
        </div>

        <div class="overflow-x-auto pb-2">
          <div
            class="inline-grid gap-1 p-2.5 bg-[#F4EDE4] rounded-2xl border border-amber-200 mx-auto"
            :style="{ gridTemplateColumns: `repeat(${activeGrid.grid_size}, minmax(26px, 1fr))` }"
          >
            <template v-for="(row, rIdx) in activeGrid.grid_matrix" :key="rIdx">
              <div
                v-for="(cell, cIdx) in row"
                :key="`${rIdx}-${cIdx}`"
                class="bg-white border border-gray-200 rounded-md flex items-center justify-center p-0.5 shadow-2xs"
              >
                <CipherCharacter
                  :type="activeGrid.cipher_type"
                  :char="cell"
                  :size="activeGrid.grid_size >= 18 ? 'xs' : 'sm'"
                />
              </div>
            </template>
          </div>
        </div>
      </div>

      <!-- Right: Claim Form & Global Target Word List -->
      <div class="lg:col-span-5 space-y-5">
        <!-- Form Klaim Kata -->
        <div class="bg-white rounded-3xl border-2 border-[#1B4332] shadow-md p-5 space-y-4">
          <div>
            <h3 class="text-sm font-black text-[#1B4332] uppercase tracking-wider">Klaim Kata Terjemahan</h3>
            <p class="text-xs text-gray-500 mt-0.5">
              1 Kata = 1 Regu Tercepat. Ketik kata hasil terjemahan sandi dari kotak di samping (atau lembar A4).
            </p>
          </div>

          <div
            v-if="feedback.message"
            class="p-3 rounded-xl text-xs font-bold border"
            :class="feedback.type === 'error' ? 'bg-red-50 text-red-800 border-red-300' : (feedback.type === 'warning' ? 'bg-amber-50 text-amber-900 border-amber-300' : 'bg-emerald-50 text-emerald-800 border-emerald-300')"
          >
            {{ feedback.message }}
          </div>

          <form @submit.prevent="submitClaim" class="space-y-3">
            <input
              v-model="wordInput"
              type="text"
              required
              placeholder="KETIK KATA HASIL TERJEMAHAN..."
              class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-[#1B4332] focus:outline-none text-base font-mono font-black uppercase tracking-wider"
            />
            <button
              type="submit"
              :disabled="submitting || !wordInput.trim()"
              class="w-full py-3 rounded-xl bg-[#1B4332] hover:bg-emerald-900 text-white font-black text-xs uppercase tracking-wider disabled:opacity-50 cursor-pointer shadow-md transition"
            >
              {{ submitting ? 'Memeriksa & Mengunci...' : 'Klaim Kata Sekarang!' }}
            </button>
          </form>
        </div>

        <!-- Global Target Word List (Real-time strike-through when claimed) -->
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-5 space-y-3">
          <div class="flex items-center justify-between">
            <h3 class="text-xs font-black uppercase tracking-wider text-[#1B4332]">
              Daftar Target Kata Kategori Ini
            </h3>
            <span class="text-[11px] font-bold text-gray-500">
              {{ activeGrid.words.filter(w => w.is_claimed).length }} / {{ activeGrid.words.length }} Terklaim
            </span>
          </div>

          <div class="space-y-2 max-h-96 overflow-y-auto pr-1">
            <div
              v-for="w in activeGrid.words"
              :key="w.id"
              class="p-3 rounded-xl border transition flex items-center justify-between gap-3"
              :class="w.is_claimed ? 'bg-amber-50/70 border-amber-300 opacity-85' : 'bg-gray-50 border-gray-200'"
            >
              <div class="min-w-0">
                <div class="flex items-center gap-2">
                  <span
                    v-if="w.is_claimed"
                    class="text-sm font-mono font-black text-amber-900 line-through decoration-2 decoration-red-600"
                  >
                    {{ w.word }}
                  </span>
                  <span v-else class="text-sm font-black text-[#1B4332]">
                    Target #{{ w.slot_number }} ({{ w.length }} Huruf)
                  </span>
                </div>
              </div>

              <div class="shrink-0 text-right">
                <span class="inline-block px-2 py-0.5 rounded-lg text-[11px] font-black bg-white border border-gray-200 text-[#1B4332]">
                  {{ w.points }} Poin
                </span>
                <div v-if="w.is_claimed" class="text-[10px] font-black text-amber-800 mt-1">
                  Terklaim: {{ w.claimed_by }}
                </div>
                <div v-else class="text-[10px] font-bold text-emerald-700 mt-1">
                  Tersedia
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import axios from 'axios';
import confetti from 'canvas-confetti';
import echo from '../../echo';
import CipherCharacter from '../CipherCharacter.vue';
import { getCipherTypeLabel } from '../../utils/sandiHelper';

const props = defineProps({
  initialData: { type: Object, required: true },
});

const regu = props.initialData.regu;
const routes = props.initialData.routes;
const grids = ref(props.initialData.grids || []);
const selectedGridId = ref(grids.value[0]?.id || null);
const currentScore = ref(Number(props.initialData.session.score_round_2 || 0));

const wordInput = ref('');
const submitting = ref(false);
const pointGainBadge = ref(null);
const feedback = reactive({ type: 'success', message: '' });

const activeGrid = computed(() => grids.value.find(g => g.id === selectedGridId.value) || null);

const totalWordsCount = computed(() =>
  grids.value.reduce((acc, g) => acc + (g.words?.length || 0), 0)
);

const remainingWordsCount = computed(() =>
  grids.value.reduce((acc, g) => acc + (g.words || []).filter(w => !w.is_claimed).length, 0)
);

function cipherTypeLabel(t) {
  return getCipherTypeLabel(t);
}

function markWordClaimedLocally(gridId, wordId, wordText, reguId, reguName) {
  const grid = grids.value.find(g => g.id === gridId);
  if (!grid) return;
  const wordObj = (grid.words || []).find(w => w.id === wordId);
  if (wordObj) {
    wordObj.is_claimed = true;
    wordObj.word = wordText;
    wordObj.claimed_by_regu_id = reguId;
    wordObj.claimed_by = reguName;
  }
}

async function submitClaim() {
  if (!activeGrid.value || !wordInput.value.trim()) return;
  submitting.value = true;
  feedback.message = '';

  try {
    const { data } = await axios.post(routes.claim, {
      cipher_grid_id: activeGrid.value.id,
      word: wordInput.value,
    });

    if (data.success) {
      currentScore.value = data.new_score;
      pointGainBadge.value = data.points_awarded;
      setTimeout(() => { pointGainBadge.value = null; }, 3000);

      markWordClaimedLocally(data.grid_id, data.word_id, data.word, regu.id, regu.nama_regu);
      feedback.type = 'success';
      feedback.message = data.message;
      wordInput.value = '';

      try {
        confetti({ particleCount: 60, spread: 65, origin: { y: 0.7 } });
      } catch {}
    }
  } catch (err) {
    const status = err.response?.status;
    const data = err.response?.data || {};
    if (status === 409 && data.word_id) {
      markWordClaimedLocally(data.grid_id, data.word_id, data.word, null, data.claimed_by);
      feedback.type = 'warning';
    } else {
      feedback.type = 'error';
    }
    feedback.message = data.message || 'Gagal mengklaim kata.';
  } finally {
    submitting.value = false;
  }
}

onMounted(() => {
  if (echo) {
    echo.join('quiz-arena')
      .listen('.CipherWordClaimed', (e) => {
        markWordClaimedLocally(e.gridId, e.wordId, e.word, e.reguId, e.reguName);
        if (e.reguId === regu.id) {
          currentScore.value = e.newRound2Score;
        }
      })
      .listen('.RoundStatusChanged', (e) => {
        if (e.round === 2 && e.status !== 'ACTIVE') {
          window.location.href = routes.roadmap;
        }
      });
  }
});
</script>
