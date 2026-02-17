@extends('layouts.main')

@section('title', 'Tambah Soal Cerdas Cermat - Admin')

@section('content')
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Tambah Soal Baru</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <form action="{{ route('admin.cerdas-cermat.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700">Tipe Soal</label>
                            <select name="type" id="type" onchange="toggleOptions()"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-scout-primary focus:border-scout-primary sm:text-sm rounded-md">
                                <option value="Pilihan Ganda">Pilihan Ganda</option>
                                <option value="Isian Singkat">Isian Singkat</option>
                                <option value="Uraian">Uraian</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="question" class="block text-sm font-medium text-gray-700">Pertanyaan</label>
                            <textarea name="question" id="question" rows="3" required
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-scout-primary focus:border-scout-primary"></textarea>
                        </div>

                        <div id="options-container" class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilihan Jawaban</label>

                            @foreach(['a', 'b', 'c', 'd', 'e'] as $opt)
                                <div class="flex items-center mb-2">
                                    <span class="w-8 text-center text-gray-500 font-bold capitalize">{{ $opt }}</span>
                                    <input type="text" name="options[{{ $opt }}]"
                                        class="flex-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-scout-primary focus:border-scout-primary"
                                        placeholder="Pilihan {{ strtoupper($opt) }}">
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-4">
                            <label for="correct_answer" class="block text-sm font-medium text-gray-700">Jawaban
                                Benar</label>
                            <input type="text" name="correct_answer" id="correct_answer" required
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-scout-primary focus:border-scout-primary"
                                placeholder="Untuk PG tulis huruf kunci (misal: a), lainnya tulis jawaban lengkap">
                        </div>

                        <div class="mb-6">
                            <label for="score" class="block text-sm font-medium text-gray-700">Nilai</label>
                            <input type="number" name="score" id="score" required min="0" value="10"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-scout-primary focus:border-scout-primary">
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 sm:gap-0">
                            <a href="{{ route('admin.cerdas-cermat.index') }}"
                                class="w-full sm:w-auto bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-scout-primary sm:mr-3 text-center">
                                Batal
                            </a>
                            <button type="submit"
                                class="w-full sm:w-auto inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-bold rounded-md text-white bg-scout-primary hover:bg-scout-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-scout-primary">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
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

        // Init state
        toggleOptions();
    </script>
@endsection