<template>
  <div class="quiz-exam-wrapper max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Real-time Top Header Status Bar -->
    <div class="bg-gradient-to-r from-forest-green to-emerald-900 text-white px-5 py-4 rounded-2xl border border-emerald-700/40 shadow-xl mb-6 sticky top-4 z-30 flex flex-wrap items-center justify-between gap-4 backdrop-blur-md">
      <div>
        <div class="flex items-center gap-2 mb-1">
          <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-400/30">
            BABAK {{ currentRound }}
          </span>
          <span v-if="wsConnected" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse mr-1"></span> Live Sync
          </span>
          <span v-else class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-yellow-500/20 text-yellow-300 border border-yellow-400/30">
            <span class="w-1.5 h-1.5 rounded-full bg-yellow-400 mr-1"></span> Menghubungkan...
          </span>
        </div>
        <h1 class="text-lg sm:text-xl font-black tracking-tight">{{ roundTitle }}</h1>
      </div>

      <div class="flex items-center gap-4">
        <!-- Autosave Indicator Status -->
        <div class="hidden sm:flex items-center text-xs font-semibold px-3 py-1.5 rounded-xl bg-black/20 border border-white/10">
          <span v-if="saveStatus === 'saving'" class="text-amber-300 flex items-center">
            <svg class="animate-spin -ml-0.5 mr-1.5 h-3.5 w-3.5 text-amber-300" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            Menyimpan ke cloud...
          </span>
          <span v-else-if="saveStatus === 'saved'" class="text-emerald-300 flex items-center">
            <svg class="w-3.5 h-3.5 mr-1 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Tersimpan di cloud
          </span>
          <span v-else-if="saveStatus === 'error'" class="text-rose-300 flex items-center">
            <span class="w-2 h-2 rounded-full bg-rose-400 mr-1.5"></span> Gagal menyimpan (offline)
          </span>
          <span v-else class="text-gray-300 flex items-center">
            <svg class="w-3.5 h-3.5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 00-9.78 2.096A4.001 4.001 0 003 15z"></path>
            </svg>
            {{ answeredCount }}/{{ totalQuestions }} Terisi
          </span>
        </div>

        <!-- Timer Display -->
        <div class="font-mono text-xl sm:text-2xl px-4 py-1.5 rounded-xl font-black border shadow-inner flex items-center gap-2"
             :class="remainingSeconds < 300 ? 'bg-red-500/20 text-red-300 border-red-500/40 animate-pulse' : 'bg-black/30 text-amber-300 border-amber-400/30'">
          <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          {{ formattedTimer }}
        </div>
      </div>
    </div>

    <!-- Tiered Warning Banner (Pelanggaran Ke-1: Banner Kuning) -->
    <div v-if="warningCount === 1 && !isLocked" class="mb-6 p-4 rounded-2xl bg-amber-50 border-2 border-amber-300 shadow-md flex items-start gap-3 animate-bounce-short">
      <div class="p-2 rounded-xl bg-amber-100 text-amber-700 flex-shrink-0">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
      </div>
      <div class="flex-1">
        <div class="flex items-center justify-between">
          <h4 class="text-sm font-black text-amber-900 uppercase tracking-wide">Peringatan Anti-Cheat Ke-1</h4>
          <span class="text-xs font-bold text-amber-700 bg-amber-100 px-2.5 py-0.5 rounded-full">Toleransi Terakhir</span>
        </div>
        <p class="text-xs text-amber-800 mt-1 leading-relaxed">
          Jangan tinggalkan tab atau layar ujian! Terdeteksi aktivitas keluar aplikasi/inspeksi. <strong>Pelanggaran ke-2 akan langsung membekukan dan mengunci ujian Anda.</strong>
        </p>
      </div>
    </div>

    <!-- Questions Container -->
    <div class="space-y-6">
      <div v-for="(question, index) in questions" :key="question.id"
           class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition p-5 sm:p-7 relative overflow-hidden group">
        <div class="flex items-start gap-3.5 mb-4">
          <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-800 font-black text-sm flex items-center justify-center flex-shrink-0 border border-emerald-100 shadow-sm">
            {{ index + 1 }}
          </span>
          <div class="flex-1">
            <p class="text-base sm:text-lg font-bold text-gray-900 leading-snug">{{ question.question }}</p>
            <div class="flex items-center gap-2 mt-2">
              <span class="text-[10px] font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md">Bobot: {{ question.score }} Poin</span>
            </div>
          </div>
        </div>

        <!-- Question Illustration Image if present -->
        <div v-if="question.image_path" class="mb-5 ml-11">
          <img :src="'/storage/' + question.image_path" alt="Ilustrasi Soal"
               class="max-h-64 object-contain rounded-xl border border-gray-200 shadow-sm bg-gray-50">
        </div>

        <!-- Round 1: Multiple Choice Options -->
        <div v-if="currentRound === 1 && question.options" class="ml-0 sm:ml-11 grid grid-cols-1 gap-2.5">
          <label v-for="(optText, optKey) in question.options" :key="optKey"
                 class="flex items-center p-3.5 rounded-xl border transition cursor-pointer select-none"
                 :class="answers[question.id] === optKey
                   ? 'border-emerald-600 bg-emerald-50/60 ring-2 ring-emerald-500/20 text-emerald-950 font-semibold'
                   : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50 text-gray-700'">
            <input type="radio" :name="'question_' + question.id" :value="optKey"
                   :checked="answers[question.id] === optKey"
                   @change="handleRadioChange(question.id, optKey)"
                   class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
            <span class="ml-3 text-sm sm:text-base">
              <span class="uppercase font-black mr-2 opacity-70">{{ optKey }}.</span> {{ optText }}
            </span>
          </label>
        </div>

        <!-- Round 2: Short Answer Input -->
        <div v-else-if="currentRound === 2" class="ml-0 sm:ml-11">
          <div class="relative">
            <input type="text"
                   :value="answers[question.id] || ''"
                   @input="handleTextInput(question.id, $event.target.value)"
                   placeholder="Ketik jawaban singkat di sini..."
                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-gray-900 text-sm font-medium transition shadow-sm placeholder-gray-400">
            <span class="absolute right-3 top-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest pointer-events-none">
              Isian Singkat
            </span>
          </div>
        </div>

        <!-- Round 3: Essay Textarea -->
        <div v-else-if="currentRound === 3" class="ml-0 sm:ml-11">
          <textarea rows="4"
                    :value="answers[question.id] || ''"
                    @input="handleTextInput(question.id, $event.target.value)"
                    placeholder="Tuliskan elaborasi jawaban lengkap regu Anda..."
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-gray-900 text-sm font-medium transition shadow-sm placeholder-gray-400"></textarea>
        </div>
      </div>
    </div>

    <!-- Submit Action Footer -->
    <div class="mt-8 p-6 bg-white rounded-2xl border border-gray-100 shadow-lg flex flex-col sm:flex-row items-center justify-between gap-4">
      <div>
        <h4 class="text-sm font-bold text-gray-900">Selesai Mengerjakan?</h4>
        <p class="text-xs text-gray-500 mt-0.5">Pastikan seluruh soal telah dijawab dengan teliti sebelum mengirim akhir.</p>
      </div>
      <button @click="confirmManualSubmit"
              class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-emerald-600 to-forest-green text-white rounded-xl font-bold text-sm shadow-md hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] transition flex items-center justify-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
        </svg>
        Selesaikan & Kirim Ujian
      </button>
    </div>

    <!-- FULLSCREEN FREEZE MODAL (Pelanggaran Ke-2: Ujian Terkunci Total) -->
    <div v-if="isLocked" class="fixed inset-0 bg-black/90 backdrop-blur-md z-50 flex items-center justify-center p-4">
      <div class="bg-gradient-to-b from-gray-900 to-red-950 border-2 border-red-600 rounded-3xl p-8 max-w-md w-full text-center shadow-2xl text-white">
        <div class="w-20 h-20 rounded-full bg-red-600/20 border-2 border-red-500 flex items-center justify-center mx-auto mb-5 text-red-500 animate-pulse">
          <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m11-3V9a2 2 0 00-2-2M5 13V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
          </svg>
        </div>
        <h2 class="text-2xl font-black tracking-tight text-white mb-2">UJIAN DIBEKUKAN</h2>
        <p class="text-xs text-red-200/80 leading-relaxed mb-6">
          Sistem Anti-Cheat mendeteksi pelanggaran berulang. Layar Anda terkunci demi integritas kompetisi. Hubungi Dewan Juri untuk membuka kunci instan atau verifikasi manual.
        </p>

        <form @submit.prevent="verifyUnlockCode" class="space-y-4">
          <div>
            <label class="block text-[10px] font-black uppercase tracking-widest text-red-300 mb-2">
              KODE VERIFIKASI DARI JURI:
            </label>
            <input type="text" v-model="unlockCodeInput" maxlength="6"
                   placeholder="000000"
                   class="w-full text-center tracking-[0.4em] font-mono text-2xl font-black py-3 rounded-xl bg-black/50 border-2 border-red-500/60 text-white focus:outline-none focus:border-red-400">
          </div>
          <p v-if="unlockError" class="text-xs font-bold text-red-400">{{ unlockError }}</p>
          <button type="submit" :disabled="isVerifyingCode"
                  class="w-full py-3 bg-red-600 hover:bg-red-500 text-white rounded-xl font-black text-sm uppercase tracking-wider shadow-lg transition">
            <span v-if="isVerifyingCode">Memverifikasi...</span>
            <span v-else>Buka Kunci Layar</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import echo from '../echo';

