@extends('layouts.main')

@section('title', 'Kelola Laporan Keuangan - Admin Dashboard')

@section('content')
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i data-lucide="alert-circle" class="h-5 w-5 text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-red-800">Terdapat kesalahan:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Kelola Laporan Keuangan</h1>
                    <p class="mt-1 text-sm text-gray-500">Unggah dan kelola laporan keuangan untuk ditampilkan di beranda.
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <button onclick="openUploadModal()"
                        class="inline-flex items-center px-6 py-3 bg-scout-primary border border-transparent rounded-xl font-bold text-white uppercase tracking-widest hover:bg-scout-primary-dark shadow-lg transition-all active:scale-95">
                        <i data-lucide="plus" class="w-5 h-5 mr-2"></i> Unggah Laporan Baru
                    </button>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Laporan</th>
                                <th scope="col"
                                    class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    File</th>
                                <th scope="col"
                                    class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th scope="col"
                                    class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Tanggal Unggah</th>
                                <th scope="col"
                                    class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($reports as $report)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-10 w-10 bg-scout-secondary/10 rounded-lg flex items-center justify-center">
                                                <i data-lucide="{{ str_contains($report->file_path, '.pdf') ? 'file-text' : 'image' }}"
                                                    class="w-6 h-6 text-scout-primary"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $report->title }}</div>
                                                <div class="text-xs text-gray-500">{{ basename($report->file_path) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <a href="{{ Storage::url($report->file_path) }}" target="_blank"
                                            class="inline-flex items-center text-scout-primary hover:text-scout-primary-dark transition-colors">
                                            <i data-lucide="external-link" class="w-4 h-4 mr-1"></i> Buka File
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($report->is_active)
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800 border border-green-200">Aktif</span>
                                        @else
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-gray-100 text-gray-600 border border-gray-200">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 font-medium">
                                        {{ $report->created_at->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center space-x-2">
                                            <button
                                                onclick="confirmDeleteReport('{{ route('admin.financial-reports.destroy', $report) }}')"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-transparent hover:border-red-100"
                                                title="Hapus">
                                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="p-6 bg-gray-50 rounded-full mb-4">
                                                <i data-lucide="clipboard-x" class="w-12 h-12 text-gray-300"></i>
                                            </div>
                                            <h3 class="text-lg font-bold text-gray-900">Belum ada laporan</h3>
                                            <p class="text-sm text-gray-500 max-w-xs mx-auto mt-1">Gunakan tombol di atas untuk
                                                mengunggah laporan keuangan pertama Anda.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="uploadModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeUploadModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border-t-8 border-scout-primary">
                <form action="{{ route('admin.financial-reports.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-6 pb-4 sm:p-8 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-scout-secondary/20 sm:mx-0 sm:h-12 sm:w-12">
                                <i data-lucide="upload-cloud" class="h-6 w-6 text-scout-primary"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-5 sm:text-left w-full">
                                <h3 class="text-xl leading-6 font-black text-gray-900" id="modal-title">Unggah Laporan</h3>
                                <div class="mt-6 space-y-5">
                                    <div>
                                        <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Judul
                                            Laporan</label>
                                        <input type="text" name="title" id="title"
                                            class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-scout-accent focus:border-scout-primary py-3 px-4 transition-all"
                                            placeholder="Contoh: Laporan LT-I 2026" required>
                                    </div>
                                    <div>
                                        <label for="file" class="block text-sm font-bold text-gray-700 mb-2">File
                                            (PDF Saja)</label>
                                        <div
                                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-scout-primary transition-colors bg-gray-50/50">
                                            <div class="space-y-2 text-center">
                                                <i data-lucide="file-up" class="mx-auto h-10 w-10 text-gray-400"></i>
                                                <div class="flex text-sm text-gray-600">
                                                    <label for="file"
                                                        class="relative cursor-pointer bg-white rounded-md font-bold text-scout-primary hover:text-scout-primary-dark">
                                                        <span>Pilih file</span>
                                                        <input id="file" name="file" type="file" class="sr-only" required
                                                            accept=".pdf">
                                                    </label>
                                                    <p class="pl-1">atau drag & drop</p>
                                                </div>
                                                <p class="text-xs text-gray-500 italic">Maksimal 5MB</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center p-3 bg-gray-50 rounded-xl border border-gray-100">
                                        <input type="checkbox" name="is_active" id="is_active" value="1" checked
                                            class="h-5 w-5 text-scout-primary focus:ring-scout-accent border-gray-300 rounded-md cursor-pointer transition-all">
                                        <label for="is_active"
                                            class="ml-3 block text-sm font-bold text-gray-700 cursor-pointer">Aktifkan
                                            Sekarang</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-5 sm:px-8 sm:flex sm:flex-row-reverse gap-3">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-lg px-6 py-3 bg-scout-primary text-base font-bold text-white hover:bg-scout-primary-dark sm:w-auto transition-all active:scale-95">
                            Simpan & Unggah
                        </button>
                        <button type="button" onclick="closeUploadModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-all">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Hidden Delete Form -->
    <form id="delete-report-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function openUploadModal() {
            document.getElementById('uploadModal').classList.remove('hidden');
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').classList.add('hidden');
        }

        function confirmDeleteReport(url) {
            if (confirm('Apakah Anda yakin ingin menghapus laporan ini? Tindakan ini tidak bisa dibatalkan.')) {
                const form = document.getElementById('delete-report-form');
                form.action = url;
                form.submit();
            }
        }
    </script>
@endsection