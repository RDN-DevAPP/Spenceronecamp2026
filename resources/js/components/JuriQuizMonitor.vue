<template>
  <div class="juri-monitor-dashboard space-y-8">
    <!-- Top Stats & Mass Controls -->
    <div class="bg-gradient-to-br from-forest-green via-emerald-900 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-2xl border border-emerald-700/40 relative overflow-hidden">
      <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
          <div class="flex items-center gap-2 mb-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-400/30">
              <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse mr-2"></span>
              WASIT SIBER REAL-TIME
            </span>
            <span class="text-xs text-emerald-200/80 font-mono">Channel: private-juri-monitor</span>
          </div>
          <h2 class="text-2xl sm:text-3xl font-black tracking-tight">Panel Monitoring & Kontrol Babak</h2>
          <p class="text-sm text-emerald-100/70 mt-1">Pantau matriks kehadiran peserta, progres jawaban, dan pelanggaran Anti-Cheat seketika (&lt;100ms).</p>
        </div>

        <!-- Mass Round Action Buttons -->
        <div class="flex flex-wrap items-center gap-3">
          <button @click="startMassRound(1)"
                  :disabled="isStartingRound"
                  class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-black uppercase tracking-wider shadow-lg transition flex items-center gap-1.5 active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
            Mulai Babak 1
          </button>
          <button @click="startMassRound(2)"
                  :disabled="isStartingRound"
                  class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-black uppercase tracking-wider shadow-lg transition flex items-center gap-1.5 active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
            Mulai Babak 2
          </button>
          <button @click="startMassRound(3)"
                  :disabled="isStartingRound"
                  class="px-5 py-2.5 bg-purple-600 hover:bg-purple-500 text-white rounded-xl text-xs font-black uppercase tracking-wider shadow-lg transition flex items-center gap-1.5 active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
            Mulai Babak 3
          </button>
          <button @click="triggerSelectFinalists"
                  class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-emerald-950 rounded-xl text-xs font-black uppercase tracking-wider shadow-lg transition flex items-center gap-1.5 active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            Pilih Top 3 Finalis
          </button>
        </div>
      </div>
    </div>

    <!-- Live Participant Status Matrix -->
    <div>
      <div class="flex items-center justify-between mb-4">
        <div>
          <h3 class="text-xl font-black text-gray-900 flex items-center gap-2">
            Matriks Status Peserta Ujian
            <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 font-bold">{{ sessionsList.length }} Regu</span>
          </h3>
          <p class="text-xs text-gray-500">Hijau: Sedang Mengerjakan • Kuning: Peringatan 1 • Merah: Terkunci • Abu: Selesai</p>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <div v-for="s in sessionsList" :key="s.id"
             class="rounded-2xl border p-5 transition-all duration-200 relative overflow-hidden shadow-sm"
             :class="getStatusCardClass(s)">
          
          <div class="flex items-start justify-between gap-2 mb-3">
            <div>
              <span class="text-[10px] font-black uppercase tracking-widest px-2 py-0.5 rounded-md"
                    :class="getStatusBadgeClass(s)">
                {{ getStatusText(s) }}
              </span>
              <h4 class="text-base font-black text-gray-900 mt-1.5 truncate max-w-[180px]">{{ s.regu_name }}</h4>
              <p class="text-xs text-gray-500 font-mono">Regu {{ s.nomor_regu || '-' }} • Babak {{ s.current_round || 1 }}</p>
            </div>

            <!-- Progress circular / pill -->
            <div class="text-right">
              <span class="text-xs font-black font-mono text-gray-700">{{ s.answered_count || 0 }}/{{ s.total_questions || 10 }}</span>
              <p class="text-[10px] text-gray-400">Terjawab</p>
            </div>
          </div>

          <!-- Live Violation Pill if Warning/Locked -->
          <div v-if="s.warning_count > 0" class="mb-3 p-2 rounded-xl text-xs font-bold"
               :class="s.is_locked ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800'">
            <div class="flex items-center justify-between">
              <span>{{ s.is_locked ? '🚨 TERKUNCI' : '⚠️ PERINGATAN 1' }}</span>
              <span class="text-[10px]">{{ s.last_violation_time || 'Baru saja' }}</span>
            </div>
            <p class="text-[11px] font-normal mt-0.5 truncate">{{ s.last_violation_label || 'Pindah tab browser' }}</p>
          </div>

          <!-- Instant Unlock Button -->
          <div v-if="s.is_locked" class="mt-3">
            <button @click="instantUnlock(s)"
                    :disabled="s.isUnlocking"
                    class="w-full py-2 bg-red-600 hover:bg-red-500 active:scale-95 text-white rounded-xl text-xs font-black uppercase tracking-wider shadow transition flex items-center justify-center gap-1.5">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
              {{ s.isUnlocking ? 'Membuka...' : 'Buka Kunci Instan' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Live Real-Time Violation Stream Feed -->
    <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-black text-gray-900 flex items-center gap-2">
          <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
          Live Feed Pelanggaran Anti-Cheat
        </h3>
        <span class="text-xs text-gray-400 font-mono">Auto-update via WebSocket</span>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
          <thead>
            <tr class="text-left text-[11px] font-black uppercase tracking-wider text-gray-400">
              <th class="pb-3">Waktu</th>
              <th class="pb-3">Nama Regu</th>
              <th class="pb-3">Babak</th>
              <th class="pb-3">Jenis Pelanggaran</th>
              <th class="pb-3">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50 text-xs">
            <tr v-for="(v, i) in liveViolations" :key="i" class="hover:bg-gray-50/80 transition">
              <td class="py-3 font-mono text-gray-400">{{ v.time }}</td>
              <td class="py-3 font-bold text-gray-900">{{ v.regu_name }}</td>
              <td class="py-3"><span class="px-2 py-0.5 rounded bg-gray-100 font-bold">Babak {{ v.round }}</span></td>
              <td class="py-3 font-medium text-gray-700">{{ v.violation_label }}</td>
              <td class="py-3">
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider"
                      :class="v.is_frozen ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700'">
                  {{ v.is_frozen ? 'Layar Terkunci' : 'Peringatan 1' }}
                </span>
              </td>
            </tr>
            <tr v-if="liveViolations.length === 0">
              <td colspan="5" class="py-8 text-center text-gray-400 italic">Belum ada catatan pelanggaran baru.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import echo from '../echo';

const props = defineProps({
  initialSessions: { type: Array, default: () => [] },
  csrfToken: { type: String, required: true },
});

const sessionsList = ref([...props.initialSessions]);
const liveViolations = ref([]);
const isStartingRound = ref(false);

const getStatusCardClass = (s) => {
  if (s.is_locked) return 'bg-red-50/50 border-red-200';
  if (s.warning_count === 1) return 'bg-amber-50/50 border-amber-200';
  if (s.status?.includes('done') || s.status === 'finished') return 'bg-gray-50 border-gray-200 opacity-80';
  return 'bg-emerald-50/30 border-emerald-200';
};

const getStatusBadgeClass = (s) => {
  if (s.is_locked) return 'bg-red-600 text-white';
  if (s.warning_count === 1) return 'bg-amber-500 text-white';
  if (s.status?.includes('done') || s.status === 'finished') return 'bg-gray-400 text-white';
  return 'bg-emerald-600 text-white';
};

const getStatusText = (s) => {
  if (s.is_locked) return 'TERKUNCI';
  if (s.warning_count === 1) return 'PERINGATAN 1';
  if (s.status?.includes('done') || s.status === 'finished') return 'SELESAI';
  return 'AKTIF MENGERJAKAN';
};

// Instant Unlock
const instantUnlock = async (session) => {
  session.isUnlocking = true;
  try {
    const res = await fetch(`/juri/cerdas-cermat/${session.id}/instant-unlock`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': props.csrfToken,
        'Accept': 'application/json'
      }
    });
    const data = await res.json();
    if (data.success) {
      session.is_locked = false;
      session.warning_count = 0;
    }
  } catch (e) {
    alert('Gagal membuka kunci.');
  } finally {
    session.isUnlocking = false;
  }
};