const props = defineProps({
  sessionId: { type: Number, required: true },
  reguId: { type: Number, required: true },
  currentRound: { type: Number, required: true },
  remainingSecondsInit: { type: Number, default: 3600 },
  questions: { type: Array, default: () => [] },
  csrfToken: { type: String, required: true },
});

const isLocked = ref(false);
const warningCount = ref(0);
const wsConnected = ref(false);
const saveStatus = ref('idle');
const unlockCodeInput = ref('');
const unlockError = ref('');
const isVerifyingCode = ref(false);
const remainingSeconds = ref(props.remainingSecondsInit);
const answers = ref({});

let timerInterval = null;
let debounceTimers = {};
let resizeTimeout = null;

const roundTitle = computed(() => {
  if (props.currentRound === 1) return 'Babak 1: Pilihan Ganda';
  if (props.currentRound === 2) return 'Babak 2: Isian Singkat';
  return 'Babak 3: Uraian & Rebutan';
});

const totalQuestions = computed(() => props.questions.length);
const answeredCount = computed(() => Object.values(answers.value).filter(v => v !== null && v !== undefined && v !== '').length);

const formattedTimer = computed(() => {
  const m = Math.floor(remainingSeconds.value / 60);
  const s = Math.floor(remainingSeconds.value % 60);
  return `${m < 10 ? '0' + m : m}:${s < 10 ? '0' + s : s}`;
});

