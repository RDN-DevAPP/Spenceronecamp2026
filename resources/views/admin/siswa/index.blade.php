@extends('layouts.main')

@section('title', 'Daftar Siswa - Admin')

@section('content')
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Daftar Siswa</h1>
                <a href="{{ route('admin.dashboard') }}" class="text-scout-primary hover:underline text-sm font-medium">
                    &larr; Kembali ke Dashboard
                </a>
            </div>

            <!-- Tabs -->
            <div class="flex space-x-2 mb-6 bg-white rounded-xl p-1.5 shadow-sm border border-gray-200 max-w-md">
                <button onclick="showKelasTab(7)" id="tab-kelas-7"
                    class="flex-1 px-4 py-2.5 rounded-lg font-semibold text-sm transition-all bg-scout-primary text-white shadow">
                    Kelas 7 <span class="ml-1 bg-white/20 px-1.5 py-0.5 rounded text-xs">{{ $siswaKelas7->count() }}</span>
                </button>
                <button onclick="showKelasTab(8)" id="tab-kelas-8"
                    class="flex-1 px-4 py-2.5 rounded-lg font-semibold text-sm transition-all text-gray-600 hover:bg-gray-100">
                    Kelas 8 <span class="ml-1 bg-gray-200 px-1.5 py-0.5 rounded text-xs">{{ $siswaKelas8->count() }}</span>
                </button>
                <button onclick="showKelasTab(9)" id="tab-kelas-9"
                    class="flex-1 px-4 py-2.5 rounded-lg font-semibold text-sm transition-all text-gray-600 hover:bg-gray-100">
                    Kelas 9 <span class="ml-1 bg-gray-200 px-1.5 py-0.5 rounded text-xs">{{ $siswaKelas9->count() }}</span>
                </button>
            </div>

            @foreach([7, 8, 9] as $kelas)
                @php
                    $siswa = ${'siswaKelas' . $kelas};
                @endphp
                <div id="content-kelas-{{ $kelas }}" class="{{ $kelas !== 7 ? 'hidden' : '' }}">
                    <!-- Add Student Form -->
                    <div class="bg-white rounded-xl shadow-md p-6 mb-6 border border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-gray-900 flex items-center">
                                <i data-lucide="user-plus" class="w-5 h-5 mr-2 text-scout-primary"></i>
                                Tambah Siswa Kelas {{ $kelas }}
                            </h3>
                            @if($siswa->count() > 0)
                                <form id="delete-all-{{ $kelas }}" action="{{ route('admin.siswa.delete-all', $kelas) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDeleteAll('delete-all-{{ $kelas }}', {{ $kelas }})"
                                        class="text-red-500 hover:text-red-700 text-sm font-semibold flex items-center bg-red-50 px-3 py-1.5 rounded-lg border border-red-100 transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4 mr-1.5"></i>
                                        Hapus Semua Data Kelas {{ $kelas }}
                                    </button>
                                </form>
                            @endif
                        </div>
                        <form action="{{ route('admin.siswa.store') }}" method="POST" class="flex flex-wrap gap-3 items-end">
                            @csrf
                            <input type="hidden" name="kelas" value="{{ $kelas }}">
                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Siswa</label>
                                <input type="text" name="nama" required placeholder="Nama lengkap"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-scout-primary/30 focus:border-scout-primary transition text-sm">
                            </div>
                            <div class="w-40">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                                <select name="jenis_kelamin"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-scout-primary/30 focus:border-scout-primary transition text-sm">
                                    <option value="">- Pilih Jenis Kelamin -</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <button type="submit"
                                class="px-6 py-2.5 bg-scout-primary text-white rounded-lg font-semibold text-sm hover:bg-scout-primary/90 transition shadow-sm">
                                <i data-lucide="plus" class="w-4 h-4 inline mr-1"></i> Tambah
                            </button>
                        </form>
                    </div>

                    {{-- CSV Upload --}}
                    <div class="bg-white rounded-xl shadow-md p-6 mb-6 border border-gray-200">
                        <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                            <i data-lucide="file-up" class="w-5 h-5 mr-2 text-scout-accent"></i>
                            Import CSV Kelas {{ $kelas }}
                        </h3>
                        <form action="{{ route('admin.siswa.import-csv') }}" method="POST" enctype="multipart/form-data"
                            class="flex flex-wrap gap-3 items-end">
                            @csrf
                            <input type="hidden" name="kelas" value="{{ $kelas }}">
                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1">File CSV</label>
                                <input type="file" name="csv_file" accept=".csv,.txt" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-scout-accent/10 file:text-scout-accent hover:file:bg-scout-accent/20 transition">
                            </div>
                            <button type="submit"
                                class="px-6 py-2.5 bg-scout-accent text-white rounded-lg font-semibold text-sm hover:bg-amber-700 transition shadow-sm">
                                <i data-lucide="upload" class="w-4 h-4 inline mr-1"></i> Import
                            </button>
                        </form>
                        <div class="mt-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-500 font-medium mb-1">Format CSV yang diterima:</p>
                            <code class="text-[11px] text-gray-600 font-mono block">nama,jenis_kelamin</code>
                            <code class="text-[11px] text-gray-600 font-mono block">Ahmad Fauzi,L</code>
                            <code class="text-[11px] text-gray-600 font-mono block">Siti Aisyah,P</code>
                            <p class="text-[10px] text-gray-400 mt-1">Kolom: <b>nama</b> (wajib), <b>jenis_kelamin</b> atau <b>jk</b> (Laki-laki/Perempuan, L/P, Male/Female, opsional)</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider w-16">No</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Nama</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider w-32">Jenis Kelamin</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider w-32">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @forelse($siswa as $index => $s)
                                        <tr class="hover:bg-gray-50/50 transition-colors" id="row-{{ $s->id }}">
                                            <!-- Display Mode -->
                                            <td class="display-cell px-6 py-3.5 whitespace-nowrap text-sm text-gray-600 text-center">{{ $index + 1 }}</td>
                                            <td class="display-cell px-6 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900">{{ $s->nama }}</td>
                                            <td class="display-cell px-6 py-3.5 whitespace-nowrap text-center">
                                                @if($s->jenis_kelamin === 'L')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-scout-primary/10 text-scout-primary">
                                                        <i data-lucide="circle-user" class="w-3 h-3 mr-1"></i> L
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                                        <i data-lucide="circle-user" class="w-3 h-3 mr-1"></i> P
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="display-cell px-6 py-3.5 whitespace-nowrap text-center">
                                                <button onclick="startEdit({{ $s->id }})" class="text-scout-primary hover:text-scout-primary/70 mr-2 text-sm font-medium">
                                                    <i data-lucide="pencil" class="w-4 h-4 inline"></i>
                                                </button>
                                                <form id="delete-siswa-{{ $s->id }}" action="{{ route('admin.siswa.destroy', $s) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="confirmDelete('delete-siswa-{{ $s->id }}')" class="text-red-500 hover:text-red-700 text-sm font-medium">
                                                        <i data-lucide="trash-2" class="w-4 h-4 inline"></i>
                                                    </button>
                                                </form>
                                            </td>

                                            <!-- Edit Mode (hidden by default) -->
                                            <td class="edit-cell hidden px-6 py-3.5 text-center text-sm text-gray-400" colspan="1">{{ $index + 1 }}</td>
                                            <td class="edit-cell hidden px-6 py-2" colspan="1">
                                                <form action="{{ route('admin.siswa.update', $s) }}" method="POST" class="flex items-center space-x-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="kelas" value="{{ $kelas }}">
                                                    <input type="text" name="nama" value="{{ $s->nama }}" required
                                                        class="flex-1 px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-scout-primary/30">
                                                    <select name="jenis_kelamin"
                                                        class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-scout-primary/30">
                                                        <option value="" {{ is_null($s->jenis_kelamin) ? 'selected' : '' }}>-</option>
                                                        <option value="L" {{ $s->jenis_kelamin === 'L' ? 'selected' : '' }}>L</option>
                                                        <option value="P" {{ $s->jenis_kelamin === 'P' ? 'selected' : '' }}>P</option>
                                                    </select>
                                                    <button type="submit" class="px-3 py-1.5 bg-green-500 text-white rounded-lg text-xs font-bold">Simpan</button>
                                                    <button type="button" onclick="cancelEdit({{ $s->id }})" class="px-3 py-1.5 bg-gray-300 text-gray-700 rounded-lg text-xs font-bold">Batal</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                                <i data-lucide="inbox" class="w-10 h-10 text-gray-300 mx-auto mb-3"></i>
                                                <p class="font-medium">Belum ada data siswa kelas {{ $kelas }}</p>
                                                <p class="text-xs mt-1">Gunakan form di atas untuk menambah siswa.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function showKelasTab(kelas) {
        [7, 8, 9].forEach(k => {
            document.getElementById('content-kelas-' + k).classList.toggle('hidden', k !== kelas);
            const tab = document.getElementById('tab-kelas-' + k);
            if (k === kelas) {
                tab.className = 'flex-1 px-4 py-2.5 rounded-lg font-semibold text-sm transition-all bg-scout-primary text-white shadow';
            } else {
                tab.className = 'flex-1 px-4 py-2.5 rounded-lg font-semibold text-sm transition-all text-gray-600 hover:bg-gray-100';
            }
        });
        nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); });
    }

    function startEdit(id) {
        const row = document.getElementById('row-' + id);
        row.querySelectorAll('.display-cell').forEach(c => c.classList.add('hidden'));
        row.querySelectorAll('.edit-cell').forEach(c => c.classList.remove('hidden'));
    }

    function cancelEdit(id) {
        const row = document.getElementById('row-' + id);
        row.querySelectorAll('.display-cell').forEach(c => c.classList.remove('hidden'));
        row.querySelectorAll('.edit-cell').forEach(c => c.classList.add('hidden'));
    }

    function confirmDeleteAll(formId, kelas) {
        if (confirm('Apakah Anda yakin ingin menghapus SEMUA data siswa kelas ' + kelas + '? Tindakan ini tidak dapat dibatalkan.')) {
            document.getElementById(formId).submit();
        }
    }
</script>
@endpush