// Mass Round Start
const startMassRound = async (round) => {
  if (!confirm(`Yakin ingin memulai Babak ${round} serentak untuk seluruh peserta?`)) return;
  isStartingRound.value = true;
  try {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/juri/cerdas-cermat/start-round/${round}`;
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = props.csrfToken;
    form.appendChild(csrfInput);
    document.body.appendChild(form);
    form.submit();
  } catch (e) {
    isStartingRound.value = false;
  }
};

// Select Top 3 Finalists
const triggerSelectFinalists = async () => {
  if (!confirm('Pilih otomatis Top 3 Finalis Babak Rebutan berdasarkan total nilai kualifikasi & waktu tersingkat?')) return;
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '/juri/cerdas-cermat/select-finalists';
  const csrfInput = document.createElement('input');
  csrfInput.type = 'hidden';
  csrfInput.name = '_token';
  csrfInput.value = props.csrfToken;
  form.appendChild(csrfInput);
  document.body.appendChild(form);
  form.submit();
};

onMounted(() => {
  // Listen to private-juri-monitor
  echo.private('juri-monitor')
    .listen('.ExamViolationLogged', (e) => {
      const s = sessionsList.value.find(item => item.id === e.sessionId);
      if (s) {
        s.warning_count = e.violationCount;
        s.is_locked = e.isFrozen;
        s.last_violation_label = e.violationLabel;
        s.last_violation_time = new Date().toLocaleTimeString('id-ID');
      }

      liveViolations.value.unshift({
        time: new Date().toLocaleTimeString('id-ID'),
        regu_name: e.reguName,
        round: e.round,
        violation_label: e.violationLabel,
        is_frozen: e.isFrozen
      });
    })
    .listen('.AnswerAutosaved', (e) => {
      const s = sessionsList.value.find(item => item.id === e.sessionId);
      if (s) {
        s.answered_count = e.answeredCount;
        s.total_questions = e.totalQuestions;
      }
    })
    .listen('.SessionUnlocked', (e) => {
      const s = sessionsList.value.find(item => item.id === e.sessionId);
      if (s) {
        s.is_locked = false;
        s.warning_count = 0;
      }
    });
});
</script>
