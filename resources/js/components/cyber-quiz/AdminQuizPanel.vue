<template>
  <div class="space-y-6">
    <!-- Top Header & Navigation Tabs -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-4 sm:p-5">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-4">
        <div>
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold uppercase tracking-wider mb-1.5">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            Scout Cyber Quiz Arena • Admin Builder
          </div>
          <h2 class="text-xl sm:text-2xl font-black text-[#1B4332] uppercase tracking-tight">
            Manajemen 3 Babak &amp; Bank Sandi
          </h2>
        </div>

        <div class="flex flex-wrap items-center gap-2">
          <a
            :href="routes.sessions"
            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-[#1B4332] hover:bg-emerald-900 shadow-xs transition"
          >
            Panel Sesi Regu
          </a>
          <button
            type="button"
            @click="activeTab = 'translator'"
            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-emerald-950 bg-amber-400 hover:bg-amber-500 shadow-xs transition cursor-pointer"
          >
            Penerjemah Sandi
          </button>
        </div>
      </div>

      <!-- Tabs -->
      <div class="flex flex-wrap gap-2 border-t border-gray-100 pt-3">
        <button
          v-for="tab in tabs"
          :key="tab.id"
          type="button"
          @click="activeTab = tab.id"
          class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition cursor-pointer flex items-center gap-2"
          :class="activeTab === tab.id ? 'bg-[#1B4332] text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
        >
          <span>{{ tab.label }}</span>
          <span
            v-if="tab.badge !== undefined"
            class="px-1.5 py-0.5 rounded-full text-[10px]"
            :class="activeTab === tab.id ? 'bg-amber-400 text-emerald-950' : 'bg-white text-gray-700'"
          >
            {{ tab.badge }}
          </span>
        </button>
      </div>
    </div>

    <!-- Alert Banner -->
    <div
      v-if="flash.message"
      class="px-4 py-3 rounded-xl text-sm font-bold flex items-center justify-between border"
      :class="flash.type === 'error' ? 'bg-red-50 text-red-800 border-red-200' : 'bg-emerald-50 text-emerald-800 border-emerald-200'"
    >
      <span>{{ flash.message }}</span>
      <button type="button" @click="flash.message = ''" class="text-xs underline cursor-pointer">Tutup</button>
    </div>

    <!-- TAB 1: BABAK 1 (QR-HUNT TRIVIA) -->
    <div v-if="activeTab === 'qr-hunt'" class="space-y-6">
      <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
          <div>
            <h3 class="text-base font-black text-[#1B4332] uppercase">Babak 1: QR-Hunt Trivia (Isian Singkat Berebut)</h3>
            <p class="text-xs text-gray-500">1 Soal QR = 1 Regu Tercepat. Cetak lembar QR untuk ditempel di area bumi perkemahan.</p>
          </div>
          <div class="flex flex-wrap gap-2">
            <a
              :href="routes.qrTemplateCsv"
              class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-200 transition"
            >
              Template CSV
            </a>
            <label
              class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-emerald-900 bg-emerald-100 hover:bg-emerald-200 border border-emerald-200 transition cursor-pointer"
            >
              <span>{{ loading ? 'Mengimpor...' : 'Import CSV' }}</span>
              <input
                type="file"
                accept=".csv,text/csv"
                :disabled="loading"
                class="hidden"
                @change="uploadQrCsv"
              />
            </label>
            <a
              :href="routes.qrPrintPdf"
              target="_blank"
              class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-black text-emerald-950 bg-amber-400 hover:bg-amber-500 shadow-xs transition"
            >
              Cetak Lembar QR Massal (PDF)
            </a>
            <button
              type="button"
              @click="openQrForm()"
              class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#1B4332] hover:bg-emerald-900 shadow-xs transition cursor-pointer"
            >
              + Tambah Soal QR
            </button>
          </div>
        </div>

        <!-- QR Form Drawer/Panel -->
        <div v-if="qrForm.open" class="mb-6 p-5 rounded-2xl bg-[#F4EDE4]/60 border-2 border-[#1B4332]/20 space-y-4">
          <div class="flex items-center justify-between">
            <h4 class="text-sm font-black text-[#1B4332] uppercase">
              {{ qrForm.id ? 'Edit Soal QR-Hunt' : 'Tambah Soal QR-Hunt Baru' }}
            </h4>
            <button type="button" @click="qrForm.open = false" class="text-xs font-bold text-gray-500 hover:text-gray-800 cursor-pointer">
              Batal
            </button>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label class="block text-xs font-bold text-gray-700 mb-1">Label Pos (Opsional)</label>
              <input
                v-model="qrForm.code_label"
                type="text"
                placeholder="Otomatis misal QR-001"
                class="w-full px-3 py-2 rounded-xl border border-gray-300 bg-white text-sm"
              />
            </div>
            <div>
              <label class="block text-xs font-bold text-gray-700 mb-1">Poin Soal</label>
              <input
                v-model.number="qrForm.points"
                type="number"
                min="1"
                class="w-full px-3 py-2 rounded-xl border border-gray-300 bg-white text-sm font-bold"
              />
            </div>
            <div>
              <label class="block text-xs font-bold text-gray-700 mb-1">Gambar Ilustrasi (Opsional)</label>
              <input
                type="file"
                accept="image/*"
                @change="e => qrForm.imageFile = e.target.files[0] || null"
                class="w-full text-xs text-gray-600 bg-white border border-gray-300 rounded-xl p-1.5"
              />
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Pertanyaan</label>
            <textarea
              v-model="qrForm.question"
              rows="3"
              placeholder="Tuliskan pertanyaan trivia kepramukaan atau pengetahuan umum..."
              class="w-full px-3 py-2 rounded-xl border border-gray-300 bg-white text-sm"
            ></textarea>
          </div>

          <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">
              Variasi Kunci Jawaban Benar (Pisahkan dengan koma atau baris baru — Case-Insensitive)
            </label>
            <textarea
              v-model="qrForm.accepted_answers_text"
              rows="2"
              placeholder="Contoh: Baden Powell, Lord Baden Powell, Robert Baden Powell"
              class="w-full px-3 py-2 rounded-xl border border-gray-300 bg-white text-sm font-mono"
            ></textarea>
          </div>

          <div class="flex justify-end gap-2">
            <button
              type="button"
              :disabled="loading"
              @click="submitQrForm"
              class="px-5 py-2.5 rounded-xl text-xs font-black text-white bg-[#1B4332] hover:bg-emerald-900 disabled:opacity-50 cursor-pointer transition"
            >
              {{ loading ? 'Menyimpan...' : 'Simpan Soal QR' }}
            </button>
          </div>
        </div>

        <!-- Live Monitor Summary -->
        <div class="grid grid-cols-3 gap-3 mb-5">
          <div class="p-3 rounded-xl bg-gray-50 border border-gray-200 text-center">
            <div class="text-[10px] font-bold uppercase text-gray-500">Total Soal QR</div>
            <div class="text-2xl font-black text-gray-900">{{ qrQuestions.length }}</div>
          </div>
          <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-center">
            <div class="text-[10px] font-bold uppercase text-emerald-700">Tersedia</div>
            <div class="text-2xl font-black text-emerald-700">{{ qrAvailableCount }}</div>
          </div>
          <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-center">
            <div class="text-[10px] font-bold uppercase text-amber-800">Terklaim</div>
            <div class="text-2xl font-black text-amber-700">{{ qrQuestions.length - qrAvailableCount }}</div>
          </div>
        </div>

        <!-- QR List Table / Cards -->
        <div v-if="qrQuestions.length === 0" class="py-10 text-center text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-2xl">
          Belum ada soal QR-Hunt Trivia. Klik "+ Tambah Soal QR" untuk membuat soal pertama.
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div
            v-for="q in qrQuestions"
            :key="q.id"
            class="rounded-2xl border p-4 transition flex gap-4 items-start"
            :class="q.status === 'claimed' ? 'bg-amber-50/40 border-amber-300' : 'bg-white border-gray-200'"
          >
            <div class="shrink-0 text-center">
              <img :src="q.qr_svg_uri" :alt="q.code_label" class="w-20 h-20 rounded-xl border border-gray-200 bg-white p-1" />
              <div class="text-[10px] font-mono font-bold text-gray-500 mt-1">{{ q.code_label }}</div>
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center justify-between gap-2 mb-1">
                <span
                  class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase"
                  :class="q.status === 'claimed' ? 'bg-amber-200 text-amber-900' : 'bg-emerald-100 text-emerald-800'"
                >
                  {{ q.status === 'claimed' ? `Terklaim: ${q.claimed_by}` : 'Tersedia' }}
                </span>
                <span class="text-xs font-black text-[#1B4332] bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-200">
                  {{ q.points }} Poin
                </span>
              </div>
              <p class="text-sm font-bold text-gray-900 line-clamp-2 mb-1.5">{{ q.question }}</p>
              <div class="flex flex-wrap gap-1 mb-2">
                <span
                  v-for="(ans, i) in q.accepted_answers"
                  :key="i"
                  class="text-[10px] font-mono bg-gray-100 text-gray-700 px-1.5 py-0.5 rounded border border-gray-200"
                >
                  {{ ans }}
                </span>
              </div>
              <div v-if="q.status === 'claimed'" class="text-[11px] text-amber-800 font-semibold mb-2">
                Dijawab: "{{ q.claimed_answer }}"
              </div>
              <div class="flex flex-wrap gap-1.5 pt-2 border-t border-gray-100">
                <button
                  type="button"
                  @click="openQrForm(q)"
                  class="px-2.5 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-[11px] font-bold cursor-pointer"
                >
                  Edit
                </button>
                <button
                  v-if="q.status === 'claimed'"
                  type="button"
                  @click="resetQr(q.id)"
                  class="px-2.5 py-1 rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-900 text-[11px] font-bold cursor-pointer"
                >
                  Reset Klaim
                </button>
                <button
                  type="button"
                  @click="deleteQr(q.id)"
                  class="px-2.5 py-1 rounded-lg bg-red-50 hover:bg-red-100 text-red-700 text-[11px] font-bold cursor-pointer"
                >
                  Hapus
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- TAB 2: BABAK 2 (CIPHER WORD-SEARCH) -->
    <div v-if="activeTab === 'cipher-grid'" class="space-y-6">
      <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
          <div>
            <h3 class="text-base font-black text-[#1B4332] uppercase">Babak 2: Cipher Word-Search (Kotak Sandi Dinamis)</h3>
            <p class="text-xs text-gray-500">Bangun grid 12×12 s/d 20×20 bersandi &amp; cetak lembar soal A4 untuk peserta.</p>
          </div>
          <div class="flex flex-wrap gap-2">
            <a
              :href="routes.cipherTemplateCsv"
              class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-200 transition"
            >
              Template CSV Kata
            </a>
            <a
              :href="routes.cipherPrintPdf"
              target="_blank"
              class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-black text-emerald-950 bg-amber-400 hover:bg-amber-500 shadow-xs transition"
            >
              Cetak Semua A4 (PDF)
            </a>
            <a
              :href="`${routes.cipherPrintPdf}?with_key=1`"
              target="_blank"
              class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-200 transition"
            >
              PDF + Kunci Panitia
            </a>
            <button
              type="button"
              @click="openGridBuilder()"
              class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#1B4332] hover:bg-emerald-900 shadow-xs transition cursor-pointer"
            >
              + Buat Kategori Sandi
            </button>
          </div>
        </div>

        <!-- Dynamic Grid Builder Form -->
        <div v-if="gridForm.open" class="mb-6 p-5 rounded-2xl bg-[#F4EDE4]/60 border-2 border-[#1B4332]/20 space-y-4">
          <div class="flex items-center justify-between">
            <h4 class="text-sm font-black text-[#1B4332] uppercase">
              {{ gridForm.id ? 'Edit Kategori Cipher Word-Search' : 'Dynamic Grid Builder' }}
            </h4>
            <button type="button" @click="gridForm.open = false" class="text-xs font-bold text-gray-500 hover:text-gray-800 cursor-pointer">
              Batal
            </button>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="sm:col-span-2">
              <label class="block text-xs font-bold text-gray-700 mb-1">Judul Kategori <span class="text-gray-400 font-normal">(opsional, otomatis jika kosong)</span></label>
              <input
                v-model="gridForm.title"
                type="text"
                placeholder="Misal: Sandi Kotak 1 (kosongkan untuk nama otomatis)"
                class="w-full px-3 py-2 rounded-xl border border-gray-300 bg-white text-sm"
              />
            </div>
            <div>
              <label class="block text-xs font-bold text-gray-700 mb-1">Jenis Sandi</label>
              <select v-model="gridForm.cipher_type" class="w-full px-3 py-2 rounded-xl border border-gray-300 bg-white text-sm font-bold">
                <option value="kotak_1">Sandi Kotak 1</option>
                <option value="kotak_2">Sandi Kotak 2</option>
                <option value="angka">Sandi Angka</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-bold text-gray-700 mb-1">Ukuran Grid ({{ gridForm.grid_size }}×{{ gridForm.grid_size }})</label>
              <input
                v-model.number="gridForm.grid_size"
                type="range"
                min="12"
                max="20"
                class="w-full accent-[#1B4332]"
              />
              <div class="flex justify-between text-[10px] text-gray-500 font-bold">
                <span>12×12</span>
                <span>16×16</span>
                <span>20×20</span>
              </div>
            </div>
          </div>

          <!-- Words List -->
          <div class="space-y-2">
            <div class="flex flex-wrap items-center justify-between gap-2">
              <label class="text-xs font-bold text-gray-700 uppercase">Daftar Kata Target</label>
              <div class="flex flex-wrap items-center gap-2">
                <a
                  :href="routes.cipherTemplateCsv"
                  class="px-3 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold border border-gray-200"
                >
                  Template CSV
                </a>
                <label
                  class="px-3 py-1 rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-900 text-xs font-bold border border-amber-200 cursor-pointer"
                >
                  <span>Import CSV Kata</span>
                  <input
                    type="file"
                    accept=".csv,text/csv"
                    :disabled="loading"
                    class="hidden"
                    @change="(e) => uploadCipherWordsCsv(e, false)"
                  />
                </label>
                <button
                  type="button"
                  @click="addWordRow"
                  class="px-3 py-1 rounded-lg bg-emerald-100 hover:bg-emerald-200 text-[#1B4332] text-xs font-bold cursor-pointer"
                >
                  + Tambah Kata
                </button>
              </div>
            </div>
            <div
              v-for="(w, idx) in gridForm.words"
              :key="idx"
              class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center bg-white p-2.5 rounded-xl border border-gray-200"
            >
              <input
                v-model="w.word"
                type="text"
                placeholder="KATA (A-Z)"
                class="sm:col-span-7 px-3 py-1.5 rounded-lg border border-gray-300 text-xs font-mono font-bold uppercase"
              />
              <input
                v-model.number="w.points"
                type="number"
                min="1"
                placeholder="Poin"
                class="sm:col-span-3 px-3 py-1.5 rounded-lg border border-gray-300 text-xs font-bold"
              />
              <button
                type="button"
                @click="gridForm.words.splice(idx, 1)"
                class="sm:col-span-2 text-red-600 hover:text-red-800 text-xs font-bold cursor-pointer"
              >
                Hapus
              </button>
            </div>
          </div>

          <div class="flex flex-wrap justify-between items-center gap-3 pt-2">
            <button
              type="button"
              :disabled="loading"
              @click="generatePreview"
              class="px-4 py-2 rounded-xl text-xs font-black text-emerald-950 bg-amber-400 hover:bg-amber-500 cursor-pointer transition"
            >
              Generate &amp; Preview Matriks Acak
            </button>

            <button
              type="button"
              :disabled="loading"
              @click="saveCipherGrid"
              class="px-5 py-2.5 rounded-xl text-xs font-black text-white bg-[#1B4332] hover:bg-emerald-900 disabled:opacity-50 cursor-pointer transition"
            >
              {{ loading ? 'Menyimpan...' : 'Simpan Kategori & Matriks' }}
            </button>
          </div>

          <!-- Live Matrix Preview -->
          <div v-if="gridForm.matrix.length > 0" class="bg-white p-4 rounded-xl border border-gray-200 overflow-x-auto">
            <div class="flex items-center justify-between mb-3">
              <span class="text-xs font-black uppercase text-[#1B4332]">Preview Matriks Sandi ({{ gridForm.grid_size }}×{{ gridForm.grid_size }})</span>
              <label class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-600 cursor-pointer">
                <input v-model="previewShowLetters" type="checkbox" class="rounded" />
                Tampilkan Huruf Pembantu
              </label>
            </div>
            <div
              class="inline-grid gap-1 p-2 bg-gray-50 rounded-xl border border-gray-200"
              :style="{ gridTemplateColumns: `repeat(${gridForm.grid_size}, minmax(28px, 1fr))` }"
            >
              <template v-for="(row, rIdx) in gridForm.matrix" :key="rIdx">
                <div
                  v-for="(cell, cIdx) in row"
                  :key="`${rIdx}-${cIdx}`"
                  class="bg-white border rounded flex items-center justify-center p-0.5"
                  :class="isKeyCell(rIdx, cIdx) ? 'border-amber-500 bg-amber-50/70' : 'border-gray-200'"
                >
                  <CipherCharacter
                    :type="gridForm.cipher_type"
                    :char="cell"
                    size="sm"
                    :show-label="previewShowLetters"
                  />
                </div>
              </template>
            </div>
          </div>
        </div>

        <!-- Existing Cipher Grids List -->
        <div v-if="cipherGrids.length === 0" class="py-10 text-center text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-2xl">
          Belum ada kategori Cipher Word-Search. Klik "+ Buat Kategori Sandi" untuk membuat.
        </div>

        <div v-else class="space-y-4">
          <div
            v-for="grid in cipherGrids"
            :key="grid.id"
            class="rounded-2xl border border-gray-200 p-4 bg-white"
          >
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-3">
              <div>
                <div class="flex items-center gap-2">
                  <h4 class="text-base font-black text-[#1B4332]">{{ grid.title }}</h4>
                  <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-blue-50 text-blue-800 border border-blue-200">
                    {{ cipherTypeLabel(grid.cipher_type) }}
                  </span>
                  <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700">
                    {{ grid.grid_size }}×{{ grid.grid_size }}
                  </span>
                </div>
              </div>
              <div class="flex flex-wrap gap-1.5">
                <a
                  :href="`${routes.cipherPrintPdf}/${grid.id}`"
                  target="_blank"
                  class="px-3 py-1.5 rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-900 text-xs font-bold"
                >
                  Cetak A4
                </a>
                <label
                  class="px-3 py-1.5 rounded-lg bg-emerald-100 hover:bg-emerald-200 text-emerald-900 text-xs font-bold cursor-pointer"
                >
                  <span>Import CSV Kata</span>
                  <input
                    type="file"
                    accept=".csv,text/csv"
                    :disabled="loading"
                    class="hidden"
                    @change="(e) => uploadCipherWordsCsv(e, grid.id)"
                  />
                </label>
                <button
                  type="button"
                  @click="openGridBuilder(grid)"
                  class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold cursor-pointer"
                >
                  Edit / Acak Ulang
                </button>
                <button
                  type="button"
                  @click="resetGridWords(grid.id)"
                  class="px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-bold cursor-pointer"
                >
                  Reset Klaim
                </button>
                <button
                  type="button"
                  @click="deleteGrid(grid.id)"
                  class="px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-700 text-xs font-bold cursor-pointer"
                >
                  Hapus
                </button>
              </div>
            </div>

            <div class="flex flex-wrap gap-2">
              <div
                v-for="w in grid.words"
                :key="w.id"
                class="px-3 py-1.5 rounded-xl border text-xs flex items-center gap-2"
                :class="w.is_claimed ? 'bg-amber-50 border-amber-300 text-amber-900' : 'bg-gray-50 border-gray-200 text-gray-800'"
              >
                <span class="font-mono font-black" :class="w.is_claimed ? 'line-through opacity-75' : ''">{{ w.word }}</span>
                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-white border border-gray-200">{{ w.points }} pts</span>
                <span v-if="w.is_claimed" class="text-[10px] font-bold text-amber-800">({{ w.claimed_by }})</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- TAB 3: BABAK 3 (COIN ROYALE 9 SOAL) -->
    <div v-if="activeTab === 'coin-royale'" class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-5 space-y-4">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
          <h3 class="text-base font-black text-[#1B4332] uppercase">Babak 3: Bank Soal 9 Ronde Coin Royale</h3>
          <p class="text-xs text-gray-500">Ronde 1, 2, 4, 5, 7, 8 = Normal (Wajib, 2x / -1x). Ronde 3, 6, 9 = Spesial (Opsional, 3x / -2x).</p>
        </div>
        <div class="flex flex-wrap gap-2">
          <a
            :href="routes.coinRoyaleTemplateCsv"
            class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-200 transition"
          >
            Template CSV
          </a>
          <label
            class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl text-xs font-bold text-emerald-900 bg-emerald-100 hover:bg-emerald-200 border border-emerald-200 transition cursor-pointer"
          >
            <span>{{ loading ? 'Mengimpor...' : 'Import CSV' }}</span>
            <input
              type="file"
              accept=".csv,text/csv"
              :disabled="loading"
              class="hidden"
              @change="uploadCoinRoyaleCsv"
            />
          </label>
          <button
            type="button"
            :disabled="loading"
            @click="saveCoinRoyaleRounds"
            class="px-5 py-2.5 rounded-xl text-xs font-black text-white bg-[#1B4332] hover:bg-emerald-900 cursor-pointer"
          >
            Simpan Semua 9 Ronde
          </button>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div
          v-for="r in coinRounds"
          :key="r.round_number"
          class="p-4 rounded-2xl border space-y-2.5"
          :class="r.is_special ? 'bg-amber-50/60 border-amber-300' : 'bg-gray-50/70 border-gray-200'"
        >
          <div class="flex items-center justify-between">
            <span class="text-sm font-black text-[#1B4332]">Ronde {{ r.round_number }}</span>
            <span
              class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase"
              :class="r.is_special ? 'bg-amber-400 text-emerald-950' : 'bg-emerald-100 text-emerald-800'"
            >
              {{ r.is_special ? 'SPESIAL (OPSIONAL)' : 'NORMAL (WAJIB)' }}
            </span>
          </div>
          <textarea
            v-model="r.question_text"
            rows="3"
            :placeholder="`Soal untuk Ronde ${r.round_number}...`"
            class="w-full px-3 py-2 rounded-xl border border-gray-300 bg-white text-xs"
          ></textarea>
          <input
            v-model="r.correct_answer"
            type="text"
            placeholder="Kunci jawaban referensi juri..."
            class="w-full px-3 py-1.5 rounded-xl border border-gray-300 bg-white text-xs font-mono"
          />
          <div class="pt-1 border-t border-gray-200/80 space-y-2">
            <div v-if="r.question_image_url" class="relative rounded-xl overflow-hidden border border-gray-200 bg-white">
              <img
                :src="r.question_image_url"
                :alt="`Gambar Soal Ronde ${r.round_number}`"
                class="w-full h-28 object-contain bg-gray-50"
              />
            </div>
            <div class="flex flex-wrap items-center justify-between gap-2">
              <label class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-white hover:bg-gray-100 border border-gray-300 text-[11px] font-bold text-gray-700 cursor-pointer transition">
                <span>{{ r.question_image_url ? 'Ganti Gambar' : '+ Upload Gambar Soal' }}</span>
                <input
                  type="file"
                  accept="image/*"
                  :disabled="loading"
                  class="hidden"
                  @change="(e) => uploadCoinRoyaleRoundImage(r.round_number, e)"
                />
              </label>
              <button
                v-if="r.question_image_url"
                type="button"
                :disabled="loading"
                @click="deleteCoinRoyaleRoundImage(r.round_number)"
                class="px-2.5 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 text-[11px] font-bold cursor-pointer transition"
              >
                Hapus Gambar
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- TAB 4: CIPHER TRANSLATOR -->
    <div v-if="activeTab === 'translator'">
      <CipherTranslator />
    </div>

    <!-- TAB 5: PENGATURAN -->
    <div v-if="activeTab === 'settings'" class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-5 max-w-2xl space-y-4">
      <h3 class="text-base font-black text-[#1B4332] uppercase">Pengaturan Arena &amp; Prasyarat Regu</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-bold text-gray-700 mb-1">Minimal Anggota Regu (Syarat Buka Menu)</label>
          <input
            v-model.number="settingsForm.min_anggota_regu"
            type="number"
            min="1"
            max="10"
            class="w-full px-3 py-2 rounded-xl border border-gray-300 text-sm font-bold"
          />
        </div>
        <div>
          <label class="block text-xs font-bold text-gray-700 mb-1">Durasi Babak 1 (Menit)</label>
          <input
            v-model.number="settingsForm.round_1_duration"
            type="number"
            min="1"
            class="w-full px-3 py-2 rounded-xl border border-gray-300 text-sm font-bold"
          />
        </div>
        <div>
          <label class="block text-xs font-bold text-gray-700 mb-1">Durasi Babak 2 (Menit)</label>
          <input
            v-model.number="settingsForm.round_2_duration"
            type="number"
            min="1"
            class="w-full px-3 py-2 rounded-xl border border-gray-300 text-sm font-bold"
          />
        </div>
        <div>
          <label class="block text-xs font-bold text-gray-700 mb-1">Durasi Babak 3 (Menit)</label>
          <input
            v-model.number="settingsForm.round_3_duration"
            type="number"
            min="1"
            class="w-full px-3 py-2 rounded-xl border border-gray-300 text-sm font-bold"
          />
        </div>
      </div>
      <div class="flex justify-end">
        <button
          type="button"
          :disabled="loading"
          @click="saveSettings"
          class="px-5 py-2.5 rounded-xl text-xs font-black text-white bg-[#1B4332] hover:bg-emerald-900 cursor-pointer"
        >
          Simpan Pengaturan
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import axios from 'axios';
import echo from '../../echo';
import CipherCharacter from '../CipherCharacter.vue';
import CipherTranslator from '../CipherTranslator.vue';
import { getCipherTypeLabel } from '../../utils/sandiHelper';

const props = defineProps({
  initialData: { type: Object, required: true },
});

const routes = props.initialData.routes;
const activeTab = ref(props.initialData.activeTab || 'qr-hunt');
const loading = ref(false);
const previewShowLetters = ref(true);
const flash = reactive({ type: 'success', message: '' });

const qrQuestions = ref(props.initialData.qrQuestions || []);
const cipherGrids = ref(props.initialData.cipherGrids || []);
const coinRounds = ref(props.initialData.coinRoyaleRounds || []);
const settingsForm = reactive({ ...props.initialData.settings });

const tabs = computed(() => [
  { id: 'qr-hunt', label: 'Babak 1: QR-Hunt', badge: qrQuestions.value.length },
  { id: 'cipher-grid', label: 'Babak 2: Cipher Grid', badge: cipherGrids.value.length },
  { id: 'coin-royale', label: 'Babak 3: Coin Royale', badge: '9 Ronde' },
  { id: 'translator', label: 'Penerjemah Sandi' },
  { id: 'settings', label: 'Pengaturan' },
]);

const qrAvailableCount = computed(() => qrQuestions.value.filter(q => q.status === 'available').length);

const qrForm = reactive({
  open: false,
  id: null,
  code_label: '',
  question: '',
  accepted_answers_text: '',
  points: 100,
  imageFile: null,
});

const gridForm = reactive({
  open: false,
  id: null,
  title: '',
  cipher_type: 'kotak_1',
  grid_size: 14,
  default_points: 50,
  words: [{ word: 'PRAMUKA', points: 50 }],
  matrix: [],
  placements: [],
});

function showFlash(message, type = 'success') {
  flash.type = type;
  flash.message = message;
}

function cipherTypeLabel(t) {
  return getCipherTypeLabel(t);
}

function openQrForm(existing = null) {
  qrForm.open = true;
  if (existing) {
    qrForm.id = existing.id;
    qrForm.code_label = existing.code_label || '';
    qrForm.question = existing.question || '';
    qrForm.accepted_answers_text = (existing.accepted_answers || []).join(', ');
    qrForm.points = existing.points || 100;
    qrForm.imageFile = null;
  } else {
    qrForm.id = null;
    qrForm.code_label = '';
    qrForm.question = '';
    qrForm.accepted_answers_text = '';
    qrForm.points = 100;
    qrForm.imageFile = null;
  }
}

async function submitQrForm() {
  loading.value = true;
  try {
    const fd = new FormData();
    fd.append('code_label', qrForm.code_label);
    fd.append('question', qrForm.question);
    fd.append('accepted_answers', qrForm.accepted_answers_text);
    fd.append('points', String(qrForm.points));
    if (qrForm.imageFile) {
      fd.append('image', qrForm.imageFile);
    }

    const url = qrForm.id
      ? `${routes.qrBase}/${qrForm.id}/update`
      : routes.qrStore;

    const { data } = await axios.post(url, fd);
    if (qrForm.id) {
      const idx = qrQuestions.value.findIndex(item => item.id === qrForm.id);
      if (idx !== -1) qrQuestions.value[idx] = data.data;
    } else {
      qrQuestions.value.push(data.data);
    }
    qrForm.open = false;
    showFlash(data.message);
  } catch (err) {
    showFlash(err.response?.data?.message || 'Gagal menyimpan soal QR.', 'error');
  } finally {
    loading.value = false;
  }
}

async function resetQr(id) {
  try {
    const { data } = await axios.post(`${routes.qrBase}/${id}/reset`);
    const idx = qrQuestions.value.findIndex(item => item.id === id);
    if (idx !== -1) qrQuestions.value[idx] = data.data;
    showFlash(data.message);
  } catch (err) {
    showFlash(err.response?.data?.message || 'Gagal mereset soal QR.', 'error');
  }
}

async function deleteQr(id) {
  if (!confirm('Hapus soal QR-Hunt ini?')) return;
  try {
    const { data } = await axios.delete(`${routes.qrBase}/${id}`);
    qrQuestions.value = qrQuestions.value.filter(item => item.id !== id);
    showFlash(data.message);
  } catch (err) {
    showFlash(err.response?.data?.message || 'Gagal menghapus soal QR.', 'error');
  }
}

function openGridBuilder(existing = null) {
  gridForm.open = true;
  if (existing) {
    gridForm.id = existing.id;
    gridForm.title = existing.title;
    gridForm.cipher_type = existing.cipher_type;
    gridForm.grid_size = existing.grid_size;
    gridForm.default_points = existing.default_points || 50;
    gridForm.words = (existing.words || []).map(w => ({
      word: w.word,
      points: w.points || 50,
    }));
    gridForm.matrix = existing.grid_matrix || [];
    gridForm.placements = existing.word_placements || [];
  } else {
    gridForm.id = null;
    gridForm.title = '';
    gridForm.cipher_type = 'kotak_1';
    gridForm.grid_size = 14;
    gridForm.default_points = 50;
    gridForm.words = [
      { word: 'PRAMUKA', points: 50 },
      { word: 'SANDI', points: 50 },
      { word: 'KOMPAS', points: 50 },
    ];
    gridForm.matrix = [];
    gridForm.placements = [];
  }
}

function addWordRow() {
  gridForm.words.push({ word: '', points: gridForm.default_points || 50 });
}

function isKeyCell(r, c) {
  return gridForm.placements.some(p => (p.coords || []).some(pt => pt[0] === r && pt[1] === c));
}

async function generatePreview() {
  loading.value = true;
  try {
    const { data } = await axios.post(routes.cipherPreview, {
      grid_size: gridForm.grid_size,
      words: gridForm.words.filter(w => w.word.trim() !== ''),
    });
    gridForm.matrix = data.matrix;
    gridForm.placements = data.placements;
    showFlash('Matriks berhasil di-generate!');
  } catch (err) {
    showFlash(err.response?.data?.message || 'Gagal men-generate matriks.', 'error');
  } finally {
    loading.value = false;
  }
}

async function saveCipherGrid() {
  loading.value = true;
  try {
    const payload = {
      title: gridForm.title,
      cipher_type: gridForm.cipher_type,
      grid_size: gridForm.grid_size,
      default_points: gridForm.default_points,
      words: gridForm.words.filter(w => w.word.trim() !== ''),
      matrix: gridForm.matrix.length ? gridForm.matrix : null,
      placements: gridForm.placements.length ? gridForm.placements : null,
    };

    const { data } = gridForm.id
      ? await axios.put(`${routes.cipherBase}/${gridForm.id}`, payload)
      : await axios.post(routes.cipherStore, payload);

    if (gridForm.id) {
      const idx = cipherGrids.value.findIndex(g => g.id === gridForm.id);
      if (idx !== -1) cipherGrids.value[idx] = data.data;
    } else {
      cipherGrids.value.push(data.data);
    }
    gridForm.open = false;
    showFlash(data.message);
  } catch (err) {
    showFlash(err.response?.data?.message || 'Gagal menyimpan kategori sandi.', 'error');
  } finally {
    loading.value = false;
  }
}

async function resetGridWords(id) {
  try {
    const { data } = await axios.post(`${routes.cipherBase}/${id}/reset`);
    const idx = cipherGrids.value.findIndex(g => g.id === id);
    if (idx !== -1) cipherGrids.value[idx] = data.data;
    showFlash(data.message);
  } catch (err) {
    showFlash(err.response?.data?.message || 'Gagal mereset klaim kata.', 'error');
  }
}

async function deleteGrid(id) {
  if (!confirm('Hapus kategori Cipher Word-Search ini?')) return;
  try {
    const { data } = await axios.delete(`${routes.cipherBase}/${id}`);
    cipherGrids.value = cipherGrids.value.filter(g => g.id !== id);
    showFlash(data.message);
  } catch (err) {
    showFlash(err.response?.data?.message || 'Gagal menghapus kategori.', 'error');
  }
}

async function saveCoinRoyaleRounds() {
  loading.value = true;
  try {
    const { data } = await axios.post(routes.coinRoyaleRounds, {
      rounds: coinRounds.value,
    });
    coinRounds.value = data.rounds;
    showFlash(data.message);
  } catch (err) {
    showFlash(err.response?.data?.message || 'Gagal menyimpan soal Coin Royale.', 'error');
  } finally {
    loading.value = false;
  }
}

async function uploadQrCsv(event) {
  const file = event.target?.files?.[0];
  if (!file) return;
  loading.value = true;
  try {
    const fd = new FormData();
    fd.append('file', file);
    const { data } = await axios.post(routes.qrImportCsv, fd);
    qrQuestions.value = data.items || [];
    showFlash(data.message || 'Import CSV soal QR berhasil.');
  } catch (err) {
    showFlash(err.response?.data?.message || 'Gagal mengimpor CSV soal QR.', 'error');
  } finally {
    event.target.value = '';
    loading.value = false;
  }
}

async function uploadCipherWordsCsv(event, targetGridId = false) {
  const file = event.target?.files?.[0];
  if (!file) return;
  loading.value = true;
  try {
    const fd = new FormData();
    fd.append('file', file);
    fd.append('default_points', String(gridForm.default_points || 50));
    if (targetGridId) {
      fd.append('cipher_grid_id', String(targetGridId));
    }

    const { data } = await axios.post(routes.cipherImportWordsCsv, fd);

    if (targetGridId && data.data) {
      const idx = cipherGrids.value.findIndex(g => g.id === targetGridId);
      if (idx !== -1) cipherGrids.value[idx] = data.data;
      showFlash(data.message || 'Kata CSV berhasil diimpor ke kategori.');
    } else if (Array.isArray(data.words) && data.words.length > 0) {
      gridForm.words = data.words.map(w => ({
        word: w.word,
        points: w.points || gridForm.default_points || 50,
      }));
      showFlash(data.message || `${gridForm.words.length} kata dimuat dari CSV.`);
      await generatePreview();
    }
  } catch (err) {
    showFlash(err.response?.data?.message || 'Gagal mengimpor CSV kata.', 'error');
  } finally {
    event.target.value = '';
    loading.value = false;
  }
}

async function uploadCoinRoyaleCsv(event) {
  const file = event.target?.files?.[0];
  if (!file) return;
  loading.value = true;
  try {
    const fd = new FormData();
    fd.append('file', file);
    const { data } = await axios.post(routes.coinRoyaleImportCsv, fd);
    if (Array.isArray(data.rounds)) {
      coinRounds.value = data.rounds;
    }
    showFlash(data.message || 'Import CSV 9 ronde Coin Royale berhasil.');
  } catch (err) {
    showFlash(err.response?.data?.message || 'Gagal mengimpor CSV Coin Royale.', 'error');
  } finally {
    event.target.value = '';
    loading.value = false;
  }
}

async function uploadCoinRoyaleRoundImage(roundNumber, event) {
  const file = event.target?.files?.[0];
  if (!file) return;
  loading.value = true;
  try {
    const fd = new FormData();
    fd.append('image', file);
    const { data } = await axios.post(`${routes.coinRoyaleBase}/${roundNumber}/image`, fd);
    if (Array.isArray(data.rounds)) {
      coinRounds.value = data.rounds;
    }
    showFlash(data.message || `Gambar soal Ronde ${roundNumber} berhasil diunggah.`);
  } catch (err) {
    showFlash(err.response?.data?.message || 'Gagal mengunggah gambar soal.', 'error');
  } finally {
    event.target.value = '';
    loading.value = false;
  }
}

async function deleteCoinRoyaleRoundImage(roundNumber) {
  loading.value = true;
  try {
    const { data } = await axios.delete(`${routes.coinRoyaleBase}/${roundNumber}/image`);
    if (Array.isArray(data.rounds)) {
      coinRounds.value = data.rounds;
    }
    showFlash(data.message || `Gambar soal Ronde ${roundNumber} berhasil dihapus.`);
  } catch (err) {
    showFlash(err.response?.data?.message || 'Gagal menghapus gambar soal.', 'error');
  } finally {
    loading.value = false;
  }
}

async function saveSettings() {
  loading.value = true;
  try {
    const { data } = await axios.post(routes.settingsUpdate, settingsForm);
    showFlash(data.message);
  } catch (err) {
    showFlash(err.response?.data?.message || 'Gagal menyimpan pengaturan.', 'error');
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
  if (echo) {
    echo.private('juri-monitor')
      .listen('.QrQuestionClaimed', (e) => {
        const q = qrQuestions.value.find(item => item.id === e.questionId);
        if (q) {
          q.status = 'claimed';
          q.claimed_by = e.reguName;
          q.claimed_answer = e.claimedAnswer;
        }
      })
      .listen('.CipherWordClaimed', (e) => {
        const grid = cipherGrids.value.find(g => g.id === e.gridId);
        if (grid) {
          const word = (grid.words || []).find(w => w.id === e.wordId);
          if (word) {
            word.is_claimed = true;
            word.claimed_by = e.reguName;
          }
        }
      });
  }
});
</script>
