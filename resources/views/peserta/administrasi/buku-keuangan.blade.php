@extends('layouts.peserta')

@section('title', 'Buku Keuangan - Administrasi Regu ' . $regu->nama_regu)

@section('content')
<div class="py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- HEADER & ACTIONS -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white rounded-3xl p-6 border border-gray-200/90 shadow-sm">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 text-amber-800 text-xs font-black uppercase tracking-wider mb-2">
                    <i data-lucide="coins" class="w-3.5 h-3.5"></i> Buku II Administrasi Regu
                </div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Buku Keuangan Kas Regu</h1>
                <p class="text-xs text-gray-500 mt-0.5">Catatan iuran, pemasukan, dan pengeluaran regu {{ $regu->nama_regu }}.</p>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap">
                <button type="button" onclick="openAddModal()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold transition shadow-sm">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Transaksi
                </button>
                <a href="{{ route('peserta.administrasi.buku-keuangan.print') }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold transition">
                    <i data-lucide="printer" class="w-4 h-4"></i> Cetak Buku Kas
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

        <!-- BALANCE CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-emerald-100 shadow-sm">
                <div class="text-xs font-bold text-emerald-800 uppercase tracking-wider flex items-center gap-1.5">
                    <i data-lucide="arrow-down-left" class="w-4 h-4 text-emerald-600"></i> Total Pemasukan
                </div>
                <div class="text-2xl font-black text-emerald-700 mt-2">Rp {{ number_format($kasMasuk, 0, ',', '.') }}</div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-red-100 shadow-sm">
                <div class="text-xs font-bold text-red-800 uppercase tracking-wider flex items-center gap-1.5">
                    <i data-lucide="arrow-up-right" class="w-4 h-4 text-red-600"></i> Total Pengeluaran
                </div>
                <div class="text-2xl font-black text-red-700 mt-2">Rp {{ number_format($kasKeluar, 0, ',', '.') }}</div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-amber-200 shadow-sm bg-gradient-to-br from-white to-amber-50/50">
                <div class="text-xs font-bold text-amber-900 uppercase tracking-wider flex items-center gap-1.5">
                    <i data-lucide="wallet" class="w-4 h-4 text-amber-600"></i> Saldo Kas Saat Ini
                </div>
                <div class="text-2xl font-black text-amber-800 mt-2">Rp {{ number_format($saldoKas, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- TABEL ARUS KAS -->
        <div class="bg-white rounded-3xl border border-gray-200/90 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                    <thead class="bg-brand-forest text-white">
                        <tr>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider text-center w-12">No</th>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider">Uraian Transaksi</th>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider">Kategori</th>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider text-right">Masuk (Debit)</th>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider text-right">Keluar (Kredit)</th>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider text-center">Bukti / Struk</th>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium text-gray-700">
                        @forelse($finances as $idx => $row)
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="px-4 py-3.5 text-center font-bold text-gray-500">{{ $finances->firstItem() + $idx }}</td>
                                <td class="px-4 py-3.5 font-bold text-gray-900 whitespace-nowrap">{{ $row->tanggal->format('d/m/Y') }}</td>
                                <td class="px-4 py-3.5 font-bold text-gray-900">
                                    {{ $row->uraian }}
                                    @if($row->keterangan)
                                        <div class="text-[11px] text-gray-500 font-normal">{{ $row->keterangan }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-block px-2 py-0.5 rounded-md bg-gray-100 text-gray-700 text-[10px] font-bold">
                                        {{ $row->kategori ?: 'Umum' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-right font-black text-emerald-700 whitespace-nowrap">
                                    {{ $row->jenis === 'masuk' ? 'Rp ' . number_format($row->nominal, 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-4 py-3.5 text-right font-black text-red-700 whitespace-nowrap">
                                    {{ $row->jenis === 'keluar' ? 'Rp ' . number_format($row->nominal, 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    @if($row->bukti_path)
                                        <a href="{{ asset('storage/' . $row->bukti_path) }}" target="_blank"
                                           class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 hover:text-emerald-900 bg-emerald-50 px-2 py-1 rounded-lg">
                                            <i data-lucide="image" class="w-3.5 h-3.5"></i> Lihat
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-[11px]">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <form action="{{ route('peserta.administrasi.buku-keuangan.destroy', $row->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus catatan transaksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 p-1 rounded-lg hover:bg-red-50 transition">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    Belum ada catatan keuangan kas regu. Klik tombol "Tambah Transaksi" di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($finances->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $finances->links() }}
                </div>
            @endif
        </div>

    </div>
</div>

<!-- MODAL TAMBAH TRANSAKSI -->
<div id="addModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-gray-100">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
            <h3 class="text-lg font-black text-gray-900">Catat Transaksi Kas Regu</h3>
            <button type="button" onclick="closeAddModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('peserta.administrasi.buku-keuangan.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Jenis Kas</label>
                    <select name="jenis" required class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500">
                        <option value="masuk">Pemasukan (Debit)</option>
                        <option value="keluar">Pengeluaran (Kredit)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Uraian Transaksi</label>
                <input type="text" name="uraian" placeholder="Contoh: Iuran mingguan ke-1, Beli tali pramuka..." required
                       class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nominal (Rp)</label>
                    <input type="number" name="nominal" placeholder="0" min="100" required
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Kategori</label>
                    <input type="text" name="kategori" placeholder="Iuran, Konsumsi, dll"
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Unggah Struk / Bukti Pembayaran <span class="text-gray-400 font-normal">(Opsional)</span></label>
                <input type="file" name="bukti" accept="image/*"
                       class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Keterangan Tambahan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                <textarea name="keterangan" rows="2" placeholder="Catatan toko, nomor kuitansi, dll..."
                          class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold transition">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
}
function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
}
</script>
@endsection
