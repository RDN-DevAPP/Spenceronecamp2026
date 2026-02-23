@extends('layouts.main')

@section('title', 'Kelola Soal Cerdas Cermat - Admin')

@section('content')
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4 sm:gap-0">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Kelola Soal Cerdas Cermat</h1>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                    <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                        class="bg-scout-accent text-scout-primary border-2 border-scout-primary px-4 py-2 rounded-md hover:bg-yellow-400 transition shadow-md w-full sm:w-auto text-center font-bold">
                        <i data-lucide="upload" class="inline-block w-4 h-4 mr-1"></i> Import CSV
                    </button>
                    <button onclick="document.getElementById('settingsModal').classList.remove('hidden')"
                        class="bg-white text-scout-primary border-2 border-scout-primary px-4 py-2 rounded-md hover:bg-gray-50 transition shadow-md w-full sm:w-auto text-center font-bold">
                        <i data-lucide="clock" class="inline-block w-4 h-4 mr-1"></i> Waktu
                    </button>
                    <button onclick="confirmDeleteAll('{{ $activeTab }}')"
                        class="bg-red-700 text-white border-2 border-red-900 px-4 py-2 rounded-md hover:bg-red-800 transition shadow-md w-full sm:w-auto text-center font-bold">
                        <i data-lucide="trash-2" class="inline-block w-4 h-4 mr-1"></i> Hapus Semua
                    </button>
                    <form id="delete-all-form" action="{{ route('admin.cerdas-cermat.destroyAll') }}" method="POST"
                        class="hidden">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="type" id="delete-all-type">
                    </form>
                    <a href="{{ route('admin.cerdas-cermat.create') }}"
                        class="bg-scout-primary text-white border-2 border-scout-secondary px-4 py-2 rounded-md hover:bg-scout-primary/90 transition shadow-md w-full sm:w-auto text-center font-bold">
                        <i data-lucide="plus" class="inline-block w-4 h-4 mr-1"></i> Tambah Soal
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-6 shadow-sm"
                    role="alert">
                    <span class="block sm:inline font-semibold">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded relative mb-6 shadow-sm"
                    role="alert">
                    <span class="block sm:inline font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            <div class="mb-8 p-6 bg-white overflow-hidden shadow-md rounded-xl border border-gray-100 hover:shadow-lg transition-shadow duration-300 group">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center mb-2">
                            <div class="bg-rose-50 p-2 rounded-lg group-hover:bg-rose-100 transition-colors mr-3">
                                <i data-lucide="users" class="w-6 h-6 text-rose-600"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Manajemen Sesi & Peserta</h3>
                        </div>
                        <p class="text-gray-500 text-sm">Kontrol sesi aktif, reset status login peserta, dan pantau progress pengerjaan layar Juri & Peserta di sini.</p>
                    </div>
                    
                    <a href="{{ route('admin.cerdas-cermat.sessions') }}"
                        class="inline-flex items-center justify-center shrink-0 w-full sm:w-auto px-6 py-3 bg-rose-600 text-white border border-transparent rounded-lg font-bold hover:bg-rose-700 transition-all duration-200">
                        <span>Buka Panel Sesi</span>
                        <i data-lucide="external-link" class="w-4 h-4 ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Tabs -->
            <div class="mb-6 border-b-2 border-scout-secondary overflow-x-auto">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    @foreach(['Pilihan Ganda', 'Isian Singkat', 'Uraian'] as $tab)
                        <a href="{{ route('admin.cerdas-cermat.index', ['tab' => $tab]) }}"
                            class="{{ $activeTab === $tab ? 'border-scout-primary text-scout-primary bg-scout-light' : 'border-transparent text-gray-500 hover:text-scout-primary hover:border-scout-secondary' }}
                                                                  whitespace-nowrap py-4 px-4 border-b-2 font-bold text-sm transition-colors duration-200 rounded-t-md">
                            {{ $tab }}
                        </a>
                    @endforeach
                </nav>
            </div>

            <div class="bg-white shadow-lg border-2 border-scout-secondary rounded-lg overflow-hidden">
                <!-- Mobile Card View -->
                <div class="md:hidden">
                    @forelse($questions as $index => $question)
                        <div class="p-4 border-b border-gray-200 hover:bg-scout-light transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-scout-primary rounded-full">
                                    No. {{ $index + 1 }}
                                </span>
                                <span class="text-sm font-bold text-scout-primary">{{ $question->score }} Poin</span>
                            </div>
                            <div class="mb-2">
                                <p class="text-gray-900 font-medium">{{ $question->question }}</p>
                            </div>
                            @if($question->type === 'Pilihan Ganda')
                                <div class="mb-3 p-2 bg-green-50 rounded border border-green-100">
                                    <span class="text-xs text-gray-500 font-semibold uppercase">Jawaban Benar:</span>
                                    <p class="text-sm text-green-700 font-mono font-bold">{{ $question->correct_answer }}</p>
                                </div>
                            @endif
                            
                            <div class="flex justify-end gap-3 mt-3 pt-3 border-t border-gray-100">
                                <a href="{{ route('admin.cerdas-cermat.edit', $question->id) }}"
                                    class="text-indigo-600 hover:text-indigo-900 font-semibold text-sm flex items-center">
                                    <i data-lucide="edit-2" class="w-4 h-4 mr-1"></i> Edit
                                </a>
                                <form action="{{ route('admin.cerdas-cermat.destroy', $question->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-900 font-semibold text-sm flex items-center">
                                        <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <i data-lucide="file-question" class="w-12 h-12 mx-auto mb-2 opacity-30"></i>
                            <p class="italic">Belum ada soal untuk kategori ini.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-scout-light">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-bold text-scout-primary uppercase tracking-wider">
                                    No</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-bold text-scout-primary uppercase tracking-wider w-1/2">
                                    Soal</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-bold text-scout-primary uppercase tracking-wider">
                                    Jawaban Benar</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-bold text-scout-primary uppercase tracking-wider">
                                    Nilai</th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-bold text-scout-primary uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($questions as $index => $question)
                                <tr class="hover:bg-scout-light transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">{{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <div class="line-clamp-2">{{ $question->question }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        @if($question->correct_answer)
                                            <span class="inline-block max-w-xs truncate font-mono bg-green-50 text-green-700 px-2 py-1 rounded font-bold border border-green-100">
                                                {{ $question->correct_answer }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 italic">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                        {{ $question->score }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.cerdas-cermat.edit', $question->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900 font-semibold p-1 hover:bg-indigo-50 rounded">
                                                <i data-lucide="edit-2" class="w-4 h-4"></i>
                                            </a>
                                            <form action="{{ route('admin.cerdas-cermat.destroy', $question->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini?');"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900 font-semibold p-1 hover:bg-red-50 rounded">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
                                        <div class="flex flex-col items-center justify-center">
                                            <i data-lucide="clipboard-x" class="w-12 h-12 text-gray-300 mb-3"></i>
                                            <p>Belum ada soal untuk kategori ini.</p>
                                            <span class="text-xs mt-1">Silakan tambah soal baru atau import dari CSV.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.dashboard') }}"
                    class="text-scout-primary hover:text-scout-accent font-semibold flex items-center transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div id="importModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true"
                onclick="document.getElementById('importModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border-2 border-scout-primary">
                <div class="bg-scout-primary px-4 py-3 sm:px-6">
                    <h3 class="text-lg leading-6 font-bold text-scout-light" id="modal-title">Import Soal CSV</h3>
                </div>
                <form action="{{ route('admin.cerdas-cermat.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <div class="mt-2 text-sm text-gray-500 bg-scout-light p-3 rounded-md border border-scout-secondary"
                                    id="import-help-text">
                                    <p class="font-bold text-scout-primary">Format CSV:</p>
                                    <p class="font-mono text-xs mt-1">No, Soal, Pilihan 1, Pilihan 2, Pilihan 3, Pilihan 4,
                                        Pilihan 5, Jawaban Benar, Nilai</p>
                                    <p class="mt-1 text-xs text-scout-primary">Pilihan 1-5 abaikan jika bukan Pilihan Ganda.
                                    </p>
                                </div>
                                <div class="mt-4">
                                    <label for="import-type" class="block text-sm font-bold text-scout-primary mb-1">Tipe
                                        Soal</label>
                                    <select name="type" id="import-type" onchange="updateImportHelp()"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-scout-secondary focus:outline-none focus:ring-scout-primary focus:border-scout-primary sm:text-sm rounded-md bg-gray-50">
                                        <option value="Pilihan Ganda">Pilihan Ganda</option>
                                        <option value="Isian Singkat">Isian Singkat</option>
                                        <option value="Uraian">Uraian</option>
                                    </select>
                                </div>
                                <div class="mt-4">
                                    <label for="file" class="block text-sm font-bold text-scout-primary mb-1">File
                                        CSV</label>
                                    <input type="file" name="file" id="file" accept=".csv" required
                                        class="mt-1 block w-full text-sm text-gray-900 border border-scout-secondary rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-scout-primary file:text-white hover:file:bg-scout-primary/90">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-scout-primary text-base font-bold text-white hover:bg-scout-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-scout-primary sm:ml-3 sm:w-auto sm:text-sm">
                            Import
                        </button>
                        <button type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-scout-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            onclick="document.getElementById('importModal').classList.add('hidden')">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('admin.cerdas-cermat.settings_modal')

    <script>
        function updateImportHelp() {
            const type = document.getElementById('import-type').value;
            const helpText = document.getElementById('import-help-text');

            if (type === 'Pilihan Ganda') {
                helpText.innerHTML = `
                        <p class="font-bold text-scout-primary">Format CSV Pilihan Ganda:</p>
                        <p class="font-mono text-xs mt-1">No, Soal, Pilihan 1, Pilihan 2, Pilihan 3, Pilihan 4, Pilihan 5, Jawaban Benar, Nilai</p>
                        <p class="mt-1 text-xs text-scout-primary">Pastikan urutan kolom sesuai.</p>
                    `;
            } else {
                helpText.innerHTML = `
                        <p class="font-bold text-scout-primary">Format CSV Isian/Uraian:</p>
                        <p class="font-mono text-xs mt-1">No, Soal, Nilai Maksimal</p>
                        <p class="mt-1 text-xs text-scout-primary">Kolom hanya 3: Nomor urut, Pertanyaan, dan Nilai Maksimal.</p>
                    `;
            }
        }

        function confirmDeleteAll(type) {
            Swal.fire({
                title: 'Siap Hapus Semua?',
                text: `Semua soal "${type}" akan dihapus permanen!`,
                icon: 'warning',
                color: '#5D4037',
                background: '#FFF8E1',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#5D4037',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                width: '400px',
                padding: '2em',
                customClass: {
                    confirmButton: 'font-bold rounded-lg',
                    cancelButton: 'font-bold rounded-lg',
                    popup: 'border-2 border-scout-primary shadow-xl'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-all-type').value = type;
                    document.getElementById('delete-all-form').submit();
                }
            })
        }
    </script>
@endsection