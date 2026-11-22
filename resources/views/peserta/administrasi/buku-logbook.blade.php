@extends('layouts.peserta')

@section('title', 'Buku Catatan Kegiatan (Logbook) - Regu ' . $regu->nama_regu)

@section('content')
<div class="py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- HEADER & ACTIONS -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white rounded-3xl p-6 border border-gray-200/90 shadow-sm">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-800 text-xs font-black uppercase tracking-wider mb-2">
                    <i data-lucide="notebook-pen" class="w-3.5 h-3.5"></i> Buku III Administrasi Regu
                </div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Buku Catatan Kegiatan (Logbook)</h1>
                <p class="text-xs text-gray-500 mt-0.5">Jurnal harian latihan, perkemahan, dan kegiatan kepramukaan Regu {{ $regu->nama_regu }}.</p>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap">
                <button type="button" onclick="openAddModal()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-700 hover:bg-blue-800 text-white text-xs font-bold transition shadow-sm">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> Catat Kegiatan Baru
                </button>
                <a href="{{ route('peserta.administrasi.buku-logbook.print') }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold transition">
                    <i data-lucide="printer" class="w-4 h-4"></i> Cetak Logbook
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

        <!-- DAFTAR CATATAN KEGIATAN -->
        <div class="space-y-4">
            @forelse($logbooks as $log)
                <div class="bg-white rounded-3xl p-6 border border-gray-200/90 shadow-sm hover:shadow-md transition">
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 pb-4 border-b border-gray-100">
                        <div class="flex items-start gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0">
                                <i data-lucide="calendar" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-blue-800 bg-blue-50 px-2.5 py-0.5 rounded-full">
                                    {{ $log->tanggal->translatedFormat('l, d F Y') }}
                                    @if($log->waktu_mulai)
                                        • Pukul {{ $log->waktu_mulai }}{{ $log->waktu_selesai ? ' - ' . $log->waktu_selesai : '' }} WITA
                                    @endif
                                </span>
                                <h3 class="text-lg font-black text-gray-900 mt-1">{{ $log->nama_kegiatan }}</h3>
                                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 mt-1 font-medium">
                                    @if($log->tempat)
                                        <span class="flex items-center gap-1"><i data-lucide="map-pin" class="w-3.5 h-3.5 text-gray-400"></i> {{ $log->tempat }}</span>
                                    @endif
                                    @if($log->pemimpin_kegiatan)
                                        <span class="flex items-center gap-1"><i data-lucide="user" class="w-3.5 h-3.5 text-gray-400"></i> Pemimpin/Instruktur: {{ $log->pemimpin_kegiatan }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('peserta.administrasi.buku-logbook.destroy', $log->id) }}" method="POST"
                              onsubmit="return confirm('Hapus catatan kegiatan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-xl transition text-xs font-bold flex items-center gap-1">
                                <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus
                            </button>
                        </form>
                    </div>

                    <div class="mt-4 grid grid-cols-1 lg:grid-cols-12 gap-6 text-xs text-gray-700">
                        <div class="lg:col-span-8 space-y-3">
                            @if($log->ringkasan_materi)
                                <div>
                                    <h4 class="font-bold text-gray-900 uppercase tracking-wider text-[11px] mb-1">Materi / Jalannya Kegiatan:</h4>
                                    <p class="leading-relaxed bg-gray-50 p-3 rounded-2xl whitespace-pre-line border border-gray-100">{{ $log->ringkasan_materi }}</p>
                                </div>
                            @endif

                            @if($log->hasil_kegiatan)
                                <div>
                                    <h4 class="font-bold text-gray-900 uppercase tracking-wider text-[11px] mb-1">Hasil / Pencapaian Regu:</h4>
                                    <p class="leading-relaxed bg-emerald-50/50 p-3 rounded-2xl whitespace-pre-line border border-emerald-100/60 text-emerald-950">{{ $log->hasil_kegiatan }}</p>
                                </div>
                            @endif

                            @if($log->catatan_evaluasi)
                                <div>
                                    <h4 class="font-bold text-gray-900 uppercase tracking-wider text-[11px] mb-1">Catatan Evaluasi / Tindak Lanjut:</h4>
                                    <p class="leading-relaxed bg-amber-50/60 p-3 rounded-2xl whitespace-pre-line border border-amber-200/60 text-amber-950">{{ $log->catatan_evaluasi }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="lg:col-span-4">
                            @if($log->foto_kegiatan_path)
                                <div>
                                    <h4 class="font-bold text-gray-900 uppercase tracking-wider text-[11px] mb-1">Dokumentasi Foto:</h4>
                                    <div class="rounded-2xl overflow-hidden border border-gray-200 aspect-video bg-black/10">
                                        <img src="{{ asset('storage/' . $log->foto_kegiatan_path) }}" alt="Foto Kegiatan" class="w-full h-full object-cover">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl p-12 text-center text-gray-500 border border-gray-200 shadow-sm">
                    Belum ada catatan logbook kegiatan. Klik "Catat Kegiatan Baru" untuk mulai mengisi jurnal harian regu.
                </div>
            @endforelse

            @if($logbooks->hasPages())
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    {{ $logbooks->links() }}
                </div>
            @endif
        </div>

    </div>
</div>

<!-- MODAL TAMBAH LOGBOOK -->
<div id="addModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl border border-gray-100 my-8">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
            <h3 class="text-lg font-black text-gray-900">Catat Jurnal Kegiatan Regu</h3>
            <button type="button" onclick="closeAddModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('peserta.administrasi.buku-logbook.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Kegiatan</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Waktu Mulai</label>
                    <input type="text" name="waktu_mulai" placeholder="08:00"
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Waktu Selesai</label>
                    <input type="text" name="waktu_selesai" placeholder="11:30"
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 font-mono">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Nama Kegiatan</label>
                <input type="text" name="nama_kegiatan" placeholder="Contoh: Latihan Pionering Tiang Bendera, Penjelajahan Kompas..." required
                       class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Tempat / Lokasi</label>
                    <input type="text" name="tempat" placeholder="Halaman SMPN 1 Cerbon, Lapangan Utama..."
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Pemimpin Kegiatan / Instruktur</label>
                    <input type="text" name="pemimpin_kegiatan" placeholder="Kak Pembina / Pinru"
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Materi & Jalannya Kegiatan</label>
                <textarea name="ringkasan_materi" rows="3" placeholder="Jelaskan ringkasan materi dan aktivitas yang dilakukan oleh anggota regu..."
                          class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Hasil & Pencapaian Kegiatan</label>
                <textarea name="hasil_kegiatan" rows="2" placeholder="Hasil yang dicapai, target kecakapan yang tuntas..."
                          class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Foto Dokumentasi Kegiatan</label>
                    <input type="file" name="foto" accept="image/*"
                           class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Catatan Evaluasi / Kendala</label>
                    <input type="text" name="catatan_evaluasi" placeholder="Kendala perlengkapan, kedisiplinan..."
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-blue-700 hover:bg-blue-800 text-white text-xs font-bold transition">
                    Simpan Catatan Logbook
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
