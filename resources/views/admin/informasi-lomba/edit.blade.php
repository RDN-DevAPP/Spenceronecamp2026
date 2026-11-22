@extends('layouts.main')

@section('title', 'Edit Info: ' . $mataLomba->nama . ' - Admin Spencerone Camp Season II')

@section('content')
    <div class="py-10 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @php
                $isCerdasCermat = ($mataLomba->slug === 'cerdas-cermat' || strtoupper($mataLomba->kode) === 'CRC001');
            @endphp

            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1.5 text-xs text-gray-500">
                        <a href="{{ route('admin.informasi-lomba.index') }}" class="hover:text-brand-forest transition font-medium">Kelola Info Lomba</a>
                        <span>/</span>
                        <span class="text-brand-forest font-bold">Edit {{ $mataLomba->nama }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight">
                        Edit: {{ $mataLomba->nama }}
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Perbarui informasi, kode otorisasi juri, dan juknis lomba</p>
                </div>
                <div class="flex items-center gap-2">
                    @if($mataLomba->is_scored)
                        <a href="{{ route('admin.kriteria.show', $mataLomba->id) }}"
                            class="px-3.5 py-2 rounded-xl font-bold text-xs bg-brand-forest hover:bg-emerald-900 text-white flex items-center gap-1.5 shadow-2xs transition">
                            <i data-lucide="list-checks" class="w-4 h-4 text-amber-400"></i>
                            Atur Kriteria
                        </a>
                    @endif
                    <a href="{{ route('admin.informasi-lomba.index') }}"
                        class="px-3.5 py-2 rounded-xl border border-gray-200 font-semibold text-xs text-gray-700 hover:bg-gray-50 flex items-center gap-1.5 transition">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Kembali
                    </a>
                </div>
            </div>

            @if($isCerdasCermat)
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 text-blue-900 rounded-2xl flex items-center gap-3 text-xs sm:text-sm">
                    <i data-lucide="shield-check" class="w-5 h-5 text-blue-600 shrink-0"></i>
                    <div>
                        <span class="font-bold">Modul Inti Quiz (Terkunci):</span>
                        Mata lomba Cerdas Cermat terhubung langsung dengan engine kuis real-time, bank soal, dan papan penilaian. Kode modul dilindungi untuk kestabilan sinkronisasi.
                    </div>
                </div>
            @endif

            @if (isset($errors) && $errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-xs sm:text-sm">
                    <div class="font-bold mb-1">Terdapat kesalahan pengisian:</div>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-200/80 shadow-sm">
                <form action="{{ route('admin.informasi-lomba.update', $mataLomba->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Nama Lomba -->
                        <div>
                            <label for="nama" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                Nama Mata Lomba <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $mataLomba->nama) }}" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 text-sm font-medium transition">
                        </div>

                        <!-- Kode Lomba -->
                        <div>
                            <label for="kode" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                Kode Lomba (2 - 10 Karakter Otorisasi Juri)
                            </label>
                            <input type="text" name="kode" id="kode" value="{{ old('kode', $mataLomba->kode) }}" maxlength="10" minlength="2"
                                {{ $isCerdasCermat ? 'readonly' : '' }}
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 text-sm font-mono font-bold uppercase tracking-widest transition {{ $isCerdasCermat ? 'bg-gray-100 cursor-not-allowed text-gray-400' : '' }}"
                                oninput="this.value=this.value.toUpperCase().replace(/[^A-Z0-9]/g,'')">
                            @if($isCerdasCermat)
                                <p class="text-[11px] text-blue-600 mt-1 font-medium">Kode Cerdas Cermat dikunci untuk kestabilan arena kuis.</p>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <!-- Kategori -->
                        <div>
                            <label for="kategori" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                Kategori
                            </label>
                            <select name="kategori" id="kategori" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-medium transition">
                                <option value="lomba_utama" {{ old('kategori', $mataLomba->kategori) == 'lomba_utama' ? 'selected' : '' }}>Lomba Utama (Scored)</option>
                                <option value="giat_alam" {{ old('kategori', $mataLomba->kategori) == 'giat_alam' ? 'selected' : '' }}>Giat Alam Bebas</option>
                                <option value="giat_seni" {{ old('kategori', $mataLomba->kategori) == 'giat_seni' ? 'selected' : '' }}>Giat Seni & Kreasi</option>
                                <option value="lainnya" {{ old('kategori', $mataLomba->kategori) == 'lainnya' ? 'selected' : '' }}>Kegiatan Lainnya</option>
                            </select>
                        </div>

                        <!-- Status Penilaian -->
                        <div>
                            <label for="is_scored" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                Status Penilaian
                            </label>
                            <select name="is_scored" id="is_scored" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-medium transition">
                                <option value="1" {{ old('is_scored', $mataLomba->is_scored) ? 'selected' : '' }}>Dinilai Juri (Masuk Rekap Medali)</option>
                                <option value="0" {{ !old('is_scored', $mataLomba->is_scored) ? 'selected' : '' }}>Non-Skor (Partisipasi Saja)</option>
                            </select>
                        </div>

                        <!-- Urutan & Nilai Maksimal -->
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label for="urutan" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                    Urutan
                                </label>
                                <input type="number" name="urutan" id="urutan" value="{{ old('urutan', $mataLomba->urutan) }}" required min="1"
                                    class="w-full px-3 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-bold text-center transition">
                            </div>
                            <div>
                                <label for="nilai_maksimal" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                    Maks Poin
                                </label>
                                <input type="number" name="nilai_maksimal" id="nilai_maksimal" value="{{ old('nilai_maksimal', (int)$mataLomba->nilai_maksimal) }}" required min="1"
                                    class="w-full px-3 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-bold text-center transition">
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi Singkat -->
                    <div>
                        <label for="deskripsi" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Deskripsi Singkat Lomba
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="2"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-medium transition"
                            placeholder="Deskripsi ringkas yang tampil pada kartu info lomba...">{{ old('deskripsi', $mataLomba->deskripsi) }}</textarea>
                    </div>

                    <!-- Petunjuk Teknis -->
                    <div>
                        <label for="petunjuk_teknis" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Petunjuk Teknis (Juknis)
                        </label>
                        <textarea name="petunjuk_teknis" id="petunjuk_teknis" rows="4"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-medium transition"
                            placeholder="Tuliskan petunjuk teknis pelaksanaan lomba...">{{ old('petunjuk_teknis', $mataLomba->petunjuk_teknis) }}</textarea>
                    </div>

                    <!-- Ketentuan Pelaksanaan -->
                    <div>
                        <label for="ketentuan_pelaksanaan" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Ketentuan & Peraturan Pelaksanaan
                        </label>
                        <textarea name="ketentuan_pelaksanaan" id="ketentuan_pelaksanaan" rows="3"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-medium transition"
                            placeholder="Tuliskan syarat, larangan, dan ketentuan teknis lomba...">{{ old('ketentuan_pelaksanaan', $mataLomba->ketentuan_pelaksanaan) }}</textarea>
                    </div>

                    <!-- Pengaturan Upload Foto & Portofolio Karya Regu -->
                    <div class="p-5 rounded-2xl bg-amber-50/60 border border-amber-200/80 space-y-4">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-800 shrink-0">
                                    <i data-lucide="camera" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-black text-gray-900">Pengaturan Upload Foto / Portofolio Karya</h3>
                                    <p class="text-xs text-gray-500">Tentukan apakah regu peserta wajib mengunggah foto dokumentasi/hasil karya untuk lomba ini</p>
                                </div>
                            </div>
                            <input type="hidden" name="wajib_upload_foto_submitted" value="1">
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="checkbox" name="wajib_upload_foto" value="1" id="toggleWajibFoto"
                                    {{ old('wajib_upload_foto', $mataLomba->wajib_upload_foto) ? 'checked' : '' }}
                                    class="sr-only peer" onchange="togglePhotoSettings(this)">
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-forest"></div>
                            </label>
                        </div>

                        <div id="photoSettingsContainer" class="{{ old('wajib_upload_foto', $mataLomba->wajib_upload_foto) ? '' : 'hidden' }} pt-3 border-t border-amber-200/60 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="sm:col-span-1">
                                    <label for="max_foto" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                        Maksimal Jumlah Foto
                                    </label>
                                    <input type="number" name="max_foto" id="max_foto" value="{{ old('max_foto', $mataLomba->max_foto ?? 1) }}" min="1" max="10"
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-white focus:border-emerald-600 text-sm font-bold text-center transition">
                                    <p class="text-[11px] text-gray-500 mt-1">Rentang 1 s/d 10 foto per regu.</p>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="instruksi_upload_foto" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                        Instruksi / Ketentuan Foto yang Harus Diunggah
                                    </label>
                                    <textarea name="instruksi_upload_foto" id="instruksi_upload_foto" rows="2"
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-white focus:border-emerald-600 text-sm font-medium transition"
                                        placeholder="Contoh: Foto tampak depan, sudut sambungan detail, dan foto proses pembuatan...">{{ old('instruksi_upload_foto', $mataLomba->instruksi_upload_foto) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row gap-2.5">
                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl font-bold text-xs sm:text-sm bg-brand-forest hover:bg-emerald-900 text-white shadow-sm flex items-center justify-center gap-2 cursor-pointer transition">
                            <i data-lucide="save" class="w-4 h-4 text-amber-400"></i>
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.informasi-lomba.index') }}"
                            class="px-5 py-2.5 rounded-xl font-semibold text-xs sm:text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 text-center transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script>
    function togglePhotoSettings(checkbox) {
        const container = document.getElementById('photoSettingsContainer');
        if (checkbox.checked) {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }
</script>
@endpush