@extends('layouts.peserta')

@section('title', 'Buku Notulen Rapat - Regu ' . $regu->nama_regu)

@section('content')
<div class="py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- HEADER & ACTIONS -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white rounded-3xl p-6 border border-gray-200/90 shadow-sm">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-50 text-purple-800 text-xs font-black uppercase tracking-wider mb-2">
                    <i data-lucide="messages-square" class="w-3.5 h-3.5"></i> Buku IV Administrasi Regu
                </div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Buku Notulen Rapat Regu</h1>
                <p class="text-xs text-gray-500 mt-0.5">Dokumentasi hasil musyawarah, dewan regu, dan keputusan bersama Regu {{ $regu->nama_regu }}.</p>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap">
                <button type="button" onclick="openAddModal()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold transition shadow-sm">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> Catat Notulen Baru
                </button>
                <a href="{{ route('peserta.administrasi.buku-notulen.print') }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold transition">
                    <i data-lucide="printer" class="w-4 h-4"></i> Cetak Buku Notulen
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

        <!-- DAFTAR NOTULENSI -->
        <div class="space-y-4">
            @forelse($notes as $note)
                <div class="bg-white rounded-3xl p-6 border border-gray-200/90 shadow-sm hover:shadow-md transition">
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 pb-4 border-b border-gray-100">
                        <div class="flex items-start gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center shrink-0">
                                <i data-lucide="file-text" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-purple-800 bg-purple-50 px-2.5 py-0.5 rounded-full">
                                    {{ $note->tanggal->translatedFormat('l, d F Y') }}
                                    @if($note->waktu) • Pukul {{ $note->waktu }} WITA @endif
                                </span>
                                <h3 class="text-lg font-black text-gray-900 mt-1">{{ $note->agenda }}</h3>
                                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 mt-1 font-medium">
                                    @if($note->tempat)
                                        <span class="flex items-center gap-1"><i data-lucide="map-pin" class="w-3.5 h-3.5 text-gray-400"></i> {{ $note->tempat }}</span>
                                    @endif
                                    <span class="flex items-center gap-1"><i data-lucide="user-check" class="w-3.5 h-3.5 text-gray-400"></i> Pemimpin: {{ $note->pemimpin_rapat ?: 'Pinru' }}</span>
                                    <span class="flex items-center gap-1"><i data-lucide="pen-tool" class="w-3.5 h-3.5 text-gray-400"></i> Notulis: {{ $note->notulis ?: 'Wapinru' }}</span>
                                    <span class="flex items-center gap-1"><i data-lucide="users" class="w-3.5 h-3.5 text-gray-400"></i> Hadir: {{ $note->jumlah_hadir }} orang</span>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('peserta.administrasi.buku-notulen.destroy', $note->id) }}" method="POST"
                              onsubmit="return confirm('Hapus notulen rapat ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-xl transition text-xs font-bold flex items-center gap-1">
                                <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus
                            </button>
                        </form>
                    </div>

                    <div class="mt-4 space-y-3 text-xs text-gray-700">
                        @if($note->daftar_hadir_teks)
                            <div>
                                <h4 class="font-bold text-gray-900 uppercase tracking-wider text-[11px] mb-1">Daftar Anggota Hadir:</h4>
                                <p class="bg-gray-50 p-2.5 rounded-xl border border-gray-100 text-gray-600">{{ $note->daftar_hadir_teks }}</p>
                            </div>
                        @endif

                        @if($note->jalannya_rapat)
                            <div>
                                <h4 class="font-bold text-gray-900 uppercase tracking-wider text-[11px] mb-1">Pembahasan / Jalannya Musyawarah:</h4>
                                <p class="leading-relaxed bg-gray-50 p-3 rounded-2xl whitespace-pre-line border border-gray-100">{{ $note->jalannya_rapat }}</p>
                            </div>
                        @endif

                        <div>
                            <h4 class="font-bold text-purple-950 uppercase tracking-wider text-[11px] mb-1">Hasil Keputusan Rapat:</h4>
                            <p class="leading-relaxed bg-purple-50/60 p-3 rounded-2xl whitespace-pre-line border border-purple-200/60 text-purple-950 font-medium">{{ $note->keputusan }}</p>
                        </div>

                        @if($note->catatan_tambahan)
                            <div class="text-gray-500 italic">
                                <strong>Catatan:</strong> {{ $note->catatan_tambahan }}
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl p-12 text-center text-gray-500 border border-gray-200 shadow-sm">
                    Belum ada notulen rapat regu. Klik tombol "Catat Notulen Baru" untuk menyimpan hasil musyawarah regu.
                </div>
            @endforelse

            @if($notes->hasPages())
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    {{ $notes->links() }}
                </div>
            @endif
        </div>

    </div>
</div>

<!-- MODAL TAMBAH NOTULEN -->
<div id="addModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl border border-gray-100 my-8">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
            <h3 class="text-lg font-black text-gray-900">Catat Notulen Rapat Regu</h3>
            <button type="button" onclick="closeAddModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('peserta.administrasi.buku-notulen.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Rapat</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Waktu</label>
                    <input type="text" name="waktu" placeholder="14:00 - 15:30"
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Tempat</label>
                    <input type="text" name="tempat" placeholder="Sanggar Pramuka, Tenda Regu..."
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Agenda / Topik Rapat</label>
                <input type="text" name="agenda" placeholder="Contoh: Pembagian Tugas Lomba, Persiapan Menu Masak Rimba..." required
                       class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 font-bold">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Pemimpin Rapat</label>
                    <input type="text" name="pemimpin_rapat" value="{{ $regu->anggotaRegu()->where('jabatan', 'pinru')->value('nama') ?? 'Pinru' }}"
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Notulis</label>
                    <input type="text" name="notulis" value="{{ $regu->anggotaRegu()->where('jabatan', 'wapinru')->value('nama') ?? 'Wapinru' }}"
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Jumlah Hadir (Orang)</label>
                    <input type="number" name="jumlah_hadir" value="{{ $regu->anggotaRegu()->count() }}" min="1"
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 font-mono">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Daftar Anggota yang Hadir <span class="text-gray-400 font-normal">(Opsional)</span></label>
                <input type="text" name="daftar_hadir_teks" placeholder="Nama-nama anggota yang hadir..."
                       value="{{ $regu->anggotaRegu()->pluck('nama')->implode(', ') }}"
                       class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Jalannya Musyawarah & Usulan-Usulan</label>
                <textarea name="jalannya_rapat" rows="3" placeholder="Uraikan jalannya musyawarah, masukan dari anggota regu..."
                          class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-purple-950 mb-1">Hasil Keputusan Rapat (Wajib)</label>
                <textarea name="keputusan" rows="3" required placeholder="Tuliskan butir-butir keputusan final yang disepakati oleh seluruh anggota regu..."
                          class="w-full px-3 py-2 text-xs rounded-xl border border-purple-300 focus:ring-2 focus:ring-purple-500 bg-purple-50/30 font-medium"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Catatan Tambahan / Evaluasi <span class="text-gray-400 font-normal">(Opsional)</span></label>
                <input type="text" name="catatan_tambahan" placeholder="Catatan batas waktu tugas, rapat selanjutnya..."
                       class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500">
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold transition">
                    Simpan Notulen
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
