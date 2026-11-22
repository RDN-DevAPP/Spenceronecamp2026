<template>
  <div class="w-full space-y-4">
    <!-- Coin Royale Live Top 5 Stage Board -->
    <div v-if="coinRoyale.finalists && coinRoyale.finalists.length > 0" class="space-y-3">
      <!-- Active Round & Question Banner -->
      <div class="flex flex-wrap items-center justify-between gap-3 bg-black/50 border border-cyan-500/30 rounded-2xl px-4 py-3">
        <div class="flex items-center gap-3">
          <span class="px-3 py-1 rounded-full bg-amber-400 text-emerald-950 font-black text-xs uppercase tracking-wider">
            FINAL COIN ROYALE • RONDE {{ activeRound?.round_number ?? 1 }}/9
          </span>
          <span
            class="px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase"
            :class="activeRound?.is_special ? 'bg-amber-500/20 text-amber-300 border border-amber-400/50' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-400/50'"
          >
            {{ activeRound?.is_special ? 'SPESIAL (3× / -2×)' : 'NORMAL (2× / -1×)' }}
          </span>
        </div>
        <span class="px-3 py-1 rounded-full bg-cyan-950 text-cyan-300 border border-cyan-500/40 text-xs font-black uppercase">
          {{ phaseLabel(activeRound?.phase) }}
        </span>
      </div>

      <!-- Live Question Display when QUESTION_OPEN -->
      <div
        v-if="activeRound?.phase === 'QUESTION_OPEN' && (activeRound?.question_text || activeRound?.question_image_url)"
        style="background: linear-gradient(135deg, rgba(69,26,3,0.9) 0%, rgba(15,23,42,0.95) 50%, rgba(69,26,3,0.9) 100%);"
        class="border-2 border-amber-400/70 rounded-2xl p-4 text-center shadow-[0_0_30px_rgba(251,191,36,0.2)] space-y-3"
      >
        <div class="text-[10px] font-black uppercase tracking-widest text-amber-300">SOAL RONDE {{ activeRound.round_number }}</div>
        <div v-if="activeRound?.question_image_url" class="flex justify-center">
          <img
            :src="activeRound.question_image_url"
            :alt="`Gambar Soal Ronde ${activeRound.round_number}`"
            class="max-h-64 w-auto object-contain rounded-xl border border-amber-300/40 bg-black/40 p-1"
          />
        </div>
        <div v-if="activeRound?.question_text" class="text-base sm:text-xl font-black text-white leading-snug">{{ activeRound.question_text }}</div>
      </div>

      <!-- Top 5 Finalists Animated Leaderboard -->
      <transition-group
        name="rank-list"
        tag="div"
        class="grid grid-cols-1 md:grid-cols-5 gap-3"
      >
        <div
          v-for="f in coinRoyale.finalists"
          :key="f.session_id"
          class="rounded-2xl p-4 border transition-all duration-500 flex flex-col justify-between relative overflow-hidden"
          :class="f.rank === 1 ? 'bg-amber-950/80 border-amber-400 shadow-[0_0_25px_rgba(251,191,36,0.3)]' : 'bg-slate-900/90 border-emerald-600/35'"
        >
          <div>
            <div class="flex items-center justify-between mb-2">
              <span
                class="px-2.5 py-0.5 rounded-lg text-xs font-black"
                :class="f.rank === 1 ? 'bg-amber-400 text-emerald-950' : 'bg-white/10 text-amber-300'"
              >
                #{{ f.rank }} {{ f.rank === 1 ? '👑' : '' }}
              </span>
              <span
                v-if="f.current_bet"
                class="px-2 py-0.5 rounded text-[10px] font-black uppercase"
                :class="betBadgeClass(f.current_bet)"
              >
                {{ betBadgeText(f.current_bet) }}
              </span>
            </div>
            <h4 class="text-sm sm:text-base font-black text-white truncate">{{ f.nama_regu }}</h4>
            <div class="text-[10px] text-emerald-300/80 font-bold uppercase">{{ f.jenis }} • Regu {{ f.nomor_regu }}</div>
          </div>

          <div class="mt-4 pt-3 border-t border-white/10">
            <div class="text-[10px] text-gray-400 uppercase font-bold">Saldo Koin</div>
            <div class="flex items-baseline justify-between gap-2">
              <span class="text-2xl sm:text-3xl font-mono font-black text-amber-300">{{ f.coin_balance }}</span>
              <span
                v-if="f.current_bet && f.current_bet.coin_delta !== 0"
                class="text-xs font-mono font-black px-1.5 py-0.5 rounded"
                :class="f.current_bet.coin_delta > 0 ? 'bg-emerald-500/20 text-emerald-300' : 'bg-red-500/20 text-red-300'"
              >
                {{ f.current_bet.coin_delta > 0 ? `+${f.current_bet.coin_delta}` : f.current_bet.coin_delta }}
              </span>
            </div>
          </div>
        </div>
      </transition-group>
    </div>

    <!-- All Regus B1 & B2 Summary Cards -->
    <div v-if="quizLeaderboard.length > 0" class="grid grid-cols-2 md:grid-cols-4 gap-3">
      <div
        v-for="qRegu in quizLeaderboard.slice(0, 8)"
        :key="qRegu.regu_name"
        class="bg-slate-900/90 rounded-2xl p-3.5 border border-emerald-600/30 shadow-lg"
      >
        <div class="flex items-center justify-between mb-1.5">
          <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase" :class="qRegu.jenis === 'putra' ? 'bg-blue-900/80 text-blue-200' : 'bg-pink-900/80 text-pink-200'">
            {{ qRegu.jenis }}
          </span>
          <span v-if="qRegu.is_finalist" class="px-2 py-0.5 rounded text-[10px] font-black bg-amber-400 text-emerald-950">FINALIS</span>
        </div>
        <h3 class="text-sm font-black text-white truncate">{{ qRegu.regu_name }}</h3>
        <div class="mt-2 grid grid-cols-3 gap-1 text-center bg-black/30 rounded-xl p-1.5 border border-white/5 text-[10px]">
          <div><span class="text-gray-400 block">B1</span><strong class="text-emerald-300 font-mono">{{ qRegu.b1 }}</strong></div>
          <div><span class="text-gray-400 block">B2</span><strong class="text-amber-300 font-mono">{{ qRegu.b2 }}</strong></div>
          <div><span class="text-gray-400 block">Koin</span><strong class="text-cyan-300 font-mono">{{ qRegu.coin_balance }}</strong></div>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-12 text-gray-400 text-sm">
      Belum ada aktivitas sesi Scout Cyber Quiz Arena yang aktif.
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import echo from '../../echo';

