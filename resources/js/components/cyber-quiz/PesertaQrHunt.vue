<template>
  <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">
    <!-- Top Arena Bar -->
    <div style="background: linear-gradient(135deg, #1B4332 0%, #2D6A4F 100%);" class="text-white rounded-2xl p-5 shadow-lg flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <div class="flex items-center gap-2 mb-1">
          <a :href="routes.roadmap" class="text-xs font-bold text-amber-300 hover:underline">&larr; Kembali ke Roadmap</a>
          <span class="text-white/40">|</span>
          <span class="text-[11px] font-bold text-emerald-200">BABAK 1: QR-HUNT TRIVIA</span>
        </div>
        <h1 class="text-xl sm:text-2xl font-black uppercase">{{ regu.nama_regu }}</h1>
      </div>

      <div class="flex items-center gap-3">
        <div class="bg-black/25 rounded-xl px-4 py-2 text-center border border-white/10">
          <div class="text-[10px] uppercase font-bold text-emerald-200">QR Tersedia</div>
          <div class="text-lg font-mono font-black">{{ availableQr }} / {{ totalQr }}</div>
        </div>
        <div class="bg-amber-400 text-emerald-950 rounded-xl px-4 py-2 text-center shadow-md relative">
          <div class="text-[10px] uppercase font-black">Skor Babak 1</div>
          <div class="text-2xl font-mono font-black">{{ currentScore }}</div>
          <transition name="pop">
            <span
              v-if="pointGainBadge"
              class="absolute -top-4 -right-2 px-2 py-0.5 rounded-full bg-emerald-600 text-white text-xs font-black shadow-lg animate-bounce"
            >
              +{{ pointGainBadge }}
            </span>
          </transition>
        </div>
      </div>
    </div>

    <!-- Claimed Warning Alert -->
    <div
      v-if="alertState.message"
      class="p-4 rounded-2xl border-2 flex items-start justify-between gap-3 shadow-sm"
      :class="alertState.type === 'error' ? 'bg-red-50 border-red-400 text-red-900' : (alertState.type === 'warning' ? 'bg-amber-50 border-amber-400 text-amber-950' : 'bg-emerald-50 border-emerald-400 text-emerald-900')"
    >
      <div class="font-bold text-sm">{{ alertState.message }}</div>
      <button type="button" @click="alertState.message = ''" class="text-xs font-black underline shrink-0 cursor-pointer">Tutup</button>
    </div>

    <!-- Active Scanned Question Modal / Panel -->
    <div v-if="activeQuestion" class="bg-white rounded-3xl border-2 border-[#1B4332] shadow-xl p-6 space-y-5">
      <div class="flex items-center justify-between border-b border-gray-100 pb-3">
        <div class="flex items-center gap-2">
          <span class="px-3 py-1 rounded-full bg-[#1B4332] text-white text-xs font-black">{{ activeQuestion.code_label }}</span>
          <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-900 text-xs font-black border border-amber-300">
            +{{ activeQuestion.points }} POIN
          </span>
        </div>
        <button type="button" @click="cancelQuestion" class="text-xs font-bold text-gray-500 hover:text-gray-800 cursor-pointer">
          Scan QR Lain
        </button>
      </div>

      <div v-if="activeQuestion.image_url" class="flex justify-center">
        <img :src="activeQuestion.image_url" alt="Ilustrasi Soal" class="max-h-60 rounded-2xl border border-gray-200 object-contain" />
      </div>

      <p class="text-base sm:text-lg font-bold text-gray-900 leading-relaxed">
        {{ activeQuestion.question }}
      </p>

      <form @submit.prevent="submitAnswer" class="space-y-3">
        <label class="block text-xs font-black uppercase tracking-wider text-gray-600">
          Masukkan Jawaban Singkat (Cepat &amp; Tepat!)
        </label>
        <div class="flex flex-col sm:flex-row gap-2">
          <input
            v-model="answerInput"
            type="text"
            required
            autofocus
            placeholder="Ketik jawaban Anda di sini..."
            class="flex-1 px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-[#1B4332] focus:outline-none text-base font-bold"
          />
          <button
            type="submit"
            :disabled="submitting"
            class="px-6 py-3 rounded-xl bg-[#1B4332] hover:bg-emerald-900 text-white font-black text-sm uppercase tracking-wider disabled:opacity-50 cursor-pointer transition shadow-md"
          >
            {{ submitting ? 'Memeriksa...' : 'Kunci & Klaim Poin' }}
          </button>
        </div>
      </form>
    </div>

    <!-- QR Camera Scanner Card (Opens immediately when entering Babak 1) -->
    <div v-show="!activeQuestion" class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
      <div class="px-5 py-4 bg-gray-50 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
        <div>
          <h2 class="text-sm font-black text-[#1B4332] uppercase">Pemindai Kamera QR-Hunt</h2>
          <p class="text-xs text-gray-500">Arahkan kamera ke kertas QR Code soal di pos lapangan.</p>
        </div>
        <button
          type="button"
          @click="toggleScanner"
          class="px-4 py-2 rounded-xl text-xs font-black cursor-pointer transition"
          :class="isScanning ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-[#1B4332] text-white'"
        >
          {{ isScanning ? 'Matikan Kamera' : 'Nyalakan Kamera' }}
        </button>
      </div>

      <div class="p-5 space-y-5">
        <div class="max-w-md mx-auto">
          <div id="qr-reader" class="w-full rounded-2xl overflow-hidden border-2 border-dashed border-emerald-300 bg-black/5 min-h-[260px]"></div>
          <p v-if="cameraError" class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-xl p-3 mt-3 font-semibold">
            {{ cameraError }}
          </p>
        </div>

        <!-- Manual Token Input Fallback -->
        <div class="max-w-md mx-auto pt-4 border-t border-gray-100">
          <label class="block text-[11px] font-bold uppercase text-gray-500 mb-1.5">
            Input Kode Pos / Token QR Manual (Cadangan jika kamera terkendala)
          </label>
          <form @submit.prevent="handleScanToken(manualToken)" class="flex gap-2">
            <input
              v-model="manualToken"
              type="text"
              placeholder="Contoh: QR-001 atau UUID token..."
              class="flex-1 px-3 py-2 rounded-xl border border-gray-300 text-xs font-mono font-bold"
            />
            <button
              type="submit"
              :disabled="submitting || !manualToken.trim()"
              class="px-4 py-2 rounded-xl bg-amber-400 hover:bg-amber-500 text-emerald-950 text-xs font-black cursor-pointer disabled:opacity-50"
            >
              Cek QR
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Live Claim Feed -->
    <div class="bg-white rounded-2xl border border-gray-200 p-5">
      <h3 class="text-xs font-black uppercase tracking-wider text-gray-500 mb-3">
        Aktivitas Klaim QR Tercepat (Live)
      </h3>
      <div v-if="recentClaims.length === 0" class="text-xs text-gray-400 text-center py-4">
        Belum ada soal QR yang diklaim. Jadilah regu pertama!
      </div>
      <div v-else class="space-y-2 max-h-48 overflow-y-auto">
        <div
          v-for="c in recentClaims"
          :key="c.id"
          class="flex items-center justify-between text-xs p-2.5 rounded-xl border"
          :class="c.regu_id === regu.id ? 'bg-emerald-50 border-emerald-200' : 'bg-gray-50 border-gray-100'"
        >
          <div>
            <span class="font-black text-[#1B4332]">{{ c.code_label }}</span>
            <span class="text-gray-600 ml-2">diklaim oleh <strong>{{ c.regu_name }}</strong></span>
          </div>
          <span class="font-mono font-black text-amber-700">+{{ c.points }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import confetti from 'canvas-confetti';
import echo from '../../echo';

const props = defineProps({
  initialData: { type: Object, required: true },
});

const regu = props.initialData.regu;
const routes = props.initialData.routes;
const currentScore = ref(Number(props.initialData.session.score_round_1 || 0));
const totalQr = ref(Number(props.initialData.totalQr || 0));
const availableQr = ref(Number(props.initialData.availableQr || 0));
const recentClaims = ref(props.initialData.recentClaims || []);

const isScanning = ref(false);
const cameraError = ref('');
const manualToken = ref('');
const activeQuestion = ref(null);
const answerInput = ref('');
const submitting = ref(false);
const pointGainBadge = ref(null);

const alertState = reactive({ type: 'info', message: '' });

let qrScanner = null;
let scanCooldown = false;

function showAlert(message, type = 'info') {
  alertState.type = type;
  alertState.message = message;
}

async function startScanner() {
  if (typeof window.Html5Qrcode === 'undefined') {
    cameraError.value = 'Library pemindai kamera belum termuat. Anda dapat menggunakan input kode pos/token manual di bawah.';
    return;
  }

  try {
    if (!qrScanner) {
      qrScanner = new window.Html5Qrcode('qr-reader');
    }
    await qrScanner.start(
      { facingMode: 'environment' },
      { fps: 10, qrbox: { width: 230, height: 230 } },
      (decodedText) => {
        if (scanCooldown || activeQuestion.value) return;
        scanCooldown = true;
        handleScanToken(decodedText).finally(() => {
          setTimeout(() => { scanCooldown = false; }, 1500);
        });
      },
      () => {}
    );
    isScanning.value = true;
    cameraError.value = '';
  } catch (err) {
    isScanning.value = false;
    cameraError.value = 'Tidak dapat mengakses kamera otomatis (periksa izin kamera browser). Gunakan input kode pos manual di bawah.';
  }
}

async function stopScanner() {
  if (qrScanner && isScanning.value) {
    try {
      await qrScanner.stop();
    } catch {
      // ignore
    }
    isScanning.value = false;
  }
}

function toggleScanner() {
  if (isScanning.value) {
    stopScanner();
  } else {
    startScanner();
  }
}

async function handleScanToken(token) {
  const clean = String(token || '').trim();
  if (!clean) return;
  submitting.value = true;
  alertState.message = '';

  try {
    const { data } = await axios.post(routes.scan, { qr_token: clean });
    if (data.success && data.question) {
      activeQuestion.value = data.question;
      answerInput.value = '';
      await stopScanner();
    }
  } catch (err) {
    const status = err.response?.status;
    const msg = err.response?.data?.message || 'Gagal memindai QR Code.';
    showAlert(msg, status === 409 ? 'warning' : 'error');
  } finally {
    submitting.value = false;
  }
}

async function submitAnswer() {
  if (!activeQuestion.value || !answerInput.value.trim()) return;
  submitting.value = true;

  try {
    const { data } = await axios.post(routes.answer, {
      qr_token: activeQuestion.value.qr_token,
      answer: answerInput.value,
    });

    if (data.success && data.is_correct) {
      currentScore.value = data.new_score;
      pointGainBadge.value = data.points_awarded;
      setTimeout(() => { pointGainBadge.value = null; }, 3000);

      try {
        confetti({ particleCount: 70, spread: 70, origin: { y: 0.65 } });
      } catch {}

      showAlert(data.message, 'success');
      activeQuestion.value = null;
      answerInput.value = '';
      startScanner();
    }
  } catch (err) {
    const status = err.response?.status;
    const msg = err.response?.data?.message || 'Jawaban gagal dikirim.';
    if (status === 409) {
      showAlert(msg, 'warning');
      activeQuestion.value = null;
      startScanner();
    } else {
      showAlert(msg, 'error');
    }
  } finally {
    submitting.value = false;
  }
}

function cancelQuestion() {
  activeQuestion.value = null;
  startScanner();
}

onMounted(() => {
  startScanner();

  if (echo) {
    echo.join('quiz-arena')
      .listen('.QrQuestionClaimed', (e) => {
        availableQr.value = Math.max(0, availableQr.value - 1);
        recentClaims.value.unshift({
          id: e.questionId,
          code_label: e.codeLabel,
          points: e.points,
          regu_id: e.reguId,
          regu_name: e.reguName,
          claimed_at: e.claimedAt,
        });
        if (activeQuestion.value && activeQuestion.value.id === e.questionId && e.reguId !== regu.id) {
          showAlert(`Soal ini sudah diklaim oleh Regu ${e.reguName}!`, 'warning');
          activeQuestion.value = null;
          startScanner();
        }
      })
      .listen('.RoundStatusChanged', (e) => {
        if (e.round === 1 && e.status !== 'ACTIVE') {
          window.location.href = routes.roadmap;
        }
      });
  }
});

onUnmounted(() => {
  stopScanner();
});
</script>
