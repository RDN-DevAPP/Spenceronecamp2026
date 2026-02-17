<!-- Settings Modal -->
<div id="settingsModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true"
            onclick="document.getElementById('settingsModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border-2 border-scout-primary">
            <div class="bg-scout-primary px-4 py-3 sm:px-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-bold text-scout-light" id="modal-title">Pengaturan Waktu</h3>
                    <button onclick="document.getElementById('settingsModal').classList.add('hidden')"
                        class="text-scout-light hover:text-white">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
            <form action="{{ route('admin.cerdas-cermat.settings') }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="space-y-4">
                        <div>
                            <label for="round_1_duration" class="block text-sm font-bold text-gray-700">Durasi Babak 1
                                (Pilihan Ganda)</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" name="round_1_duration" id="round_1_duration" min="1" required
                                    value="{{ $settings['round_1_duration'] }}"
                                    class="focus:ring-scout-primary focus:border-scout-primary block w-full pr-12 sm:text-sm border-gray-300 rounded-md">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Menit</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="round_2_duration" class="block text-sm font-bold text-gray-700">Durasi Babak 2
                                (Isian Singkat)</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" name="round_2_duration" id="round_2_duration" min="1" required
                                    value="{{ $settings['round_2_duration'] }}"
                                    class="focus:ring-scout-primary focus:border-scout-primary block w-full pr-12 sm:text-sm border-gray-300 rounded-md">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Menit</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="round_3_duration" class="block text-sm font-bold text-gray-700">Durasi Babak 3
                                (Uraian)</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" name="round_3_duration" id="round_3_duration" min="1" required
                                    value="{{ $settings['round_3_duration'] }}"
                                    class="focus:ring-scout-primary focus:border-scout-primary block w-full pr-12 sm:text-sm border-gray-300 rounded-md">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Menit</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                    <button type="submit"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-scout-primary text-base font-bold text-white hover:bg-scout-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-scout-primary sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan Perubahan
                    </button>
                    <button type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-scout-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        onclick="document.getElementById('settingsModal').classList.add('hidden')">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>