// Restore answers from localStorage
const restoreLocalAnswers = () => {
  const prefix = `ans_r${props.currentRound}_s${props.sessionId}_`;
  for (let i = 0; i < localStorage.length; i++) {
    const key = localStorage.key(i);
    if (key && key.startsWith(prefix)) {
      const qId = key.replace(prefix, '').replace('answers[', '').replace(']', '');
      answers.value[qId] = localStorage.getItem(key);
    }
  }
};

// Autosave handler
const saveToServer = async (questionId, value) => {
  saveStatus.value = 'saving';
  try {
    const res = await fetch('/peserta/cerdas-cermat/autosave', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': props.csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        question_id: questionId,
        answer_text: value,
        round: props.currentRound,
      })
    });
    const data = await res.json();
    if (res.ok && data.success) {
      saveStatus.value = 'saved';
      setTimeout(() => {
        if (saveStatus.value === 'saved') saveStatus.value = 'idle';
      }, 2000);
    } else if (res.status === 423 || data.locked) {
      isLocked.value = true;
      saveStatus.value = 'error';
    } else {
      saveStatus.value = 'error';
    }
  } catch (e) {
    saveStatus.value = 'error';
  }
};

const handleRadioChange = (questionId, value) => {
  answers.value[questionId] = value;
  localStorage.setItem(`ans_r${props.currentRound}_s${props.sessionId}_answers[${questionId}]`, value);
  saveToServer(questionId, value);
};

