@extends('layouts.peserta')

@section('title', 'Buku Anggota - Administrasi Regu ' . $regu->nama_regu)

@section('content')
<div class="py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- HEADER & ACTIONS -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white rounded-3xl p-6 border border-gray-200/90 shadow-sm">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-xs font-black uppercase tracking-wider mb-2">
                    <i data-lucide="id-card" class="w-3.5 h-3.5"></i> Buku I Administrasi Regu
                </div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Buku Anggota Regu</h1>
                <p class="text-xs text-gray-500 mt-0.5">Daftar induk anggota Regu {{ $regu->nama_regu }} ({{ ucfirst($regu->jenis) }}) dengan NTA & riwayat kecakapan.</p>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap">
                <a href="{{ route('peserta.administrasi.buku-anggota.print') }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold transition">
                    <i data-lucide="printer" class="w-4 h-4"></i> Cetak Buku Anggota
                </a>
                <a href="{{ route('peserta.administrasi.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-forest hover:bg-emerald-900 text-white text-xs font-bold transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-bold flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- TABEL BUKU ANGGOTA -->
        <div class="bg-white rounded-3xl border border-gray-200/90 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                    <thead class="bg-brand-forest text-white">
                        <tr>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider text-center w-12">No</th>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider">Foto</th>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider">Nama Lengkap & NTA</th>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider">Jabatan</th>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider">Tingkatan TKU</th>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider">Kelas</th>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider">Tempat, Tgl Lahir</th>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider">Agama / Alamat</th>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium text-gray-700">
                        @forelse($anggotaList as $idx => $anggota)
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="px-4 py-4 text-center font-bold text-gray-500">{{ $idx + 1 }}</td>
                                <td class="px-4 py-4">
                                    <div class="w-10 h-10 rounded-xl bg-gray-100 overflow-hidden border border-gray-200 shrink-0 flex items-center justify-center">
                                        @if($anggota->foto_path)
                                            <img src="{{ asset('storage/' . $anggota->foto_path) }}" alt="{{ $anggota->nama }}" class="w-full h-full object-cover">
                                        @else
                                            <i data-lucide="user" class="w-5 h-5 text-gray-400"></i>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="font-black text-gray-900 text-sm">{{ $anggota->nama }}</div>
                                    <div class="text-[11px] text-emerald-800 font-mono mt-0.5">
                                        NTA: <strong>{{ $anggota->nta ?: ($anggota->nisn ?: '-') }}</strong>
                                    </div>
                                    @if($anggota->tkk)
                                        <div class="text-[10px] text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md inline-block mt-1">
                                            TKK: {{ Str::limit($anggota->tkk, 30) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    @if($anggota->jabatan === 'pinru')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black bg-amber-100 text-amber-900 border border-amber-300">
                                            PINRU
                                        </span>
                                    @elseif($anggota->jabatan === 'wapinru')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black bg-emerald-100 text-emerald-900 border border-emerald-300">
                                            WAPINRU
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-gray-100 text-gray-700">
                                            ANGGOTA
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center gap-1 font-bold text-xs uppercase
                                        {{ $anggota->tingkatan_tku === 'terap' ? 'text-amber-800' : ($anggota->tingkatan_tku === 'rakit' ? 'text-emerald-800' : 'text-blue-800') }}">
                                        <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                                        Penggalang {{ ucfirst($anggota->tingkatan_tku) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 font-bold text-gray-900">Kelas {{ $anggota->kelas }}</td>
                                <td class="px-4 py-4 text-gray-600">
                                    @if($anggota->tempat_lahir || $anggota->tanggal_lahir)
                                        {{ $anggota->tempat_lahir ?? '-' }}, {{ $anggota->tanggal_lahir ? $anggota->tanggal_lahir->format('d/m/Y') : '-' }}
                                    @else
                                        <span class="text-gray-500 italic">Belum diisi</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-gray-600 max-w-xs truncate">
                                    <div>Agama: <span class="font-bold">{{ $anggota->agama ?: '-' }}</span></div>
                                    <div class="text-[11px] text-gray-600 truncate">{{ $anggota->alamat ?: '-' }}</div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <button type="button" onclick="openEditModal({{ json_encode($anggota) }})"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200 text-xs font-bold transition">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                    Belum ada data anggota regu. Tambahkan anggota di menu Dashboard utama.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- MODAL EDIT BIODATA & NTA ANGGOTA -->
<div id="editModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-gray-100 my-8">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
            <div>
                <h3 class="text-lg font-black text-gray-900">Lengkapi Data Buku Anggota</h3>
                <p class="text-xs text-gray-500" id="modalNamaAnggota">Nama Anggota</p>
            </div>
            <button type="button" onclick="closeEditModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="formEditAnggota" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">NTA (Nomor Tanda Anggota) <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <input type="text" name="nta" id="modal_nta" placeholder="Contoh: 03.059.001"
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">NISN <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <input type="text" name="nisn" id="modal_nisn" placeholder="10 Digit NISN"
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 font-mono">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Tingkatan TKU</label>
                    <select name="tingkatan_tku" id="modal_tingkatan_tku" required
                            class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500">
                        <option value="ramu">Penggalang Ramu</option>
                        <option value="rakit">Penggalang Rakit</option>
                        <option value="terap">Penggalang Terap</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Agama</label>
                    <input type="text" name="agama" id="modal_agama" placeholder="Islam, Kristen, dll"
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="modal_tempat_lahir" placeholder="Kota / Kabupaten"
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="modal_tanggal_lahir"
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Alamat Domisili</label>
                <textarea name="alamat" id="modal_alamat" rows="2" placeholder="Nama Jalan, Desa, RT/RW..."
                          class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">TKK yang Dimiliki <span class="text-gray-400 font-normal">(Opsional)</span></label>
                <input type="text" name="tkk" id="modal_tkk" placeholder="Contoh: Berkemah Purwa, Menjahit Madya, P3K Utama"
                       class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500">
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-brand-forest hover:bg-emerald-900 text-white text-xs font-bold transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(anggota) {
    document.getElementById('modalNamaAnggota').innerText = anggota.nama + ' (' + anggota.jabatan.toUpperCase() + ')';
    document.getElementById('formEditAnggota').action = `/peserta/administrasi/buku-anggota/${anggota.id}`;
    document.getElementById('modal_nta').value = anggota.nta || '';
    document.getElementById('modal_nisn').value = anggota.nisn || '';
    document.getElementById('modal_tingkatan_tku').value = anggota.tingkatan_tku || 'ramu';
    document.getElementById('modal_agama').value = anggota.agama || '';
    document.getElementById('modal_tempat_lahir').value = anggota.tempat_lahir || '';
    document.getElementById('modal_tanggal_lahir').value = anggota.tanggal_lahir ? anggota.tanggal_lahir.substring(0, 10) : '';
    document.getElementById('modal_alamat').value = anggota.alamat || '';
    document.getElementById('modal_tkk').value = anggota.tkk || '';

    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection
