<template>
  <div class="juri-grading-wrapper max-w-5xl mx-auto py-8 px-4">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
      <div>
        <div class="flex items-center gap-2 mb-1">
          <span class="px-2.5 py-0.5 rounded-full text-xs font-black uppercase tracking-wider bg-emerald-100 text-emerald-800">
            PENILAIAN BABAK {{ round }}
          </span>
          <span class="text-xs text-gray-400">•</span>
          <span class="text-xs font-bold text-gray-500">Regu: {{ reguName }}</span>
        </div>
        <h1 class="text-2xl font-black text-gray-900">Grading Interaktif Cepat (1-Click)</h1>
      </div>
      <div class="text-right">
        <span class="text-xs text-gray-400 font-mono uppercase">Total Nilai Sementara</span>
        <div class="text-3xl font-black text-emerald-700">{{ calculatedTotal }} Poin</div>
      </div>
    </div>

    <!-- Questions Grading Grid: 2 Columns (Peserta vs Kunci Jawaban) -->
    <div class="space-y-6">
      <div v-for="(item, index) in localAnswers" :key="item.id"
           class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 overflow-hidden transition"
           :class="item.score > 0 ? 'border-l-8 border-l-emerald-500' : (item.score === 0 && item.graded ? 'border-l-8 border-l-rose-400' : '')">
        
        <!-- Question Prompt -->
        <div class="flex items-start gap-3 mb-5">
          <span class="w-7 h-7 rounded-xl bg-gray-100 text-gray-800 text-xs font-black flex items-center justify-center flex-shrink-0">
            {{ index + 1 }}
          </span>
          <div class="flex-1">
            <h4 class="text-base font-bold text-gray-900 leading-snug">{{ item.question_text }}</h4>
            <div class="text-xs text-gray-400 mt-1">Skor Maksimal Soal: {{ item.max_score }} Poin</div>
          </div>
        </div>

        <!-- 2 Columns: Jawaban Peserta vs Kunci Jawaban Resmi -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
          <!-- Kolom Jawaban Peserta -->
          <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100">
            <span class="text-[10px] font-black uppercase tracking-widest text-indigo-700 block mb-1">
              Jawaban Asli Peserta:
            </span>
            <p class="text-sm font-semibold font-mono text-indigo-950 break-words whitespace-pre-wrap">
              {{ item.answer_text || '(Tidak dijawab)' }}
            </p>
          </div>

          <!-- Kolom Kunci Jawaban & Rubrik Resmi -->
          <div class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-100">
            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-700 block mb-1">
              Kunci Jawaban Resmi & Rubrik:
            </span>
            <p class="text-sm font-bold text-emerald-950">
              {{ item.correct_answer }}
            </p>
          </div>
        </div>

        <!-- 1-Click Score Action Bar -->
        <div class="flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-gray-100">
          <div class="flex items-center gap-2">
            <button @click="setScore(item, item.max_score)"
                    type="button"
                    class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition active:scale-95"
                    :class="item.score === item.max_score ? 'bg-emerald-600 text-white shadow' : 'bg-gray-100 hover:bg-emerald-100 text-emerald-800'">
              ✓ Benar Penuh ({{ item.max_score }})
            </button>
            <button @click="setScore(item, Math.round(item.max_score / 2))"
                    type="button"
                    class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition active:scale-95"
                    :class="item.score === Math.round(item.max_score / 2) && item.score > 0 ? 'bg-amber-500 text-white shadow' : 'bg-gray-100 hover:bg-amber-100 text-amber-800'">
              ½ Sebagian ({{ Math.round(item.max_score / 2) }})
            </button>
            <button @click="setScore(item, 0)"
                    type="button"
                    class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition active:scale-95"
                    :class="item.score === 0 && item.graded ? 'bg-rose-600 text-white shadow' : 'bg-gray-100 hover:bg-rose-100 text-rose-800'">
              ✕ Salah (0)
            </button>
          </div>

          <!-- Manual Score Input -->
          <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-gray-500">Skor Kustom:</span>
            <input type="number" min="0" :max="item.max_score"
                   v-model.number="item.score"
                   @input="item.graded = true"
                   class="w-20 px-3 py-1.5 rounded-xl border border-gray-300 font-bold text-center text-sm focus:ring-2 focus:ring-emerald-500">
            <span class="text-xs text-gray-400">/ {{ item.max_score }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Finalize Button -->
    <div class="mt-8 p-6 bg-white rounded-3xl border border-gray-200 shadow-xl flex items-center justify-between gap-4">
      <div>
        <h4 class="text-base font-black text-gray-900">Simpan Seluruh Nilai</h4>
        <p class="text-xs text-gray-500">Nilai akan langsung diperbarui ke papan skor secara real-time via WebSocket.</p>
      </div>
      <button @click="submitAllScores"
              :disabled="isSubmitting"
              class="px-8 py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-black text-sm uppercase tracking-wider shadow-lg transition active:scale-95 flex items-center gap-2">
        <span v-if="isSubmitting">Menyimpan...</span>
        <span v-else>Simpan & Finalkan Nilai</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  sessionId: { type: Number, required: true },
  round: { type: Number, required: true },
  reguName: { type: String, default: 'Regu' },
  answers: { type: Array, default: () => [] },
  csrfToken: { type: String, required: true },
  saveUrl: { type: String, required: true },
});

const localAnswers = ref(props.answers.map(a => ({
  id: a.id,
  question_text: a.question?.question || a.question_text,
  correct_answer: a.question?.correct_answer || a.correct_answer,
  max_score: a.question?.score || a.max_score || 10,
  answer_text: a.answer_text,
  score: a.score ?? 0,
  graded: a.score !== null && a.score !== undefined,
})));

const isSubmitting = ref(false);

const calculatedTotal = computed(() => {
  return localAnswers.value.reduce((acc, cur) => acc + (Number(cur.score) || 0), 0);
});

const setScore = (item, val) => {
  item.score = val;
  item.graded = true;
};

const submitAllScores = async () => {
  isSubmitting.value = true;
  const scoresObj = {};
  localAnswers.value.forEach(a => {
    scoresObj[a.id] = a.score;
  });

  try {
    const res = await fetch(props.saveUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': props.csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify({ scores: scoresObj })
    });
    const data = await res.json();
    if (res.ok && data.success) {
      alert('Nilai berhasil disimpan dan dibroadcast ke sistem live!');
      window.location.href = '/juri/cerdas-cermat';
    } else {
      alert('Gagal menyimpan nilai.');
    }
  } catch (e) {
    alert('Terjadi kesalahan jaringan.');
  } finally {
    isSubmitting.value = false;
  }
};
</script>