const handleTextInput = (questionId, value) => {
  answers.value[questionId] = value;
  localStorage.setItem(`ans_r${props.currentRound}_s${props.sessionId}_answers[${questionId}]`, value);

  // Debounce 800ms
  clearTimeout(debounceTimers[questionId]);
  saveStatus.value = 'saving';
  debounceTimers[questionId] = setTimeout(() => {
    saveToServer(questionId, value);
  }, 800);
};

// Report Violation
const reportViolation = async (violationType, description) => {
  try {
    const res = await fetch('/peserta/cerdas-cermat/report-violation', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': props.csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        violation_type: violationType,
        round: props.currentRound,
        description: description
      })
    });
    const data = await res.json();
    if (data.success) {
      warningCount.value = data.warning_count;
      isLocked.value = data.is_locked;
    }
  } catch (e) {}
};

// Unlock code verification
const verifyUnlockCode = async () => {
  if (!unlockCodeInput.value) return;
  isVerifyingCode.value = true;
  unlockError.value = '';

  try {
    const res = await fetch('/peserta/cerdas-cermat/verify-unlock-code', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': props.csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify({ code: unlockCodeInput.value })
    });
    const data = await res.json();
    if (data.success) {
      isLocked.value = false;
      warningCount.value = 0;
      unlockCodeInput.value = '';
    } else {
      unlockError.value = data.message || 'Kode unlock tidak valid.';
    }
  } catch (e) {
    unlockError.value = 'Gagal menghubungi server.';
  } finally {
    isVerifyingCode.value = false;
  }
};

// Submit form
const confirmManualSubmit = () => {
  if (confirm('Yakin ingin menyelesaikan dan mengirim jawaban ujian sekarang?')) {
    const form = document.getElementById('examForm');
    if (form) form.submit();
  }
};

// Anti-cheat Listeners with 1s buffer on resize
const setupAntiCheat = () => {
  document.addEventListener('visibilitychange', () => {
    if (document.hidden && !isLocked.value) {
      reportViolation('tab_switch', 'Meninggalkan tab browser');
    }
  });

  window.addEventListener('blur', () => {
    setTimeout(() => {
      if (!document.hasFocus() && !isLocked.value) {
        reportViolation('tab_switch', 'Fokus jendela browser hilang');
      }
    }, 400);
  });

  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      // Buffer 1s passed, safe
    }, 1000);
  });

  ['copy', 'paste', 'cut', 'contextmenu'].forEach(evt => {
    document.addEventListener(evt, (e) => {
      e.preventDefault();
      reportViolation(evt === 'contextmenu' ? 'context_menu' : evt, `Mencoba melakukan ${evt}`);
    });
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'PrintScreen' || e.keyCode === 44) {
      e.preventDefault();
      reportViolation('screenshot', 'Menekan tombol PrintScreen');
    }
    if ((e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C')) || e.key === 'F12') {
      e.preventDefault();
      reportViolation('devtools', 'Membuka Developer Tools');
    }
  });
};

onMounted(() => {
  restoreLocalAnswers();
  setupAntiCheat();

  // Timer countdown
  timerInterval = setInterval(() => {
    if (remainingSeconds.value > 0) {
      remainingSeconds.value--;
    } else {
      clearInterval(timerInterval);
      const form = document.getElementById('examForm');
      if (form) form.submit();
    }
  }, 1000);

  // Echo WebSockets
  echo.private(`quiz-session.${props.sessionId}`)
    .listen('.SessionUnlocked', (e) => {
      isLocked.value = false;
      warningCount.value = 0;
    })
    .listen('.ExamViolationLogged', (e) => {
      warningCount.value = e.violationCount;
      isLocked.value = e.isFrozen;
    });

  echo.join('quiz-arena')
    .here(() => { wsConnected.value = true; })
    .listen('.RoundStatusChanged', (e) => {
      if (e.status === 'FROZEN') isLocked.value = true;
      if (e.remainingSeconds) remainingSeconds.value = e.remainingSeconds;
    });
});

onUnmounted(() => {
  if (timerInterval) clearInterval(timerInterval);
  Object.values(debounceTimers).forEach(t => clearTimeout(t));
});
</script>
