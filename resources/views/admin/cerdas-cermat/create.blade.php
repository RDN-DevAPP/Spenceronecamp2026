@extends('layouts.main')

@section('title', 'Tambah Soal Cerdas Cermat - Admin Spencerone Camp 2026')

@section('content')
    <div class="py-10 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-brand-forest uppercase tracking-tight">
                        Tambah Soal Baru
                    </h1>
                    <p class="text-xs text-gray-500 mt-0.5">Masukkan pertanyaan, bobot nilai, dan kunci jawaban</p>
                </div>
                <a href="{{ route('admin.cerdas-cermat.index') }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
                </a>
            </div>

            <div class="bg-white shadow-sm border border-gray-200/80 rounded-2xl overflow-hidden">
                <form action="{{ route('admin.cerdas-cermat.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-5">
                    @csrf

                    <div>
                        <label for="type" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Tipe Soal / Babak</label>
                        <select name="type" id="type" onchange="toggleOptions()"
                            class="block w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition">
                            <option value="Pilihan Ganda">Pilihan Ganda (Babak 1)</option>
                            <option value="Isian Singkat">Isian Singkat (Babak 2)</option>
                            <option value="Uraian">Uraian (Babak 3)</option>
                        </select>
                    </div>

                    <div>
                        <label for="question" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Teks Pertanyaan</label>
                        <textarea name="question" id="question" rows="4" required
                            class="block w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition"
                            placeholder="Tuliskan butir soal secara lengkap di sini..."></textarea>
                    </div>

                    <div>
                        <label for="image" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Gambar Pendukung / Ilustrasi (Opsional)</label>
                        <input type="file" name="image" id="image" accept="image/*"
                            class="block w-full text-xs text-gray-600 border border-gray-300 rounded-xl cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-brand-forest hover:file:bg-emerald-200 transition">
                        <p class="text-[11px] text-gray-400 mt-1">Format: JPG, PNG, WEBP. Maks 3MB.</p>
                    </div>

                    <div id="options-container" class="space-y-3 pt-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">Pilihan Jawaban</label>
                        @foreach(['a', 'b', 'c', 'd', 'e'] as $opt)
                            <div class="flex items-center gap-2">
                                <span class="w-8 h-8 rounded-xl bg-gray-100 text-gray-700 font-black text-xs flex items-center justify-center shrink-0 uppercase border border-gray-200">
                                    {{ $opt }}
                                </span>
                                <input type="text" name="options[{{ $opt }}]"
                                    class="block w-full px-3 py-2 text-sm rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition"
                                    placeholder="Teks pilihan {{ strtoupper($opt) }}">
                            </div>
                        @endforeach
                    </div>

                    <div>
                        <label for="correct_answer" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Kunci Jawaban</label>
                        <input type="text" name="correct_answer" id="correct_answer" required
                            class="block w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition"
                            placeholder="Untuk PG ketik huruf kunci (misal: a), untuk isian tuliskan kata kunci">
                    </div>

                    <div>
                        <label for="score" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Bobot Nilai</label>
                        <input type="number" name="score" id="score" required min="1" value="10"
                            class="block w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition">
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-2.5 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.cerdas-cermat.index') }}"
                            class="w-full sm:w-auto px-5 py-2.5 border border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50 text-center transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="w-full sm:w-auto px-6 py-2.5 bg-brand-forest hover:bg-emerald-900 text-white rounded-xl text-xs font-bold shadow-sm transition">
                            Simpan Soal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleOptions() {
            const type = document.getElementById('type').value;
            const container = document.getElementById('options-container');
            const optionsInputs = container.querySelectorAll('input');

            if (type === 'Pilihan Ganda') {
                container.classList.remove('hidden');
                optionsInputs.forEach(input => input.required = true);
            } else {
                container.classList.add('hidden');
                optionsInputs.forEach(input => input.required = false);
            }
        }
        toggleOptions();
    </script>
@endsection