const props = defineProps({
  initialData: { type: Object, required: true },
});

const coinRoyale = ref(props.initialData.coinRoyaleState || { rounds: [], finalists: [] });
const quizLeaderboard = ref(props.initialData.quizLeaderboard || []);

const activeRound = computed(() =>
  coinRoyale.value.active_round || (coinRoyale.value.rounds || [])[0] || null
);

function phaseLabel(phase) {
  const map = {
    PENDING: 'Menunggu Ronde',
    BETTING_OPEN: 'Taruhan Dibuka',
    BETTING_LOCKED: 'Taruhan Dikunci',
    QUESTION_OPEN: 'Soal Dibuka',
    GRADED: 'Hasil Disahkan',
  };
  return map[phase] || 'Standby';
}

function betBadgeClass(bet) {
  if (bet.result === 'CORRECT') return 'bg-emerald-500 text-white';
  if (bet.result === 'WRONG') return 'bg-red-600 text-white';
  if (bet.action === 'PASS') return 'bg-gray-600 text-gray-100';
  return 'bg-amber-400/20 text-amber-300 border border-amber-400/40';
}

function betBadgeText(bet) {
  if (bet.result === 'CORRECT') return 'BENAR';
  if (bet.result === 'WRONG') return 'SALAH';
  if (bet.action === 'PASS') return 'PASS';
  return `BET ${bet.bet_amount}`;
}

async function refreshLiveDisplay() {
  try {
    const { data } = await axios.get('/api/live-display/data');
    if (data.success && data.data) {
      if (data.data.coinRoyaleState) coinRoyale.value = data.data.coinRoyaleState;
      if (data.data.quizLeaderboard) quizLeaderboard.value = data.data.quizLeaderboard;
    }
  } catch {
    // ignore
  }
}

onMounted(() => {
  if (echo) {
    echo.channel('public-live-screen')
      .listen('.CoinRoyaleUpdated', (e) => {
        if (e.state) {
          coinRoyale.value = e.state;
        }
        refreshLiveDisplay();
      })
      .listen('.QrQuestionClaimed', () => refreshLiveDisplay())
      .listen('.CipherWordClaimed', () => refreshLiveDisplay())
      .listen('.RoundStatusChanged', () => refreshLiveDisplay());
  }
});
</script>

<style scoped>
.rank-list-move {
  transition: transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
}
</style>
