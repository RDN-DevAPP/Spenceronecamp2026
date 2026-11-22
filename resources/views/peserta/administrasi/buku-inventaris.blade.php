@extends('layouts.peserta')

@section('title', 'Buku Inventaris Perlengkapan - Regu ' . $regu->nama_regu)

@section('content')
<div class="py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- HEADER & ACTIONS -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white rounded-3xl p-6 border border-gray-200/90 shadow-sm">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-50 text-orange-800 text-xs font-black uppercase tracking-wider mb-2">
                    <i data-lucide="boxes" class="w-3.5 h-3.5"></i> Buku VI Administrasi Regu
                </div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Buku Inventaris Perlengkapan Regu</h1>
                <p class="text-xs text-gray-500 mt-0.5">Daftar aset barang, tenda, tongkat, tali, kompas, dan alat perkemahan Regu {{ $regu->nama_regu }}.</p>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap">
                <button type="button" onclick="openAddModal()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-orange-700 hover:bg-orange-800 text-white text-xs font-bold transition shadow-sm">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Barang
                </button>
                <a href="{{ route('peserta.administrasi.buku-inventaris.print') }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold transition">
                    <i data-lucide="printer" class="w-4 h-4"></i> Cetak Inventaris
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

        <!-- INVENTORY STATS -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm">
                <div class="text-xs font-bold text-gray-600 uppercase tracking-wider flex items-center gap-1.5">
                    <i data-lucide="boxes" class="w-4 h-4 text-gray-500"></i> Total Barang / Unit
                </div>
                <div class="text-2xl font-black text-gray-900 mt-2">{{ $totalBarang }} Unit</div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-emerald-100 shadow-sm">
                <div class="text-xs font-bold text-emerald-800 uppercase tracking-wider flex items-center gap-1.5">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i> Kondisi Baik
                </div>
                <div class="text-2xl font-black text-emerald-700 mt-2">{{ $kondisiBaik }} Unit</div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-amber-100 shadow-sm">
                <div class="text-xs font-bold text-amber-800 uppercase tracking-wider flex items-center gap-1.5">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-600"></i> Kondisi Rusak / Perbaikan
                </div>
                <div class="text-2xl font-black text-amber-700 mt-2">{{ $kondisiRusak }} Unit</div>
            </div>
        </div>

        <!-- TABEL INVENTARIS -->
        <div class="bg-white rounded-3xl border border-gray-200/90 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                    <thead class="bg-brand-forest text-white">
                        <tr>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider text-center w-12">No</th>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider">Kode</th>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider">Nama Barang / Perlengkapan</th>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider text-center">Jumlah</th>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider text-center">Kondisi</th>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider">Asal Perolehan</th>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider">Penyimpanan / Keterangan</th>
                            <th class="px-4 py-3.5 font-black uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium text-gray-700">
                        @forelse($inventories as $idx => $inv)
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="px-4 py-3.5 text-center font-bold text-gray-500">{{ $inventories->firstItem() + $idx }}</td>
                                <td class="px-4 py-3.5 font-mono text-gray-500">{{ $inv->kode_barang ?: '-' }}</td>
                                <td class="px-4 py-3.5 font-bold text-gray-900 text-sm">{{ $inv->nama_barang }}</td>
                                <td class="px-4 py-3.5 text-center font-black text-brand-forest">
                                    {{ $inv->jumlah }} {{ $inv->satuan }}
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    @if($inv->kondisi === 'baik')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-900 font-bold text-[10px]">
                                            Baik
                                        </span>
                                    @elseif($inv->kondisi === 'rusak_ringan')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 font-bold text-[10px]">
                                            Rusak Ringan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-red-100 text-red-900 font-bold text-[10px]">
                                            Rusak Berat
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-gray-600">
                                    {{ $inv->asal_barang ?: 'Beli Regu' }}
                                    @if($inv->tanggal_perolehan)
                                        <div class="text-[10px] text-gray-400">Tgl: {{ $inv->tanggal_perolehan->format('d/m/Y') }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-gray-600">
                                    <div>Lokasi: <span class="font-bold">{{ $inv->lokasi_penyimpanan ?: 'Box Regu' }}</span></div>
                                    @if($inv->keterangan)
                                        <div class="text-[11px] text-gray-400">{{ $inv->keterangan }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <form action="{{ route('peserta.administrasi.buku-inventaris.destroy', $inv->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus barang inventaris ini?')">
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
                                    Belum ada inventaris barang perlengkapan. Klik tombol "Tambah Barang" di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($inventories->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $inventories->links() }}
                </div>
            @endif
        </div>

    </div>
</div>

<!-- MODAL TAMBAH INVENTARIS -->
<div id="addModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-gray-100">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
            <h3 class="text-lg font-black text-gray-900">Tambah Barang Inventaris Regu</h3>
            <button type="button" onclick="closeAddModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('peserta.administrasi.buku-inventaris.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Kode Barang <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <input type="text" name="kode_barang" placeholder="REG-01, TND-01..."
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-orange-500 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Kondisi</label>
                    <select name="kondisi" required class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-orange-500">
                        <option value="baik">Baik</option>
                        <option value="rusak_ringan">Rusak Ringan</option>
                        <option value="rusak_berat">Rusak Berat</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Nama Barang / Perlengkapan</label>
                <input type="text" name="nama_barang" placeholder="Tenda Dome, Tongkat Pramuka, Kompas Bidik..." required
                       class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-orange-500 font-bold">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Jumlah</label>
                    <input type="number" name="jumlah" value="1" min="1" required
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-orange-500 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Satuan</label>
                    <input type="text" name="satuan" value="Buah" placeholder="Buah, Set, Pcs, Gulung..." required
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-orange-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Asal Usul Perolehan</label>
                    <input type="text" name="asal_barang" placeholder="Beli Kas Regu, Hibah, Pinjam..."
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Perolehan</label>
                    <input type="date" name="tanggal_perolehan" value="{{ date('Y-m-d') }}"
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-orange-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Tempat Penyimpanan</label>
                <input type="text" name="lokasi_penyimpanan" placeholder="Box Regu Kavling, Sanggar Pramuka..."
                       class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-orange-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Keterangan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                <textarea name="keterangan" rows="2" placeholder="Spesifikasi warna, merk, catatan..."
                          class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-orange-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-orange-700 hover:bg-orange-800 text-white text-xs font-bold transition">
                    Simpan Barang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() { document.getElementById('addModal').classList.remove('hidden'); }
function closeAddModal() { document.getElementById('addModal').classList.add('hidden'); }
</script>
@endsection
