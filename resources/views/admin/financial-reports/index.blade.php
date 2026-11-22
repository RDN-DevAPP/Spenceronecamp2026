@extends('layouts.main')

@section('title', 'Kelola Laporan Keuangan - Admin Spencerone Camp 2026')

@section('content')
    <div class="py-10 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(isset($errors) && $errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl">
                    <div class="flex items-start gap-2.5">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-red-500 shrink-0 mt-0.5"></i>
                        <div>
                            <h3 class="text-xs font-bold text-red-800 uppercase tracking-wider">Terdapat kesalahan:</h3>
                            <ul class="mt-1 text-xs text-red-700 list-disc list-inside space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200/60 text-xs font-semibold uppercase tracking-wider mb-2">
                        <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5 text-emerald-600"></i>
                        Transparansi Anggaran
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight">
                        Kelola Laporan Keuangan
                    </h1>
                    <p class="mt-1 text-xs sm:text-sm text-gray-500">Unggah dan kelola berkas laporan transparansi keuangan untuk publik.</p>
                </div>
                <div>
                    <button onclick="openUploadModal()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-xs sm:text-sm text-white bg-brand-forest hover:bg-emerald-900 border border-emerald-800 shadow-sm transition">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Unggah Laporan Baru</span>
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/70">
                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                        <i data-lucide="folder" class="w-4 h-4 text-emerald-700"></i>
                        <span>Daftar Dokumen Laporan</span>
                    </h3>
                    <span class="text-xs font-bold text-brand-forest bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200/60">
                        {{ count($reports) }} Dokumen
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/80 text-gray-600">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Laporan</th>
                                <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold uppercase tracking-wider">Berkas</th>
                                <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold uppercase tracking-wider">Tanggal Unggah</th>
                                <th scope="col" class="px-6 py-3.5 text-right text-xs font-bold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($reports as $report)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-emerald-50 text-brand-forest rounded-xl flex items-center justify-center shrink-0 border border-emerald-200/50">
                                                <i data-lucide="{{ str_contains($report->file_path, '.pdf') ? 'file-text' : 'image' }}" class="w-5 h-5"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $report->title }}</div>
                                                <div class="text-xs text-gray-400 font-mono">{{ basename($report->file_path) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-medium">
                                        <a href="{{ Storage::url($report->file_path) }}" target="_blank"
                                            class="inline-flex items-center gap-1 text-emerald-800 hover:text-emerald-950 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200 font-bold transition">
                                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i> Buka File
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($report->is_active)
                                            <span class="px-2.5 py-0.5 inline-flex text-xs font-bold rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">Aktif</span>
                                        @else
                                            <span class="px-2.5 py-0.5 inline-flex text-xs font-bold rounded-full bg-gray-100 text-gray-600 border border-gray-200">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-xs text-gray-500 font-medium">
                                        {{ $report->created_at->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs">
                                        <button onclick="confirmDeleteReport('{{ route('admin.financial-reports.destroy', $report) }}')"
                                            class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus Laporan">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                                        <i data-lucide="file-x" class="w-10 h-10 text-gray-300 mx-auto mb-2"></i>
                                        <p class="text-sm font-semibold text-gray-700">Belum ada laporan keuangan</p>
                                        <p class="text-xs text-gray-400 mt-0.5">Gunakan tombol di atas untuk mengunggah dokumen baru.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-brand-forest transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="uploadModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs transition-opacity" aria-hidden="true" onclick="closeUploadModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200">
                <div class="bg-gradient-to-r from-brand-forest to-emerald-800 px-6 py-4 text-white flex justify-between items-center">
                    <h3 class="text-base font-bold flex items-center gap-2" id="modal-title">
                        <i data-lucide="upload-cloud" class="w-4 h-4 text-amber-400"></i>
                        <span>Unggah Dokumen Laporan</span>
                    </h3>
                    <button onclick="closeUploadModal()" class="text-white/80 hover:text-white">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <form action="{{ route('admin.financial-reports.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="title" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Judul Dokumen Laporan</label>
                            <input type="text" name="title" id="title"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-medium transition"
                                placeholder="Contoh: Laporan Realisasi Anggaran LT-I Season II" required>
                        </div>
                        <div>
                            <label for="file" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Berkas Laporan (PDF Saja)</label>
                            <input id="file" name="file" type="file" required accept=".pdf"
                                class="block w-full text-xs text-gray-600 border border-gray-300 rounded-xl cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-brand-forest hover:file:bg-emerald-200 transition">
                            <p class="text-[11px] text-gray-400 mt-1">Format PDF resmi, ukuran maksimal 5MB.</p>
                        </div>
                        <div class="flex items-center gap-2.5 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <input type="checkbox" name="is_active" id="is_active" value="1" checked
                                class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 rounded border-gray-300 cursor-pointer">
                            <label for="is_active" class="text-xs font-bold text-gray-700 cursor-pointer">Publikasikan dan aktifkan langsung di beranda</label>
                        </div>
                    </div>
                    <div class="bg-gray-50/80 px-6 py-3.5 border-t border-gray-100 flex flex-row-reverse gap-2">
                        <button type="submit"
                            class="px-5 py-2 rounded-xl bg-brand-forest hover:bg-emerald-900 text-white text-xs font-bold shadow-xs transition">
                            Unggah Laporan
                        </button>
                        <button type="button" onclick="closeUploadModal()"
                            class="px-4 py-2 bg-white text-gray-700 border border-gray-200 hover:bg-gray-50 rounded-xl text-xs font-semibold transition">
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
            if (confirm('Apakah Anda yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan.')) {
                const form = document.getElementById('delete-report-form');
                form.action = url;
                form.submit();
            }
        }
    </script>
@endsection