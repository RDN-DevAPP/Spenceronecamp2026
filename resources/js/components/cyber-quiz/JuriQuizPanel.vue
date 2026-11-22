<template>
  <div class="space-y-6">
    <!-- Top Header -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-5 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold uppercase tracking-wider mb-1">
          <span class="w-2 h-2 rounded-full" :class="wsConnected ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500'"></span>
          {{ wsConnected ? 'Reverb WebSocket Terhubung' : 'Menghubungkan Reverb...' }}
        </div>
        <h2 class="text-xl sm:text-2xl font-black text-[#1B4332] uppercase tracking-tight">
          Panel Kontrol Dewan Juri &amp; Wasit Coin Royale
        </h2>
      </div>

      <div class="flex flex-wrap gap-2">
        <button
          type="button"
          @click="showTranslator = !showTranslator"
          class="px-4 py-2 rounded-xl text-xs font-black text-emerald-950 bg-amber-400 hover:bg-amber-500 cursor-pointer transition shadow-xs"
        >
          {{ showTranslator ? 'Tutup Penerjemah Sandi' : 'Buka Penerjemah Sandi' }}
        </button>
        <a
          :href="routes.liveDisplay"
          target="_blank"
          class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#1E3A8A] hover:bg-blue-900 transition shadow-xs"
        >
          Buka Layar Proyektor (/live-display)
        </a>
        <button
          type="button"
          @click="refreshState"
          class="px-3.5 py-2 rounded-xl text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-200 cursor-pointer transition"
        >
          Refresh Data
        </button>
      </div>
    </div>

    <!-- Flash Message -->
    <div
      v-if="flash.message"
      class="px-4 py-3 rounded-xl text-sm font-bold flex items-center justify-between border"
      :class="flash.type === 'error' ? 'bg-red-50 text-red-800 border-red-200' : 'bg-emerald-50 text-emerald-800 border-emerald-200'"
    >
      <span>{{ flash.message }}</span>
      <button type="button" @click="flash.message = ''" class="text-xs underline cursor-pointer">Tutup</button>
    </div>

    <!-- Penerjemah Sandi Widget (Toggleable) -->
    <div v-if="showTranslator">
      <CipherTranslator />
    </div>

    <!-- CENTRALIZED ROUND CONTROLLER (LOCKED / ACTIVE / FINISHED) -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-5">
      <h3 class="text-sm font-black text-[#1B4332] uppercase tracking-wider mb-4">
        Kontrol Terpusat Status Babak (Real-Time WebSocket)
      </h3>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div
          v-for="r in roundMeta"
          :key="r.num"
          class="rounded-2xl border-2 p-4 flex flex-col justify-between transition"
          :class="roundCardClass(roundStatuses[`round_${r.num}`])"
        >
          <div>
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-black uppercase tracking-wider text-gray-500">Babak {{ r.num }}</span>
              <span
                class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase"
                :class="statusBadgeClass(roundStatuses[`round_${r.num}`])"
              >
                {{ roundStatuses[`round_${r.num}`] }}
              </span>
            </div>
            <h4 class="text-base font-black text-[#1B4332]">{{ r.title }}</h4>
            <p class="text-xs text-gray-500 mt-1 mb-4">{{ r.desc }}</p>
          </div>

          <div class="grid grid-cols-3 gap-1.5">
            <button
              type="button"
              :disabled="loading"
              @click="changeRoundStatus(r.num, 'LOCKED')"
              class="py-2 rounded-xl text-[11px] font-black uppercase border cursor-pointer transition"
              :class="roundStatuses[`round_${r.num}`] === 'LOCKED' ? 'bg-gray-800 text-white border-gray-800' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-100'"
            >
              LOCKED
            </button>
            <button
              type="button"
              :disabled="loading"
              @click="changeRoundStatus(r.num, 'ACTIVE')"
              class="py-2 rounded-xl text-[11px] font-black uppercase border cursor-pointer transition"
              :class="roundStatuses[`round_${r.num}`] === 'ACTIVE' ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm' : 'bg-white text-emerald-700 border-emerald-300 hover:bg-emerald-50'"
            >
              ACTIVE
            </button>
            <button
              type="button"
              :disabled="loading"
              @click="changeRoundStatus(r.num, 'FINISHED')"
              class="py-2 rounded-xl text-[11px] font-black uppercase border cursor-pointer transition"
              :class="roundStatuses[`round_${r.num}`] === 'FINISHED' ? 'bg-blue-700 text-white border-blue-700' : 'bg-white text-blue-700 border-blue-300 hover:bg-blue-50'"
            >
              FINISHED
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation Tabs for Juri Workspace -->
    <div class="flex flex-wrap gap-2">
      <button
        v-for="tab in workspaceTabs"
        :key="tab.id"
        type="button"
        @click="activeWorkspace = tab.id"
        class="px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition cursor-pointer"
        :class="activeWorkspace === tab.id ? 'bg-[#1B4332] text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
      >
        {{ tab.label }}
      </button>
    </div>

    <!-- WORKSPACE 1: LIVE MONITOR BABAK 1 & 2 + KLASEMEN -->
    <div v-if="activeWorkspace === 'monitor'" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
      <!-- Klasemen Gabungan B1 + B2 -->
      <div class="lg:col-span-6 bg-white rounded-2xl border border-gray-200/80 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h3 class="text-sm font-black text-[#1B4332] uppercase">Klasemen Kualifikasi (B1 + B2)</h3>
            <p class="text-[11px] text-gray-500">Top 5 otomatis lolos ke Final Coin Royale (Tie-breaker: waktu tercepat)</p>
          </div>
          <button
            type="button"
            :disabled="loading"
            @click="qualifyTop5(false)"
            class="px-3 py-1.5 rounded-xl text-xs font-black text-emerald-950 bg-amber-400 hover:bg-amber-500 cursor-pointer transition"
          >
            Saring Top 5 Finalis
          </button>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full text-xs">
            <thead class="bg-gray-50 text-gray-500 uppercase font-bold">
              <tr>
                <th class="px-3 py-2 text-left">#</th>
                <th class="px-3 py-2 text-left">Regu</th>
                <th class="px-3 py-2 text-right">B1</th>
                <th class="px-3 py-2 text-right">B2</th>
                <th class="px-3 py-2 text-right">Total</th>
                <th class="px-3 py-2 text-center">Finalis</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr
                v-for="row in leaderboard"
                :key="row.session_id"
                :class="row.rank <= 5 ? 'bg-amber-50/40' : ''"
              >
                <td class="px-3 py-2.5 font-black text-[#1B4332]">{{ row.rank }}</td>
                <td class="px-3 py-2.5">
                  <div class="font-bold text-gray-900">{{ row.nama_regu }}</div>
                  <div class="text-[10px] text-gray-400 uppercase">{{ row.jenis }} • Regu {{ row.nomor_regu }}</div>
                </td>
                <td class="px-3 py-2.5 text-right font-mono font-bold">{{ row.score_round_1 }}</td>
                <td class="px-3 py-2.5 text-right font-mono font-bold">{{ row.score_round_2 }}</td>
                <td class="px-3 py-2.5 text-right font-mono font-black text-[#1B4332]">{{ row.b1_b2_total }}</td>
                <td class="px-3 py-2.5 text-center">
                  <span
                    v-if="row.is_verified_round_3"
                    class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-400 text-emerald-950"
                  >
                    TOP 5
                  </span>
                  <span v-else class="text-gray-300">-</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Live Feed QR-Hunt & Cipher Word-Search -->
      <div class="lg:col-span-6 space-y-6">
        <!-- Babak 1 QR Status -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-5">
          <h3 class="text-sm font-black text-[#1B4332] uppercase mb-3">
            Monitor Babak 1: QR-Hunt ({{ qrQuestions.filter(q => q.status === 'claimed').length }}/{{ qrQuestions.length }} Terklaim)
          </h3>
          <div class="max-h-56 overflow-y-auto space-y-2 pr-1">
            <div
              v-for="q in qrQuestions"
              :key="q.id"
              class="flex items-center justify-between p-2.5 rounded-xl border text-xs"
              :class="q.status === 'claimed' ? 'bg-amber-50 border-amber-200' : 'bg-gray-50 border-gray-200'"
            >
              <div class="min-w-0 pr-2">
                <span class="font-black text-[#1B4332] mr-2">{{ q.code_label }}</span>
                <span class="text-gray-600 truncate">{{ q.question }}</span>
              </div>
              <span
                class="shrink-0 px-2 py-0.5 rounded-full text-[10px] font-black"
                :class="q.status === 'claimed' ? 'bg-amber-200 text-amber-900' : 'bg-emerald-100 text-emerald-800'"
              >
                {{ q.status === 'claimed' ? `Regu ${q.claimed_by}` : 'Tersedia' }}
              </span>
            </div>
          </div>
        </div>

        <!-- Babak 2 Cipher Status -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-5">
          <h3 class="text-sm font-black text-[#1B4332] uppercase mb-3">
            Monitor Babak 2: Kata Sandi Terklaim
          </h3>
          <div class="space-y-3 max-h-60 overflow-y-auto pr-1">
            <div v-for="g in cipherGrids" :key="g.id" class="border border-gray-200 rounded-xl p-3">
              <div class="text-xs font-black text-[#1B4332] mb-2">{{ g.title }}</div>
              <div class="flex flex-wrap gap-1.5">
                <span
                  v-for="w in g.words"
                  :key="w.id"
                  class="px-2 py-1 rounded-lg text-[11px] font-mono font-bold border"
                  :class="w.is_claimed ? 'bg-amber-100 border-amber-300 text-amber-900 line-through' : 'bg-gray-50 border-gray-200 text-gray-600'"
                >
                  {{ w.word }} <span v-if="w.is_claimed" class="no-underline font-sans">({{ w.claimed_by }})</span>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- WORKSPACE 2: PANEL WASIT COIN ROYALE (9 RONDE) -->
    <div v-if="activeWorkspace === 'coin-royale'" class="space-y-6">
      <!-- Top 5 Finalists Bar -->
      <div style="background: linear-gradient(135deg, #0B2317 0%, #1B4332 100%);" class="text-white rounded-2xl p-5 shadow-md">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
          <div>
            <h3 class="text-base font-black text-amber-300 uppercase tracking-wide">
              Panel Wasit Final "Coin Royale" — Top 5 Finalis
            </h3>
            <p class="text-xs text-emerald-100/80">
              Saldo awal = Total Poin B1 + B2. Perubahan saldo otomatis disiarkan ke /live-display.
            </p>
          </div>
          <div class="flex flex-wrap gap-2">
            <button
              type="button"
              :disabled="loading"
              @click="qualifyTop5(true)"
              class="px-3.5 py-2 rounded-xl text-xs font-black text-emerald-950 bg-amber-400 hover:bg-amber-500 cursor-pointer"
            >
              Inisialisasi Ulang Top 5 &amp; Koin
            </button>
            <button
              type="button"
              :disabled="loading"
              @click="resetCoinRoyale"
              class="px-3.5 py-2 rounded-xl text-xs font-bold text-red-200 bg-red-900/60 hover:bg-red-800 border border-red-400/40 cursor-pointer"
            >
              Reset 9 Ronde
            </button>
          </div>
        </div>

        <div v-if="coinRoyale.finalists.length === 0" class="p-4 rounded-xl bg-black/25 text-center text-xs text-amber-200">
          Belum ada Top 5 Finalis yang ditetapkan. Klik "Inisialisasi Ulang Top 5 &amp; Koin" untuk menyaring otomatis dari Babak 1 + 2.
        </div>

        <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
          <div
            v-for="f in coinRoyale.finalists"
            :key="f.session_id"
            class="bg-white/10 backdrop-blur rounded-xl p-3 border border-white/15"
          >
            <div class="flex items-center justify-between text-[11px] font-black text-amber-300 mb-1">
              <span>RANK #{{ f.rank }}</span>
              <span>Awal: {{ f.coin_initial }}</span>
            </div>
            <div class="font-black text-sm truncate">{{ f.nama_regu }}</div>
            <div class="mt-2 text-2xl font-mono font-black text-white">
              {{ f.coin_balance }} <span class="text-xs text-amber-300 font-sans">KOIN</span>
            </div>
          </div>
        </div>
      </div>

      <!-- 9 Rounds Selector -->
      <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-5">
        <div class="text-xs font-black uppercase tracking-wider text-gray-500 mb-3">Pilih Ronde (1 - 9)</div>
        <div class="grid grid-cols-3 sm:grid-cols-9 gap-2 mb-6">
          <button
            v-for="r in coinRoyale.rounds"
            :key="r.round_number"
            type="button"
            @click="selectedRoundNumber = r.round_number"
            class="p-2.5 rounded-xl border-2 text-center cursor-pointer transition"
            :class="[
              selectedRoundNumber === r.round_number ? 'border-[#1B4332] ring-2 ring-[#1B4332]/20' : 'border-gray-200',
              r.phase === 'GRADED' ? 'bg-emerald-50' : (r.is_special ? 'bg-amber-50' : 'bg-white'),
            ]"
          >
            <div class="text-xs font-black text-[#1B4332]">R{{ r.round_number }}</div>
            <div class="text-[9px] font-black uppercase" :class="r.is_special ? 'text-amber-700' : 'text-gray-500'">
              {{ r.is_special ? 'SPESIAL' : 'NORMAL' }}
            </div>
            <div class="text-[9px] font-bold mt-1 text-gray-600">{{ r.phase }}</div>
          </button>
        </div>

        <!-- Active Selected Round Referee Console -->
        <div v-if="selectedRound" class="space-y-5 border-t border-gray-100 pt-5">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
              <div class="flex items-center gap-2">
                <h4 class="text-lg font-black text-[#1B4332]">
                  Ronde {{ selectedRound.round_number }}
                </h4>
                <span
                  class="px-2.5 py-0.5 rounded-full text-xs font-black uppercase"
                  :class="selectedRound.is_special ? 'bg-amber-400 text-emerald-950' : 'bg-emerald-100 text-emerald-800'"
                >
                  {{ selectedRound.is_special ? 'Ronde Spesial (Opsional: Benar 3x / Salah -2x)' : 'Ronde Normal (Wajib: Benar 2x / Salah -1x)' }}
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-blue-100 text-blue-800">
                  Fase: {{ selectedRound.phase }}
                </span>
              </div>
            </div>

            <!-- 4-Step Referee Action Buttons -->
            <div class="flex flex-wrap gap-2">
              <button
                type="button"
                :disabled="loading || selectedRound.phase === 'GRADED'"
                @click="openBetting(selectedRound.round_number)"
                class="px-3.5 py-2 rounded-xl text-xs font-black text-white bg-amber-500 hover:bg-amber-600 disabled:opacity-40 cursor-pointer"
              >
                1. Buka Taruhan
              </button>
              <button
                type="button"
                :disabled="loading || selectedRound.phase === 'GRADED'"
                @click="lockBetting(selectedRound.round_number)"
                class="px-3.5 py-2 rounded-xl text-xs font-black text-white bg-slate-700 hover:bg-slate-800 disabled:opacity-40 cursor-pointer"
              >
                2. Kunci Taruhan
              </button>
              <button
                type="button"
                :disabled="loading || selectedRound.phase === 'GRADED'"
                @click="openQuestion(selectedRound.round_number)"
                class="px-3.5 py-2 rounded-xl text-xs font-black text-white bg-blue-700 hover:bg-blue-800 disabled:opacity-40 cursor-pointer"
              >
                3. Tampilkan Soal
              </button>
            </div>
          </div>

          <!-- Question Editor for Round -->
          <div class="grid grid-cols-1 md:grid-cols-12 gap-3 bg-gray-50 p-4 rounded-xl border border-gray-200">
            <div class="md:col-span-5">
              <label class="block text-[11px] font-bold uppercase text-gray-500 mb-1">Teks Soal Ronde {{ selectedRound.round_number }}</label>
              <textarea
                v-model="questionDraft.question_text"
                rows="2"
                class="w-full px-3 py-2 rounded-xl border border-gray-300 bg-white text-sm"
                placeholder="Ketik soal yang akan ditampilkan saat fase Tampilkan Soal..."
              ></textarea>
            </div>
            <div class="md:col-span-3">
              <label class="block text-[11px] font-bold uppercase text-gray-500 mb-1">Kunci Jawaban (Juri)</label>
              <input
                v-model="questionDraft.correct_answer"
                type="text"
                class="w-full px-3 py-2 rounded-xl border border-gray-300 bg-white text-sm font-mono"
                placeholder="Jawaban benar..."
              />
            </div>
            <div class="md:col-span-2">
              <label class="block text-[11px] font-bold uppercase text-gray-500 mb-1">Gambar Soal</label>
              <div class="flex flex-col gap-1.5">
                <img
                  v-if="questionDraft.preview_url || (selectedRound.question_image_url && !questionDraft.remove_image)"
                  :src="questionDraft.preview_url || selectedRound.question_image_url"
                  alt="Preview Soal"
                  class="w-full h-14 object-contain rounded-lg border border-gray-200 bg-white"
                />
                <div class="flex items-center gap-1.5">
                  <label class="flex-1 text-center px-2 py-1.5 rounded-lg bg-white hover:bg-gray-100 border border-gray-300 text-[11px] font-bold text-gray-700 cursor-pointer">
                    <span>{{ (questionDraft.preview_url || (selectedRound.question_image_url && !questionDraft.remove_image)) ? 'Ganti' : '+ Gambar' }}</span>
                    <input type="file" accept="image/*" class="hidden" @change="onJuriImageSelect" />
                  </label>
                  <button
                    v-if="questionDraft.preview_url || (selectedRound.question_image_url && !questionDraft.remove_image)"
                    type="button"
                    @click="clearJuriRoundImage"
                    class="px-2 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 text-[11px] font-bold cursor-pointer"
                  >
                    Hapus
                  </button>
                </div>
              </div>
            </div>
            <div class="md:col-span-2 flex items-end">
              <button
                type="button"
                :disabled="loading"
                @click="saveRoundQuestion"
                class="w-full py-2.5 rounded-xl text-xs font-black text-[#1B4332] bg-emerald-100 hover:bg-emerald-200 cursor-pointer"
              >
                Simpan Soal
              </button>
            </div>
          </div>

          <!-- Top 5 Bets & Grading Table -->
          <div class="overflow-x-auto border border-gray-200 rounded-2xl">
            <table class="min-w-full text-xs">
              <thead class="bg-gray-50 text-gray-600 uppercase font-bold">
                <tr>
                  <th class="px-4 py-3 text-left">Finalis Top 5</th>
                  <th class="px-4 py-3 text-right">Saldo Saat Ini</th>
                  <th class="px-4 py-3 text-center">Status Taruhan</th>
                  <th class="px-4 py-3 text-right">Taruhan</th>
                  <th class="px-4 py-3 text-center">Penilaian Wasit</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100 bg-white">
                <tr v-for="f in coinRoyale.finalists" :key="f.session_id">
                  <td class="px-4 py-3 font-black text-gray-900">
                    #{{ f.rank }} {{ f.nama_regu }}
                  </td>
                  <td class="px-4 py-3 text-right font-mono font-black text-[#1B4332]">
                    {{ f.coin_balance }}
                  </td>
                  <td class="px-4 py-3 text-center">
                    <template v-if="getBetForSession(f.session_id)">
                      <span
                        class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase"
                        :class="getBetForSession(f.session_id).action === 'PASS' ? 'bg-gray-200 text-gray-700' : 'bg-amber-100 text-amber-900'"
                      >
                        {{ getBetForSession(f.session_id).action }}
                      </span>
                    </template>
                    <span v-else class="text-gray-400 italic">Menunggu...</span>
                  </td>
                  <td class="px-4 py-3 text-right font-mono font-bold text-amber-700">
                    {{ getBetForSession(f.session_id)?.bet_amount ?? 0 }}
                  </td>
                  <td class="px-4 py-3 text-center">
                    <template v-if="selectedRound.phase === 'GRADED'">
                      <span
                        class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase"
                        :class="{
                          'bg-emerald-100 text-emerald-800': getBetForSession(f.session_id)?.result === 'CORRECT',
                          'bg-red-100 text-red-800': getBetForSession(f.session_id)?.result === 'WRONG',
                          'bg-gray-100 text-gray-600': getBetForSession(f.session_id)?.result === 'PASS',
                        }"
                      >
                        {{ getBetForSession(f.session_id)?.result }} ({{ formatDelta(getBetForSession(f.session_id)?.coin_delta ?? 0) }})
                      </span>
                    </template>
                    <template v-else-if="selectedRound.is_special && getBetForSession(f.session_id)?.action === 'PASS'">
                      <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-600 text-[11px] font-bold">
                        PASS (Saldo Tetap)
                      </span>
                    </template>
                    <template v-else>
                      <div class="inline-flex gap-1.5">
                        <button
                          type="button"
                          @click="gradeSelections[f.session_id] = 'CORRECT'"
                          class="px-3 py-1.5 rounded-lg text-[11px] font-black cursor-pointer border transition"
                          :class="gradeSelections[f.session_id] === 'CORRECT' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-emerald-700 border-emerald-300 hover:bg-emerald-50'"
                        >
                          Benar
                        </button>
                        <button
                          type="button"
                          @click="gradeSelections[f.session_id] = 'WRONG'"
                          class="px-3 py-1.5 rounded-lg text-[11px] font-black cursor-pointer border transition"
                          :class="gradeSelections[f.session_id] === 'WRONG' ? 'bg-red-600 text-white border-red-600' : 'bg-white text-red-700 border-red-300 hover:bg-red-50'"
                        >
                          Salah
                        </button>
                      </div>
                    </template>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-if="selectedRound.phase !== 'GRADED'" class="flex justify-end">
            <button
              type="button"
              :disabled="loading || coinRoyale.finalists.length === 0"
              @click="submitGradeRound(selectedRound.round_number)"
              class="px-6 py-3 rounded-xl text-xs font-black uppercase tracking-wider text-white bg-[#1B4332] hover:bg-emerald-900 disabled:opacity-40 cursor-pointer shadow-md transition"
            >
              4. Hitung &amp; Sahkan Hasil Ronde {{ selectedRound.round_number }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue';
import axios from 'axios';
import echo from '../../echo';
import CipherTranslator from '../CipherTranslator.vue';

const props = defineProps({
  initialData: { type: Object, required: true },
});

const routes = props.initialData.routes;
const wsConnected = ref(false);
const loading = ref(false);
const showTranslator = ref(false);
const activeWorkspace = ref('monitor');
const flash = reactive({ type: 'success', message: '' });

const roundStatuses = reactive({ ...props.initialData.round_statuses });
const qrQuestions = ref(props.initialData.qr_questions || []);
const cipherGrids = ref(props.initialData.cipher_grids || []);
const leaderboard = ref(props.initialData.leaderboard || []);
const coinRoyale = ref(props.initialData.coin_royale || { rounds: [], finalists: [] });

const selectedRoundNumber = ref(coinRoyale.value.active_round_number || 1);
const gradeSelections = reactive({});
const questionDraft = reactive({
  question_text: '',
  correct_answer: '',
  image: null,
  preview_url: '',
  remove_image: false,
});

const roundMeta = [
  { num: 1, title: 'QR-Hunt Trivia', desc: 'Scan QR 1 Soal = 1 Regu Tercepat (Isian Singkat).' },
  { num: 2, title: 'Cipher Word-Search', desc: 'Cari kata bersandi (Kotak 1, Kotak 2, Angka).' },
  { num: 3, title: 'Final "Coin Royale"', desc: '9 Ronde taruhan taktis untuk Top 5 Regu.' },
];

const workspaceTabs = [
  { id: 'monitor', label: 'Monitor Babak 1 & 2 + Klasemen' },
  { id: 'coin-royale', label: 'Wasit Coin Royale (9 Ronde)' },
];

const selectedRound = computed(() =>
  (coinRoyale.value.rounds || []).find(r => r.round_number === selectedRoundNumber.value) || null
);

watch(
  selectedRound,
  (r, prev) => {
    if (r) {
      questionDraft.question_text = r.question_text || '';
      questionDraft.correct_answer = r.correct_answer || '';
      if (!prev || prev.round_number !== r.round_number) {
        questionDraft.image = null;
        questionDraft.preview_url = '';
        questionDraft.remove_image = false;
      }
    }
  },
  { immediate: true }
);

function roundCardClass(status) {
  if (status === 'ACTIVE') return 'border-emerald-500 bg-emerald-50/40';
  if (status === 'FINISHED') return 'border-blue-400 bg-blue-50/30';
  return 'border-gray-200 bg-gray-50/60';
}

function statusBadgeClass(status) {
  if (status === 'ACTIVE') return 'bg-emerald-600 text-white';
  if (status === 'FINISHED') return 'bg-blue-700 text-white';
  return 'bg-gray-200 text-gray-700';
}

function getBetForSession(sessionId) {
  if (!selectedRound.value) return null;
  return (selectedRound.value.bets || []).find(b => b.session_id === sessionId) || null;
}

function formatDelta(val) {
  const n = Number(val || 0);
  return n > 0 ? `+${n}` : `${n}`;
}

function showFlash(message, type = 'success') {
  flash.type = type;
  flash.message = message;
}

async function refreshState() {
  try {
    const { data } = await axios.get(routes.state);
    if (data.success) {
      Object.assign(roundStatuses, data.data.round_statuses);
      qrQuestions.value = data.data.qr_questions;
      cipherGrids.value = data.data.cipher_grids;
      leaderboard.value = data.data.leaderboard;
      coinRoyale.value = data.data.coin_royale;
    }
  } catch {
    // ignore
  }
}

async function changeRoundStatus(round, status) {
  loading.value = true;
  try {
    const { data } = await axios.post(`${routes.roundStatusBase}/${round}`, { status });
    Object.assign(roundStatuses, data.round_statuses);
    if (data.coin_royale) coinRoyale.value = data.coin_royale;
    showFlash(data.message);
  } catch (err) {
    showFlash(err.response?.data?.message || 'Gagal mengubah status babak.', 'error');
  } finally {
    loading.value = false;
  }
}

async function qualifyTop5(forceReset = false) {
  loading.value = true;
  try {
    const { data } = await axios.post(routes.selectFinalists, { force_reset: forceReset });
    if (data.coin_royale) coinRoyale.value = data.coin_royale;
    await refreshState();
    showFlash(data.message);
  } catch (err) {
    showFlash(err.response?.data?.message || 'Gagal menyaring Top 5.', 'error');
  } finally {
    loading.value = false;
  }
}

function onJuriImageSelect(event) {
  const file = event.target?.files?.[0];
  if (!file) return;
  questionDraft.image = file;
  questionDraft.remove_image = false;
  questionDraft.preview_url = URL.createObjectURL(file);
  event.target.value = '';
}

function clearJuriRoundImage() {
  questionDraft.image = null;
  questionDraft.preview_url = '';
  questionDraft.remove_image = true;
}

async function saveRoundQuestion() {
  loading.value = true;
  try {
    const fd = new FormData();
    fd.append('question_text', questionDraft.question_text || '');
    fd.append('correct_answer', questionDraft.correct_answer || '');
    if (questionDraft.image) {
      fd.append('image', questionDraft.image);
    }
    if (questionDraft.remove_image) {
      fd.append('remove_image', '1');
    }
    const { data } = await axios.post(`${routes.coinRoyaleBase}/${selectedRoundNumber.value}/question`, fd);
    coinRoyale.value = data.coin_royale;
    questionDraft.image = null;
    questionDraft.preview_url = '';
    questionDraft.remove_image = false;
    showFlash(data.message);
  } catch (err) {
    showFlash(err.response?.data?.message || 'Gagal menyimpan soal ronde.', 'error');
  } finally {
    loading.value = false;
  }
}

async function openBetting(roundNumber) {
  loading.value = true;
  try {
    const { data } = await axios.post(`${routes.coinRoyaleBase}/${roundNumber}/open-betting`);
    coinRoyale.value = data.coin_royale;
    showFlash(data.message);
  } catch (err) {
    showFlash(err.response?.data?.message || 'Gagal membuka taruhan.', 'error');
  } finally {
    loading.value = false;
  }
}

async function lockBetting(roundNumber) {
  loading.value = true;
  try {
    const { data } = await axios.post(`${routes.coinRoyaleBase}/${roundNumber}/lock-betting`);
    coinRoyale.value = data.coin_royale;
    showFlash(data.message);
  } catch (err) {
    showFlash(err.response?.data?.message || 'Gagal mengunci taruhan.', 'error');
  } finally {
    loading.value = false;
  }
}

async function openQuestion(roundNumber) {
  loading.value = true;
  try {
    const { data } = await axios.post(`${routes.coinRoyaleBase}/${roundNumber}/open-question`);
    coinRoyale.value = data.coin_royale;
    showFlash(data.message);
  } catch (err) {
    showFlash(err.response?.data?.message || 'Gagal menampilkan soal.', 'error');
  } finally {
    loading.value = false;
  }
}

async function submitGradeRound(roundNumber) {
  const payloadResults = {};
  for (const f of coinRoyale.value.finalists) {
    const bet = getBetForSession(f.session_id);
    if (selectedRound.value?.is_special && bet?.action === 'PASS') {
      continue;
    }
    if (!gradeSelections[f.session_id]) {
      showFlash(`Pilih Benar atau Salah untuk ${f.nama_regu} terlebih dahulu.`, 'error');
      return;
    }
    payloadResults[f.session_id] = gradeSelections[f.session_id];
  }

  loading.value = true;
  try {
    const { data } = await axios.post(`${routes.coinRoyaleBase}/${roundNumber}/grade`, {
      results: payloadResults,
    });
    coinRoyale.value = data.coin_royale;
    showFlash(data.message);
  } catch (err) {
    showFlash(err.response?.data?.message || 'Gagal mengesahkan hasil ronde.', 'error');
  } finally {
    loading.value = false;
  }
}

async function resetCoinRoyale() {
  if (!confirm('Reset seluruh 9 Ronde Coin Royale dan kembalikan saldo koin awal Top 5?')) return;
  loading.value = true;
  try {
    const { data } = await axios.post(routes.coinRoyaleResetAll);
    coinRoyale.value = data.coin_royale;
    selectedRoundNumber.value = 1;
    showFlash(data.message);
  } catch (err) {
    showFlash(err.response?.data?.message || 'Gagal mereset Coin Royale.', 'error');
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
  if (echo) {
    if (echo.connector?.pusher?.connection) {
      echo.connector.pusher.connection.bind('connected', () => { wsConnected.value = true; });
      echo.connector.pusher.connection.bind('disconnected', () => { wsConnected.value = false; });
      if (echo.connector.pusher.connection.state === 'connected') wsConnected.value = true;
    }

    echo.private('juri-monitor')
      .listen('.QrQuestionClaimed', () => refreshState())
      .listen('.CipherWordClaimed', () => refreshState())
      .listen('.CoinRoyaleUpdated', (e) => {
        if (e.state) {
          // Keep juri answers when refreshing
          refreshState();
        }
      })
      .listen('.RoundStatusChanged', (e) => {
        if (e.allStatuses) Object.assign(roundStatuses, e.allStatuses);
      });
  }
});
</script>
