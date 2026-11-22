<!-- Settings Modal -->
<div id="settingsModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs transition-opacity" aria-hidden="true"
            onclick="document.getElementById('settingsModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-200">
            <div class="bg-gradient-to-r from-brand-forest to-emerald-800 px-6 py-4 text-white flex justify-between items-center">
                <h3 class="text-base font-bold flex items-center gap-2" id="modal-title">
                    <i data-lucide="clock" class="w-4 h-4 text-amber-400"></i>
                    <span>Pengaturan Durasi Waktu Babak</span>
                </h3>
                <button onclick="document.getElementById('settingsModal').classList.add('hidden')" class="text-white/80 hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('admin.cerdas-cermat.settings') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label for="round_1_duration" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Durasi Babak 1 (Pilihan Ganda)</label>
                        <div class="relative rounded-xl shadow-2xs">
                            <input type="number" name="round_1_duration" id="round_1_duration" min="1" required
                                value="{{ $settings['round_1_duration'] }}"
                                class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 pr-16 transition">
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-xs text-gray-400 font-semibold">
                                Menit
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="round_2_duration" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Durasi Babak 2 (Isian Singkat)</label>
                        <div class="relative rounded-xl shadow-2xs">
                            <input type="number" name="round_2_duration" id="round_2_duration" min="1" required
                                value="{{ $settings['round_2_duration'] }}"
                                class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 pr-16 transition">
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-xs text-gray-400 font-semibold">
                                Menit
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="round_3_duration" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Durasi Babak 3 (Uraian)</label>
                        <div class="relative rounded-xl shadow-2xs">
                            <input type="number" name="round_3_duration" id="round_3_duration" min="1" required
                                value="{{ $settings['round_3_duration'] }}"
                                class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 pr-16 transition">
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-xs text-gray-400 font-semibold">
                                Menit
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50/80 px-6 py-3.5 border-t border-gray-100 flex flex-row-reverse gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-brand-forest hover:bg-emerald-900 text-white rounded-xl text-xs font-bold shadow-xs transition">
                        Simpan Pengaturan
                    </button>
                    <button type="button"
                        class="px-4 py-2 bg-white text-gray-700 border border-gray-200 hover:bg-gray-50 rounded-xl text-xs font-semibold transition"
                        onclick="document.getElementById('settingsModal').classList.add('hidden')">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>