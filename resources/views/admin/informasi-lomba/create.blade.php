@extends('layouts.main')

@section('title', 'Tambah Mata Lomba Baru - Admin Spencerone Camp Season II')

@section('content')
<div class="py-10 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1.5 text-xs text-gray-500">
                    <a href="{{ route('admin.informasi-lomba.index') }}" class="hover:text-brand-forest transition font-medium">Kelola Info Lomba</a>
                    <span>/</span>
                    <span class="text-brand-forest font-bold">Tambah Baru</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight">
                    Tambah Mata Lomba Baru
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Konfigurasi mata lomba dan petunjuk teknis Spencerone Camp Season II</p>
            </div>
            <a href="{{ route('admin.informasi-lomba.index') }}"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 self-start sm:self-auto transition shadow-2xs">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
            </a>
        </div>

        @if (isset($errors) && $errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-xs sm:text-sm">
                <div class="font-bold mb-1 flex items-center gap-1.5">
                    <i data-lucide="alert-triangle" class="w-4 h-4"></i> Periksa kembali isian formulir:
                </div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Tambah Lomba -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-200/80 shadow-sm">
            <form action="{{ route('admin.informasi-lomba.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Nama Lomba -->
                    <div>
                        <label for="nama" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Nama Mata Lomba <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 text-sm font-medium transition"
                            placeholder="Contoh: Pionering Kreatif Menara Pandang">
                    </div>

                    <!-- Kode Lomba -->
                    <div>
                        <label for="kode" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Kode Lomba (2 - 10 Karakter Otorisasi Juri) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode" id="kode" value="{{ old('kode') }}" required maxlength="10" minlength="2"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 text-sm font-mono font-bold uppercase tracking-widest transition"
                            placeholder="Contoh: PIO001 / PBB"
                            oninput="this.value=this.value.toUpperCase().replace(/[^A-Z0-9]/g,'')">
                        <p class="text-[11px] text-gray-400 mt-1">Kode 2-10 karakter alfanumerik unik untuk otorisasi penilaian dewan juri.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <!-- Kategori -->
                    <div>
                        <label for="kategori" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select name="kategori" id="kategori" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-medium transition">
                            <option value="lomba_utama" {{ old('kategori') == 'lomba_utama' ? 'selected' : '' }}>Lomba Utama (Scored)</option>
                            <option value="giat_alam" {{ old('kategori') == 'giat_alam' ? 'selected' : '' }}>Giat Alam Bebas</option>
                            <option value="giat_seni" {{ old('kategori') == 'giat_seni' ? 'selected' : '' }}>Giat Seni & Kreasi</option>
                            <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>Kegiatan Lainnya</option>
                        </select>
                    </div>

                    <!-- Status Penilaian -->
                    <div>
                        <label for="is_scored" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Jenis Penilaian <span class="text-red-500">*</span>
                        </label>
                        <select name="is_scored" id="is_scored" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-medium transition">
                            <option value="1" {{ old('is_scored', '1') == '1' ? 'selected' : '' }}>Dinilai Juri (Masuk Rekap Medali)</option>
                            <option value="0" {{ old('is_scored') == '0' ? 'selected' : '' }}>Non-Skor (Partisipasi Saja)</option>
                        </select>
                    </div>

                    <!-- Urutan & Nilai Maksimal -->
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="urutan" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                Urutan
                            </label>
                            <input type="number" name="urutan" id="urutan" value="{{ old('urutan', $nextUrutan ?? 1) }}" required min="1"
                                class="w-full px-3 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-bold text-center transition">
                        </div>
                        <div>
                            <label for="nilai_maksimal" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                Maks Poin
                            </label>
                            <input type="number" name="nilai_maksimal" id="nilai_maksimal" value="{{ old('nilai_maksimal', 100) }}" required min="1"
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
                        placeholder="Deskripsi ringkas yang tampil pada kartu informasi lomba...">{{ old('deskripsi') }}</textarea>
                </div>

                <!-- Petunjuk Teknis -->
                <div>
                    <label for="petunjuk_teknis" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Petunjuk Teknis (Juknis)
                    </label>
                    <textarea name="petunjuk_teknis" id="petunjuk_teknis" rows="4"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-medium transition"
                        placeholder="Tuliskan petunjuk teknis pelaksanaan lomba secara komprehensif...">{{ old('petunjuk_teknis') }}</textarea>
                </div>

                <!-- Ketentuan Pelaksanaan -->
                <div>
                    <label for="ketentuan_pelaksanaan" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Ketentuan & Peraturan Pelaksanaan
                    </label>
                    <textarea name="ketentuan_pelaksanaan" id="ketentuan_pelaksanaan" rows="3"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-medium transition"
                        placeholder="Tuliskan syarat, larangan, dan ketentuan teknis lomba...">{{ old('ketentuan_pelaksanaan') }}</textarea>
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
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="checkbox" name="wajib_upload_foto" value="1" id="toggleWajibFoto"
                                {{ old('wajib_upload_foto') ? 'checked' : '' }}
                                class="sr-only peer" onchange="togglePhotoSettings(this)">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-forest"></div>
                        </label>
                    </div>

                    <div id="photoSettingsContainer" class="{{ old('wajib_upload_foto') ? '' : 'hidden' }} pt-3 border-t border-amber-200/60 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="sm:col-span-1">
                                <label for="max_foto" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                    Maksimal Jumlah Foto
                                </label>
                                <input type="number" name="max_foto" id="max_foto" value="{{ old('max_foto', 1) }}" min="1" max="10"
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-white focus:border-emerald-600 text-sm font-bold text-center transition">
                                <p class="text-[11px] text-gray-500 mt-1">Rentang 1 s/d 10 foto per regu.</p>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="instruksi_upload_foto" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                    Instruksi / Ketentuan Foto yang Harus Diunggah
                                </label>
                                <textarea name="instruksi_upload_foto" id="instruksi_upload_foto" rows="2"
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-white focus:border-emerald-600 text-sm font-medium transition"
                                    placeholder="Contoh: Foto tampak depan, sudut sambungan detail, dan foto proses pembuatan...">{{ old('instruksi_upload_foto') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row gap-2.5">
                    <button type="submit"
                        class="px-6 py-2.5 rounded-xl font-bold text-xs sm:text-sm bg-brand-forest hover:bg-emerald-900 text-white shadow-sm flex items-center justify-center gap-2 cursor-pointer transition">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        Simpan Mata Lomba
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