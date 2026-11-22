<template>
  <div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">
    <!-- Top Header -->
    <div style="background: linear-gradient(135deg, #0B2317 0%, #1B4332 50%, #0B2317 100%);" class="text-white rounded-3xl p-6 shadow-xl border border-amber-400/30 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <div class="flex items-center gap-2 mb-1">
          <a :href="routes.roadmap" class="text-xs font-bold text-amber-300 hover:underline">&larr; Roadmap</a>
          <span class="text-white/40">|</span>
          <span class="text-[11px] font-black text-amber-300 uppercase tracking-wider">BABAK 3: FINAL "COIN ROYALE"</span>
        </div>
        <h1 class="text-2xl font-black uppercase">{{ regu.nama_regu }}</h1>
        <p class="text-xs text-emerald-200 mt-0.5">
          {{ isFinalist ? 'Finalis Resmi Top 5 • 9 Ronde Taruhan Taktis' : 'Mode Penonton — Anda dapat menyaksikan duel Top 5 secara langsung' }}
        </p>
      </div>

      <div v-if="isFinalist" class="bg-amber-400 text-emerald-950 rounded-2xl px-6 py-3 text-center shadow-lg">
        <div class="text-[10px] font-black uppercase tracking-wider">Saldo Koin Saat Ini</div>
        <div class="text-3xl font-mono font-black">{{ myBalance }}</div>
        <div class="text-[10px] font-bold">Saldo Awal: {{ myFinalist?.coin_initial ?? 0 }}</div>
      </div>
    </div>

    <!-- 9-Round Progress Bar -->
    <div class="bg-white rounded-2xl border border-gray-200 p-4">
      <div class="grid grid-cols-9 gap-1.5">
        <div
          v-for="r in state.rounds"
          :key="r.round_number"
          class="p-2 rounded-xl border text-center transition"
          :class="[
            r.round_number === activeRound?.round_number ? 'border-[#1B4332] ring-2 ring-[#1B4332]/20' : 'border-gray-200',
            r.phase === 'GRADED' ? 'bg-emerald-50' : (r.is_special ? 'bg-amber-50' : 'bg-gray-50'),
          ]"
        >
          <div class="text-xs font-black text-[#1B4332]">R{{ r.round_number }}</div>
          <div class="text-[9px] font-black" :class="r.is_special ? 'text-amber-700' : 'text-gray-400'">
            {{ r.is_special ? 'SPESIAL' : 'NORMAL' }}
          </div>
        </div>
      </div>
    </div>

    <!-- Active Round Interactive Console (For Finalists) -->
    <div v-if="activeRound" class="bg-white rounded-3xl border-2 shadow-lg overflow-hidden"
      :class="activeRound.is_special ? 'border-amber-400' : 'border-[#1B4332]'"
    >
      <div class="px-6 py-4 text-white flex flex-wrap items-center justify-between gap-3"
        :class="activeRound.is_special ? 'bg-amber-600' : 'bg-[#1B4332]'"
      >
        <div>
          <span class="text-xs font-black uppercase tracking-widest opacity-90">
            {{ activeRound.is_special ? 'RONDE SPESIAL (OPSIONAL)' : 'RONDE NORMAL (WAJIB)' }}
          </span>
          <h2 class="text-xl font-black">Ronde {{ activeRound.round_number }} dari 9</h2>
        </div>
        <span class="px-3 py-1 rounded-full bg-black/25 text-xs font-black uppercase">
          Fase: {{ phaseLabel(activeRound.phase) }}
        </span>
      </div>

      <div class="p-6 space-y-5">
        <!-- Rules reminder -->
        <div class="p-3.5 rounded-2xl text-xs font-bold border"
          :class="activeRound.is_special ? 'bg-amber-50 border-amber-200 text-amber-900' : 'bg-emerald-50 border-emerald-200 text-emerald-900'"
        >
          <template v-if="activeRound.is_special">
            Aturan Ronde Spesial: Boleh memilih <strong>Ikut</strong> atau <strong>Pass (Saldo Aman)</strong>.
            Jika Ikut: <strong>Benar = Menang 3× Taruhan</strong>, <strong>Salah = Potong 2× Taruhan</strong> (Maksimal taruhan 50% saldo = {{ maxSpecialBet }} koin).
          </template>
          <template v-else>
            Aturan Ronde Normal: <strong>WAJIB memasang taruhan</strong> dari saldo Anda.
            <strong>Benar = Menang 2× Taruhan</strong>, <strong>Salah = Taruhan Hangus</strong>.
          </template>
        </div>

        <!-- Feedback Alert -->
        <div
          v-if="feedback.message"
          class="p-3.5 rounded-xl text-xs font-bold border"
          :class="feedback.type === 'error' ? 'bg-red-50 border-red-300 text-red-800' : 'bg-emerald-50 border-emerald-300 text-emerald-800'"
        >
          {{ feedback.message }}
        </div>

        <!-- Phase: BETTING_OPEN (For Top 5 Finalists) -->
        <div v-if="isFinalist && activeRound.phase === 'BETTING_OPEN'" class="space-y-4">
          <div v-if="myCurrentBet" class="p-4 rounded-2xl bg-emerald-50 border border-emerald-300 text-sm font-bold text-emerald-900">
            Pilihan Anda tercatat:
            <span class="font-black uppercase">
              {{ myCurrentBet.action === 'PASS' ? 'PASS (Tidak Ikut)' : `BERTARUH ${myCurrentBet.bet_amount} KOIN` }}
            </span>
            <span class="text-xs font-normal block mt-1">Anda masih dapat mengubah sebelum Juri mengunci taruhan.</span>
          </div>

          <div class="space-y-3">
            <label class="block text-xs font-black uppercase text-gray-600">
              Jumlah Taruhan Koin (Maks: {{ activeRound.is_special ? maxSpecialBet : myBalance }} Koin)
            </label>
            <div class="flex flex-wrap gap-2">
              <button
                v-for="pct in [25, 50, 75, 100]"
                :key="pct"
                type="button"
                @click="applyPresetPct(pct)"
                class="px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-amber-100 text-xs font-black text-gray-800 border border-gray-200 cursor-pointer"
              >
                {{ activeRound.is_special && pct === 100 ? 'Max 50%' : `${pct}%` }}
              </button>
            </div>
            <input
              v-model.number="betInput"
              type="number"
              min="0"
              :max="activeRound.is_special ? maxSpecialBet : myBalance"
              class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-[#1B4332] text-xl font-mono font-black"
            />
          </div>

          <div class="flex flex-col sm:flex-row gap-3">
            <button
              type="button"
              :disabled="submitting"
              @click="submitBet('BET')"
              class="flex-1 py-3.5 rounded-2xl bg-[#1B4332] hover:bg-emerald-900 text-white font-black text-xs uppercase tracking-wider cursor-pointer shadow-md"
            >
              {{ activeRound.is_special ? 'Ikut Ronde Spesial & Pasang Taruhan' : 'Pasang Taruhan Wajib' }}
            </button>

            <button
              v-if="activeRound.is_special"
              type="button"
              :disabled="submitting"
              @click="submitBet('PASS')"
              class="sm:w-56 py-3.5 rounded-2xl bg-gray-200 hover:bg-gray-300 text-gray-800 font-black text-xs uppercase tracking-wider cursor-pointer"
            >
              Tidak Ikut / Pass
            </button>
          </div>
        </div>

        <!-- Phase: BETTING_LOCKED -->
        <div v-else-if="activeRound.phase === 'BETTING_LOCKED'" class="p-6 text-center bg-gray-50 rounded-2xl border border-gray-200">
          <p class="text-sm font-black text-[#1B4332] uppercase">Taruhan Telah Dikunci!</p>
          <p class="text-xs text-gray-500 mt-1">Menunggu Dewan Juri membuka pertanyaan Ronde {{ activeRound.round_number }}...</p>
        </div>

        <!-- Phase: QUESTION_OPEN -->
        <div v-else-if="activeRound.phase === 'QUESTION_OPEN'" class="p-6 rounded-2xl bg-[#F4EDE4] border-2 border-amber-400 space-y-3">
          <div class="text-[11px] font-black uppercase text-amber-800 tracking-wider">Pertanyaan Ronde {{ activeRound.round_number }}</div>
          <div v-if="activeRound.question_image_url" class="rounded-2xl overflow-hidden border border-amber-300 bg-white p-2 flex justify-center">
            <img
              :src="activeRound.question_image_url"
              :alt="`Gambar Soal Ronde ${activeRound.round_number}`"
              class="max-h-72 w-auto object-contain rounded-xl"
            />
          </div>
          <p class="text-lg sm:text-xl font-black text-[#1B4332] leading-relaxed">
            {{ activeRound.question_text || 'Perhatikan layar proyektor / arahan Dewan Juri!' }}
          </p>
        </div>

        <!-- Phase: GRADED -->
        <div v-else-if="activeRound.phase === 'GRADED'" class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200 text-center space-y-1">
          <div class="text-xs font-black uppercase text-emerald-800">Ronde {{ activeRound.round_number }} Selesai Dinilai</div>
          <div v-if="isFinalist && myCurrentBet" class="text-lg font-black">
            Hasil Regu Anda:
            <span :class="myCurrentBet.coin_delta >= 0 ? 'text-emerald-700' : 'text-red-600'">
              {{ myCurrentBet.result }} ({{ myCurrentBet.coin_delta > 0 ? `+${myCurrentBet.coin_delta}` : myCurrentBet.coin_delta }} Koin)
            </span>
          </div>
        </div>

        <!-- Phase: PENDING -->
        <div v-else class="p-6 text-center bg-gray-50 rounded-2xl border border-gray-200">
          <p class="text-sm font-bold text-gray-600">Menunggu Dewan Juri membuka fase taruhan ronde berikutnya...</p>
        </div>
      </div>
    </div>

    <!-- Top 5 Live Leaderboard -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-5">
      <h3 class="text-xs font-black uppercase tracking-wider text-gray-500 mb-3">
        Klasemen Langsung Top 5 Finalis Coin Royale
      </h3>
      <div class="space-y-2">
        <div
          v-for="f in state.finalists"
          :key="f.session_id"
          class="flex items-center justify-between p-3.5 rounded-2xl border transition"
          :class="f.regu_id === regu.id ? 'bg-amber-50 border-amber-400 ring-2 ring-amber-400/20' : 'bg-gray-50 border-gray-100'"
        >
          <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-xl bg-[#1B4332] text-amber-300 font-black text-sm flex items-center justify-center">
              #{{ f.rank }}
            </span>
            <div>
              <div class="font-black text-sm text-gray-900">{{ f.nama_regu }}</div>
              <div class="text-[11px] text-gray-500">Modal Awal: {{ f.coin_initial }} Koin</div>
            </div>
          </div>
          <div class="text-right">
            <div class="text-xl font-mono font-black text-[#1B4332]">{{ f.coin_balance }}</div>
            <div class="text-[10px] font-bold text-gray-400 uppercase">KOIN</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import axios from 'axios';
import echo from '../../echo';

const props = defineProps({
  initialData: { type: Object, required: true },
});

const regu = props.initialData.regu;
const isFinalist = Boolean(props.initialData.isFinalist);
const routes = props.initialData.routes;
const state = ref(props.initialData.coinRoyale || { rounds: [], finalists: [] });

const betInput = ref(0);
const submitting = ref(false);
const feedback = reactive({ type: 'success', message: '' });

const activeRound = computed(() =>
  state.value.active_round || (state.value.rounds || [])[0] || null
);

const myFinalist = computed(() =>
  (state.value.finalists || []).find(f => f.regu_id === regu.id) || null
);

const myBalance = computed(() => Number(myFinalist.value?.coin_balance ?? 0));
const maxSpecialBet = computed(() => Math.floor(myBalance.value * 0.5));

const myCurrentBet = computed(() => {
  if (!activeRound.value || !myFinalist.value) return null;
  return (activeRound.value.bets || []).find(b => b.session_id === myFinalist.value.session_id) || null;
});

function phaseLabel(phase) {
  const map = {
    PENDING: 'Menunggu',
    BETTING_OPEN: 'Taruhan Dibuka',
    BETTING_LOCKED: 'Taruhan Dikunci',
    QUESTION_OPEN: 'Soal Dibuka',
    GRADED: 'Selesai Dinilai',
  };
  return map[phase] || phase;
}

function applyPresetPct(pct) {
  const cap = activeRound.value?.is_special ? maxSpecialBet.value : myBalance.value;
  if (activeRound.value?.is_special) {
    betInput.value = Math.min(cap, Math.floor((myBalance.value * (pct / 2)) / 100));
    if (pct === 100) betInput.value = cap;
  } else {
    betInput.value = Math.floor((myBalance.value * pct) / 100);
  }
}

async function submitBet(action) {
  if (!activeRound.value) return;
  submitting.value = true;
  feedback.message = '';

  try {
    const { data } = await axios.post(routes.bet, {
      round_number: activeRound.value.round_number,
      action,
      bet_amount: action === 'PASS' ? 0 : Number(betInput.value || 0),
    });

    if (data.coin_royale) {
      state.value = data.coin_royale;
    }
    feedback.type = 'success';
    feedback.message = data.message;
  } catch (err) {
    feedback.type = 'error';
    feedback.message = err.response?.data?.message || 'Gagal mengirim taruhan.';
  } finally {
    submitting.value = false;
  }
}

onMounted(() => {
  if (echo) {
    echo.join('quiz-arena')
      .listen('.CoinRoyaleUpdated', (e) => {
        if (e.state) {
          state.value = e.state;
        }
      })
      .listen('.RoundStatusChanged', (e) => {
        if (e.round === 3 && e.status !== 'ACTIVE') {
          window.location.href = routes.roadmap;
        }
      });
  }
});
</script>
