<template>
  <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
    <!-- Hero Regu Status Header -->
    <div style="background: linear-gradient(135deg, #1B4332 0%, #2D6A4F 50%, #1B4332 100%);" class="rounded-3xl p-6 sm:p-8 text-white shadow-xl border border-emerald-700/40 relative overflow-hidden">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5 relative z-10">
        <div>
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-400/20 border border-amber-300/40 text-amber-300 text-xs font-black uppercase tracking-wider mb-2">
            <span class="w-2 h-2 rounded-full" :class="wsConnected ? 'bg-emerald-400 animate-pulse' : 'bg-amber-300'"></span>
            Scout Cyber Quiz Arena • Live Roadmap
          </div>
          <h1 class="text-2xl sm:text-3xl font-black uppercase tracking-tight">
            {{ regu.nama_regu }}
          </h1>
          <p class="text-xs sm:text-sm text-emerald-100/90 mt-1">
            Regu {{ regu.jenis }} • Nomor {{ regu.nomor_regu }}
            <span v-if="myRank" class="ml-2 px-2 py-0.5 rounded-full bg-amber-400 text-emerald-950 font-black text-[11px]">
              Peringkat #{{ myRank }}
            </span>
          </p>
        </div>

        <div class="grid grid-cols-3 gap-3">
          <div class="bg-black/25 backdrop-blur rounded-2xl px-4 py-3 text-center border border-white/10">
            <div class="text-[10px] uppercase font-bold text-emerald-200">Skor B1</div>
            <div class="text-2xl font-mono font-black text-white">{{ scores.round_1 }}</div>
          </div>
          <div class="bg-black/25 backdrop-blur rounded-2xl px-4 py-3 text-center border border-white/10">
            <div class="text-[10px] uppercase font-bold text-emerald-200">Skor B2</div>
            <div class="text-2xl font-mono font-black text-white">{{ scores.round_2 }}</div>
          </div>
          <div class="bg-amber-500/20 backdrop-blur rounded-2xl px-4 py-3 text-center border border-amber-400/40">
            <div class="text-[10px] uppercase font-bold text-amber-200">
              {{ session.is_verified_round_3 ? 'Koin B3' : 'Total B1+B2' }}
            </div>
            <div class="text-2xl font-mono font-black text-amber-300">
              {{ session.is_verified_round_3 ? scores.coin_balance : scores.round_1 + scores.round_2 }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Real-Time Announcement Toast -->
    <div
      v-if="liveNotification"
      class="bg-amber-50 border-2 border-amber-400 text-amber-950 px-5 py-3.5 rounded-2xl text-sm font-black flex items-center justify-between shadow-sm animate-pulse"
    >
      <span>{{ liveNotification }}</span>
      <button type="button" @click="liveNotification = ''" class="text-xs underline cursor-pointer">Tutup</button>
    </div>

    <!-- Pendaftaran Perwakilan Peserta Cerdas Cermat -->
    <div class="bg-white rounded-3xl border-2 shadow-sm p-5 sm:p-6"
      :class="hasRegisteredParticipants ? 'border-emerald-200' : 'border-amber-300 bg-amber-50/30'"
    >
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
          <div class="flex flex-wrap items-center gap-2">
            <h2 class="text-base font-black text-[#1B4332] uppercase tracking-tight">
              Pendaftaran Peserta Cerdas Cermat (Perwakilan Regu)
            </h2>
            <span
              class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase"
              :class="hasRegisteredParticipants ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-amber-100 text-amber-900 border border-amber-300'"
            >
              {{ hasRegisteredParticipants ? 'Tim Terdaftar' : 'Belum Didaftarkan' }}
            </span>
          </div>
          <p class="text-xs text-gray-500 mt-0.5">
            Pilih {{ anggota.length >= 3 ? '3 anggota' : 'anggota' }} perwakilan dari daftar anggota regu resmi Anda untuk mengikuti babak Cerdas Cermat.
          </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
          <a
            v-if="routes.dashboardAnggota && anggota.length < 8"
            :href="routes.dashboardAnggota"
            class="px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold border border-gray-200 transition"
          >
            + Tambah Anggota Regu
          </a>
          <button
            v-if="hasRegisteredParticipants"
            type="button"
            @click="editingParticipants = !editingParticipants"
            class="px-3.5 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-[#1B4332] text-xs font-bold border border-emerald-200 cursor-pointer transition"
          >
            {{ editingParticipants ? 'Tutup Form' : 'Ubah Perwakilan' }}
          </button>
        </div>
      </div>

      <!-- Flash message -->
      <div
        v-if="regFlash.message"
        class="mt-3 px-4 py-2.5 rounded-xl text-xs font-bold border"
        :class="regFlash.type === 'error' ? 'bg-red-50 border-red-200 text-red-700' : 'bg-emerald-50 border-emerald-200 text-emerald-800'"
      >
        {{ regFlash.message }}
      </div>

      <!-- Summary Chips when Registered & Collapsed -->
      <div v-if="hasRegisteredParticipants && !editingParticipants" class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div
          v-for="(slotName, idx) in [session.name_1, session.name_2, session.name_3]"
          :key="idx"
          class="flex items-center gap-2.5 p-3 rounded-2xl bg-emerald-50/70 border border-emerald-200"
        >
          <span class="w-6 h-6 rounded-full bg-[#1B4332] text-white text-xs font-black flex items-center justify-center shrink-0">
            {{ idx + 1 }}
          </span>
          <div class="min-w-0">
            <div class="text-[10px] font-bold uppercase text-emerald-700">Peserta {{ idx + 1 }}</div>
            <div class="text-sm font-black text-[#1B4332] truncate">
              {{ slotName || '-' }}
            </div>
          </div>
        </div>
      </div>

      <!-- Registration Form -->
      <form v-else @submit.prevent="submitParticipantRegistration" class="mt-4 space-y-4">
        <div v-if="anggota.length < 3" class="px-4 py-2.5 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900">
          Regu Anda saat ini baru memiliki <strong>{{ anggota.length }}</strong> anggota terdaftar. Anda tetap dapat mendaftarkan perwakilan yang tersedia atau menambah anggota di Dashboard terlebih dahulu.
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div
            v-for="slot in [1, 2, 3]"
            :key="slot"
            class="p-3.5 rounded-2xl bg-gray-50 border border-gray-200"
          >
            <label :for="`reg-name-${slot}`" class="block text-xs font-black text-[#1B4332] mb-1.5">
              Peserta {{ slot }} {{ slot === 1 || anggota.length >= 3 ? '*' : '(Opsional)' }}
            </label>
            <select
              :id="`reg-name-${slot}`"
              v-model="participantForm[`name_${slot}`]"
              :required="slot === 1 || anggota.length >= 3"
              class="w-full px-3 py-2 rounded-xl border border-gray-300 bg-white text-xs font-bold text-gray-800 focus:border-[#1B4332]"
            >
              <option value="">-- Pilih Anggota Regu --</option>
              <option
                v-for="member in anggota"
                :key="member.id"
                :value="member.nama"
                :disabled="isMemberPickedElsewhere(slot, member.nama)"
              >
                {{ member.nama }} ({{ (member.jabatan || 'anggota').toUpperCase() }} • Kls {{ member.kelas }})
              </option>
            </select>
          </div>
        </div>

        <div class="flex justify-end gap-2">
          <button
            type="submit"
            :disabled="savingParticipants || !participantForm.name_1"
            class="px-5 py-2.5 rounded-xl bg-[#1B4332] hover:bg-emerald-900 disabled:opacity-50 text-white text-xs font-black uppercase tracking-wider shadow-sm cursor-pointer transition"
          >
            {{ savingParticipants ? 'Menyimpan...' : (hasRegisteredParticipants ? 'Simpan Perubahan Tim' : 'Daftarkan Tim') }}
          </button>
        </div>
      </form>
    </div>

    <!-- 3-ROUND ROADMAP CARDS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div
        v-for="card in roadmapCards"
        :key="card.round"
        class="bg-white rounded-3xl border-2 shadow-sm overflow-hidden flex flex-col justify-between transition-all duration-300"
        :class="cardBorderClass(roundStatuses[`round_${card.round}`])"
      >
        <div>
          <!-- Card Header -->
          <div class="p-5 text-white" :class="cardHeaderBg(roundStatuses[`round_${card.round}`])">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-black uppercase tracking-widest opacity-85">
                BABAK {{ card.round }}
              </span>
              <span
                class="px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider"
                :class="badgeClass(roundStatuses[`round_${card.round}`])"
              >
                {{ statusLabel(roundStatuses[`round_${card.round}`]) }}
              </span>
            </div>
            <h2 class="text-xl font-black tracking-tight">{{ card.title }}</h2>
            <p class="text-xs opacity-90 mt-1">{{ card.subtitle }}</p>
          </div>

          <!-- Card Body -->
          <div class="p-5 space-y-4">
            <p class="text-xs text-gray-600 leading-relaxed">{{ card.description }}</p>

            <!-- Round Metrics -->
            <div class="grid grid-cols-2 gap-2.5">
              <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                <div class="text-[10px] font-bold uppercase text-gray-400">{{ card.metric1Label }}</div>
                <div class="text-base font-black text-[#1B4332]">{{ card.metric1Value }}</div>
              </div>
              <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                <div class="text-[10px] font-bold uppercase text-gray-400">{{ card.metric2Label }}</div>
                <div class="text-base font-black text-amber-600">{{ card.metric2Value }}</div>
              </div>
            </div>

            <!-- Finalist Badge on Babak 3 -->
            <div v-if="card.round === 3" class="p-3 rounded-xl border text-xs font-bold"
              :class="session.is_verified_round_3 ? 'bg-amber-50 border-amber-300 text-amber-900' : 'bg-gray-50 border-gray-200 text-gray-500'"
            >
              <template v-if="session.is_verified_round_3">
                Selamat! Regu Anda lolos sebagai <strong>TOP 5 Finalis Coin Royale</strong> (Saldo Awal: {{ session.coin_initial }} Koin).
              </template>
              <template v-else>
                Babak Final khusus Top 5 Regu terbaik (B1 + B2). Anda tetap dapat memantau klasemen secara live saat babak aktif.
              </template>
            </div>
          </div>
        </div>

        <!-- Action Footer Controlled in Real-Time by Juri -->
        <div class="p-5 pt-0">
          <a
            v-if="roundStatuses[`round_${card.round}`] === 'ACTIVE'"
            :href="card.url"
            class="w-full py-3.5 px-4 rounded-2xl bg-[#1B4332] hover:bg-emerald-900 text-white font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 shadow-lg transition transform hover:scale-[1.01]"
          >
            <span>Masuk Arena Babak {{ card.round }}</span>
            <span>&rarr;</span>
          </a>

          <button
            v-else-if="roundStatuses[`round_${card.round}`] === 'FINISHED'"
            type="button"
            disabled
            class="w-full py-3.5 px-4 rounded-2xl bg-blue-50 text-blue-800 border border-blue-200 font-black text-xs uppercase tracking-wider cursor-not-allowed"
          >
            Babak {{ card.round }} Telah Selesai
          </button>

          <button
            v-else
            type="button"
            disabled
            class="w-full py-3.5 px-4 rounded-2xl bg-gray-100 text-gray-500 border border-gray-200 font-black text-xs uppercase tracking-wider cursor-not-allowed flex items-center justify-center gap-2"
          >
            <span>Terkunci — Menunggu Juri</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import echo from '../../echo';

const props = defineProps({
  initialData: { type: Object, required: true },
});

const regu = props.initialData.regu;
const session = reactive({ ...props.initialData.session });
const anggota = ref(props.initialData.anggota || []);
const routes = props.initialData.routes;
const wsConnected = ref(false);
const liveNotification = ref('');
const myRank = ref(props.initialData.myRank);

const hasRegisteredParticipants = computed(() => Boolean((session.name_1 || '').trim()));
const editingParticipants = ref(!Boolean((session.name_1 || '').trim()));
const savingParticipants = ref(false);
const regFlash = reactive({ message: '', type: 'success' });

const participantForm = reactive({
  name_1: session.name_1 || '',
  name_2: session.name_2 || '',
  name_3: session.name_3 || '',
});

function isMemberPickedElsewhere(slot, memberName) {
  for (const s of [1, 2, 3]) {
    if (s !== slot && participantForm[`name_${s}`] === memberName) {
      return true;
    }
  }
  return false;
}

async function submitParticipantRegistration() {
  savingParticipants.value = true;
  regFlash.message = '';
  try {
    const { data } = await axios.post(routes.register, {
      name_1: participantForm.name_1,
      name_2: participantForm.name_2 || null,
      name_3: participantForm.name_3 || null,
    });
    if (data.session) {
      session.name_1 = data.session.name_1;
      session.name_2 = data.session.name_2;
      session.name_3 = data.session.name_3;
    }
    regFlash.type = 'success';
    regFlash.message = data.message || 'Perwakilan peserta berhasil didaftarkan.';
    editingParticipants.value = false;
  } catch (err) {
    regFlash.type = 'error';
    regFlash.message = err.response?.data?.message || 'Gagal mendaftarkan perwakilan peserta.';
  } finally {
    savingParticipants.value = false;
  }
}

const roundStatuses = reactive({ ...props.initialData.roundStatuses });
const qrStats = reactive({ ...props.initialData.qrStats });
const cipherStats = reactive({ ...props.initialData.cipherStats });

const scores = reactive({
  round_1: Number(session.score_round_1 || 0),
  round_2: Number(session.score_round_2 || 0),
  coin_balance: Number(session.coin_balance || 0),
});

const roadmapCards = computed(() => [
  {
    round: 1,
    title: 'QR-Hunt Trivia',
    subtitle: 'Isian Singkat Berebut (1 Soal = 1 Regu)',
    description: 'Pindai QR Code yang tersebar di bumi perkemahan menggunakan kamera. Jawab cepat sebelum diklaim regu lain!',
    metric1Label: 'QR Tersedia',
    metric1Value: `${qrStats.available} / ${qrStats.total}`,
    metric2Label: 'Skor Regu B1',
    metric2Value: `${scores.round_1} Poin`,
    url: routes.round1,
  },
  {
    round: 2,
    title: 'Cipher Word-Search',
    subtitle: 'Cari Kata Bersandi (Kotak 1, Kotak 2, Angka)',
    description: 'Terjemahkan simbol sandi pramuka dari matriks dinamis dan klaim kata tersembunyi lebih cepat dari regu lawan.',
    metric1Label: 'Kata Tersedia',
    metric1Value: `${cipherStats.available_words} / ${cipherStats.total_words}`,
    metric2Label: 'Skor Regu B2',
    metric2Value: `${scores.round_2} Poin`,
    url: routes.round2,
  },
  {
    round: 3,
    title: 'Final "Coin Royale"',
    subtitle: '9 Ronde Taruhan Taktis (Top 5 Regu)',
    description: 'Total poin B1 + B2 menjadi Saldo Koin Awal. 6 Ronde Normal (Wajib) dan 3 Ronde Spesial (3x / -2x Opsional).',
    metric1Label: 'Status Regu',
    metric1Value: session.is_verified_round_3 ? 'TOP 5 FINALIS' : 'Kualifikasi',
    metric2Label: 'Saldo Koin',
    metric2Value: `${scores.coin_balance} Koin`,
    url: routes.round3,
  },
]);

function cardBorderClass(status) {
  if (status === 'ACTIVE') return 'border-emerald-500 ring-4 ring-emerald-500/15';
  if (status === 'FINISHED') return 'border-blue-300';
  return 'border-gray-200 opacity-90';
}

function cardHeaderBg(status) {
  if (status === 'ACTIVE') return 'bg-[#1B4332]';
  if (status === 'FINISHED') return 'bg-blue-800';
  return 'bg-slate-700';
}

function badgeClass(status) {
  if (status === 'ACTIVE') return 'bg-amber-400 text-emerald-950 animate-pulse';
  if (status === 'FINISHED') return 'bg-blue-200 text-blue-950';
  return 'bg-black/30 text-gray-200';
}

function statusLabel(status) {
  if (status === 'ACTIVE') return 'AKTIF • BUKA';
  if (status === 'FINISHED') return 'SELESAI';
  return 'TERKUNCI';
}

let pollTimer = null;

async function syncStatusFallback() {
  try {
    const { data } = await axios.get(routes.checkStatus);
    if (data.round_statuses) {
      Object.assign(roundStatuses, data.round_statuses);
    }
  } catch {
    // ignore
  }
}

onMounted(() => {
  if (echo) {
    if (echo.connector?.pusher?.connection) {
      echo.connector.pusher.connection.bind('connected', () => { wsConnected.value = true; });
      echo.connector.pusher.connection.bind('disconnected', () => { wsConnected.value = false; });
      if (echo.connector.pusher.connection.state === 'connected') wsConnected.value = true;
    }

    echo.join('quiz-arena')
      .listen('.RoundStatusChanged', (e) => {
        if (e.allStatuses) {
          Object.assign(roundStatuses, e.allStatuses);
        } else if (e.round && e.status) {
          roundStatuses[`round_${e.round}`] = e.status;
        }
        if (e.message) {
          liveNotification.value = e.message;
        }
      })
      .listen('.QrQuestionClaimed', (e) => {
        qrStats.available = Math.max(0, qrStats.available - 1);
        if (e.reguId === regu.id) {
          scores.round_1 = e.newRound1Score;
        }
      })
      .listen('.CipherWordClaimed', (e) => {
        cipherStats.available_words = Math.max(0, cipherStats.available_words - 1);
        if (e.reguId === regu.id) {
          scores.round_2 = e.newRound2Score;
        }
      })
      .listen('.CoinRoyaleUpdated', (e) => {
        const me = (e.state?.finalists || []).find(f => f.regu_id === regu.id);
        if (me) {
          session.is_verified_round_3 = true;
          session.coin_initial = me.coin_initial;
          scores.coin_balance = me.coin_balance;
        }
      });
  }

  pollTimer = setInterval(syncStatusFallback, 10000);
});

onUnmounted(() => {
  if (pollTimer) clearInterval(pollTimer);
});
</script>
