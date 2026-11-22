@extends('layouts.peserta')

@section('title', 'Buku Presensi Regu - ' . $regu->nama_regu)

@section('content')
<div class="py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- HEADER & ACTIONS -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white rounded-3xl p-6 border border-gray-200/90 shadow-sm">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-50 text-teal-800 text-xs font-black uppercase tracking-wider mb-2">
                    <i data-lucide="user-check" class="w-3.5 h-3.5"></i> Buku V Administrasi Regu
                </div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Buku Presensi / Daftar Hadir Regu</h1>
                <p class="text-xs text-gray-500 mt-0.5">Catatan presensi kehadiran anggota Regu {{ $regu->nama_regu }} pada latihan dan kegiatan perkemahan.</p>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap">
                <button type="button" onclick="openAddModal()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold transition shadow-sm">
                    <i data-lucide="user-plus" class="w-4 h-4"></i> Catat Presensi Pertemuan
                </button>
                <a href="{{ route('peserta.administrasi.buku-presensi.print') }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold transition">
                    <i data-lucide="printer" class="w-4 h-4"></i> Cetak Presensi
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

        <!-- DAFTAR PRESENSI PERTEMUAN -->
        <div class="space-y-4">
            @forelse($attendances as $att)
                <div class="bg-white rounded-3xl p-6 border border-gray-200/90 shadow-sm overflow-hidden">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 mb-4 border-b border-gray-100 gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold">
                                <i data-lucide="calendar" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-teal-800 bg-teal-50 px-2 py-0.5 rounded-md">
                                    {{ $att->tanggal->translatedFormat('l, d F Y') }}
                                </span>
                                <h3 class="font-black text-gray-900 text-base mt-1">{{ $att->nama_kegiatan }}</h3>
                                @if($att->keterangan)
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $att->keterangan }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            @php
                                $hadirCount = $att->details->where('status', 'hadir')->count();
                                $izinCount = $att->details->where('status', 'izin')->count();
                                $sakitCount = $att->details->where('status', 'sakit')->count();
                                $alpaCount = $att->details->where('status', 'alpa')->count();
                            @endphp
                            <div class="flex items-center gap-1.5 text-[11px] font-bold">
                                <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-800">H: {{ $hadirCount }}</span>
                                <span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-800">I: {{ $izinCount }}</span>
                                <span class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-800">S: {{ $sakitCount }}</span>
                                <span class="px-2 py-0.5 rounded-md bg-red-50 text-red-800">A: {{ $alpaCount }}</span>
                            </div>

                            <form action="{{ route('peserta.administrasi.buku-presensi.destroy', $att->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus presensi tanggal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-xl transition">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- TABEL DETAIL STATUS ANGGOTA -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100 text-left text-xs">
                            <thead class="bg-gray-50 text-gray-600">
                                <tr>
                                    <th class="px-3 py-2 font-bold w-10 text-center">No</th>
                                    <th class="px-3 py-2 font-bold">Nama Anggota</th>
                                    <th class="px-3 py-2 font-bold">Jabatan</th>
                                    <th class="px-3 py-2 font-bold text-center">Status Kehadiran</th>
                                    <th class="px-3 py-2 font-bold">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($att->details as $idx => $det)
                                    <tr>
                                        <td class="px-3 py-2 text-center text-gray-400">{{ $idx + 1 }}</td>
                                        <td class="px-3 py-2 font-bold text-gray-900">{{ $det->anggotaRegu?->nama ?? '-' }}</td>
                                        <td class="px-3 py-2 text-gray-500 uppercase text-[10px]">{{ $det->anggotaRegu?->jabatan ?? '-' }}</td>
                                        <td class="px-3 py-2 text-center">
                                            @if($det->status === 'hadir')
                                                <span class="inline-block px-2.5 py-0.5 rounded-md bg-emerald-100 text-emerald-900 font-bold text-[10px]">HADIR</span>
                                            @elseif($det->status === 'izin')
                                                <span class="inline-block px-2.5 py-0.5 rounded-md bg-blue-100 text-blue-900 font-bold text-[10px]">IZIN</span>
                                            @elseif($det->status === 'sakit')
                                                <span class="inline-block px-2.5 py-0.5 rounded-md bg-amber-100 text-amber-900 font-bold text-[10px]">SAKIT</span>
                                            @else
                                                <span class="inline-block px-2.5 py-0.5 rounded-md bg-red-100 text-red-900 font-bold text-[10px]">ALPA</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 text-gray-500">{{ $det->keterangan ?: '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl p-12 text-center text-gray-500 border border-gray-200 shadow-sm">
                    Belum ada presensi pertemuan yang dicatat. Klik tombol "Catat Presensi Pertemuan" di atas.
                </div>
            @endforelse

            @if($attendances->hasPages())
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    {{ $attendances->links() }}
                </div>
            @endif
        </div>

    </div>
</div>

<!-- MODAL TAMBAH PRESENSI -->
<div id="addModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl border border-gray-100 my-8">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
            <h3 class="text-lg font-black text-gray-900">Catat Presensi Kehadiran Regu</h3>
            <button type="button" onclick="closeAddModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('peserta.administrasi.buku-presensi.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Kegiatan</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Pertemuan / Kegiatan</label>
                    <input type="text" name="nama_kegiatan" placeholder="Latihan Regu, Gladi Lomba..." required
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Keterangan / Lokasi <span class="text-gray-400 font-normal">(Opsional)</span></label>
                <input type="text" name="keterangan" placeholder="Lapangan sekolah, sanggar regu..."
                       class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-800 mb-2">Presensi Kehadiran Setiap Anggota:</label>
                <div class="space-y-2.5 max-h-64 overflow-y-auto pr-1">
                    @foreach($anggotaList as $a)
                        <div class="flex items-center justify-between p-2.5 bg-gray-50 rounded-xl border border-gray-200 text-xs">
                            <div class="font-bold text-gray-900">
                                {{ $a->nama }}
                                <span class="text-[10px] text-gray-500 block uppercase">({{ $a->jabatan }})</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <label class="px-2 py-1 rounded-lg border border-gray-300 bg-white text-gray-700 text-[11px] font-bold cursor-pointer hover:bg-gray-100 has-[:checked]:bg-emerald-600 has-[:checked]:text-white has-[:checked]:border-emerald-600">
                                    <input type="radio" name="status[{{ $a->id }}]" value="hadir" checked class="hidden"> H
                                </label>
                                <label class="px-2 py-1 rounded-lg border border-gray-300 bg-white text-gray-700 text-[11px] font-bold cursor-pointer hover:bg-gray-100 has-[:checked]:bg-blue-600 has-[:checked]:text-white has-[:checked]:border-blue-600">
                                    <input type="radio" name="status[{{ $a->id }}]" value="izin" class="hidden"> I
                                </label>
                                <label class="px-2 py-1 rounded-lg border border-gray-300 bg-white text-gray-700 text-[11px] font-bold cursor-pointer hover:bg-gray-100 has-[:checked]:bg-amber-600 has-[:checked]:text-white has-[:checked]:border-amber-600">
                                    <input type="radio" name="status[{{ $a->id }}]" value="sakit" class="hidden"> S
                                </label>
                                <label class="px-2 py-1 rounded-lg border border-gray-300 bg-white text-gray-700 text-[11px] font-bold cursor-pointer hover:bg-gray-100 has-[:checked]:bg-red-600 has-[:checked]:text-white has-[:checked]:border-red-600">
                                    <input type="radio" name="status[{{ $a->id }}]" value="alpa" class="hidden"> A
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold transition">
                    Simpan Presensi
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
