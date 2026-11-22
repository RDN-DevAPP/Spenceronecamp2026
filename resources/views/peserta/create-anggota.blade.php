@extends('layouts.peserta')

@section('title', 'Tambah Anggota Regu - Spencerone Camp Season II')

@section('content')
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb & Back -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('peserta.dashboard') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 text-xs font-bold transition shadow-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Dashboard
            </a>
            <span class="text-xs font-bold text-gray-500">
                Kuota: <strong class="text-emerald-700">{{ $currentCount }} / 8</strong> Anggota
            </span>
        </div>

        <!-- Card Form -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-brand-forest to-emerald-800 p-6 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center text-amber-300">
                        <i data-lucide="user-plus" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black uppercase tracking-tight text-white">Tambah Anggota Regu</h2>
                        <p class="text-xs text-emerald-100">Regu {{ $regu->nama_regu }} ({{ ucfirst($regu->kategori ?? $regu->jenis) }})</p>
                    </div>
                </div>
                <span class="hidden sm:inline-flex px-3 py-1 bg-amber-400/20 text-amber-300 text-xs font-black uppercase rounded-lg border border-amber-300/30">
                    Form Pendaftaran
                </span>
            </div>

            <div class="p-6 sm:p-8">
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-xl shadow-sm">
                        <p class="font-bold text-xs uppercase tracking-wider mb-1">Perhatikan Kesalahan Berikut:</p>
                        <ul class="list-disc list-inside text-xs space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('peserta.anggota.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Nama Lengkap & NTA -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama" class="block text-xs font-bold text-gray-700 uppercase mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                                placeholder="Contoh: Muhammad Rizky"
                                class="w-full text-sm rounded-xl border-gray-200 focus:border-brand-forest focus:ring focus:ring-emerald-200 py-2.5 px-3 border shadow-sm">
                        </div>
                        <div>
                            <label for="nisn" class="block text-xs font-bold text-gray-700 uppercase mb-2">
                                NTA <span class="text-gray-400 font-normal">(Nomor Tanda Anggota - Opsional)</span>
                            </label>
                            <input type="text" name="nisn" id="nisn" value="{{ old('nisn') }}"
                                placeholder="Nomor Tanda Anggota Pramuka"
                                class="w-full text-sm font-mono rounded-xl border-gray-200 focus:border-brand-forest focus:ring focus:ring-emerald-200 py-2.5 px-3 border shadow-sm">
                        </div>
                    </div>

                    <!-- Kelas, TKU & Jabatan -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="kelas" class="block text-xs font-bold text-gray-700 uppercase mb-2">
                                Kelas <span class="text-red-500">*</span>
                            </label>
                            <select name="kelas" id="kelas" required
                                class="w-full text-sm rounded-xl border-gray-200 focus:border-brand-forest focus:ring focus:ring-emerald-200 py-2.5 px-3 border bg-white shadow-sm">
                                <option value="">-- Pilih Kelas --</option>
                                <option value="7" {{ old('kelas') == '7' ? 'selected' : '' }}>Kelas 7</option>
                                <option value="8" {{ old('kelas') == '8' ? 'selected' : '' }}>Kelas 8</option>
                                <option value="9" {{ old('kelas') == '9' ? 'selected' : '' }}>Kelas 9</option>
                            </select>
                        </div>
                        <div>
                            <label for="tingkatan_tku" class="block text-xs font-bold text-gray-700 uppercase mb-2">
                                Tingkatan TKU <span class="text-red-500">*</span>
                            </label>
                            <select name="tingkatan_tku" id="tingkatan_tku" required
                                class="w-full text-sm rounded-xl border-gray-200 focus:border-brand-forest focus:ring focus:ring-emerald-200 py-2.5 px-3 border bg-white shadow-sm">
                                <option value="ramu" {{ old('tingkatan_tku') == 'ramu' ? 'selected' : '' }}>RAMU</option>
                                <option value="rakit" {{ old('tingkatan_tku') == 'rakit' ? 'selected' : '' }}>RAKIT</option>
                                <option value="terap" {{ old('tingkatan_tku') == 'terap' ? 'selected' : '' }}>TERAP</option>
                            </select>
                        </div>
                        <div>
                            <label for="jabatan" class="block text-xs font-bold text-gray-700 uppercase mb-2">
                                Jabatan <span class="text-red-500">*</span>
                            </label>
                            <select name="jabatan" id="jabatan" required
                                class="w-full text-sm rounded-xl border-gray-200 focus:border-brand-forest focus:ring focus:ring-emerald-200 py-2.5 px-3 border bg-white shadow-sm">
                                <option value="anggota" {{ old('jabatan') == 'anggota' ? 'selected' : '' }}>Anggota</option>
                                <option value="pinru" {{ old('jabatan') == 'pinru' ? 'selected' : '' }} {{ $hasPinru ? 'disabled' : '' }}>
                                    Pinru (Pemimpin) {{ $hasPinru ? ' - Sudah Ada' : '' }}
                                </option>
                                <option value="wapinru" {{ old('jabatan') == 'wapinru' ? 'selected' : '' }} {{ $hasWapinru ? 'disabled' : '' }}>
                                    Wapinru (Wakil) {{ $hasWapinru ? ' - Sudah Ada' : '' }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Upload Foto Resmi ID Card -->
                    <div class="p-5 rounded-2xl bg-amber-50/50 border border-amber-200/80">
                        <label class="block text-xs font-bold text-gray-800 uppercase mb-2 flex items-center gap-1.5">
                            <i data-lucide="camera" class="w-4 h-4 text-amber-700"></i>
                            Foto Resmi untuk ID Card <span class="text-gray-400 font-normal normal-case">(Opsional, dapat diunggah/diubah kapan saja)</span>
                        </label>
                        <div class="flex flex-col sm:flex-row items-center gap-5">
                            <div class="w-24 h-32 rounded-xl bg-white border-2 border-dashed border-amber-300 flex flex-col items-center justify-center overflow-hidden shrink-0 shadow-inner relative group" id="photoPreviewContainer">
                                <img id="photoPreview" src="" alt="Preview Foto" class="w-full h-full object-cover hidden">
                                <div id="photoPlaceholder" class="flex flex-col items-center justify-center text-gray-400 p-2 text-center">
                                    <i data-lucide="user" class="w-8 h-8 text-amber-300 mb-1"></i>
                                    <span class="text-[10px] font-bold text-gray-400">Pasfoto 3x4</span>
                                </div>
                            </div>
                            <div class="flex-1 space-y-2 text-center sm:text-left">
                                <label class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-amber-300 text-amber-800 text-xs font-bold hover:bg-amber-100 cursor-pointer transition shadow-2xs">
                                    <i data-lucide="upload" class="w-4 h-4 text-amber-700"></i>
                                    <span>Pilih File Foto</span>
                                    <input type="file" name="foto" id="fotoInput" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewSelectedPhoto(this)">
                                </label>
                                <p class="text-[11px] text-gray-500 leading-relaxed">
                                    Disarankan pasfoto resmi berseragam pramuka lengkap dengan rasio 3:4 atau 4:6 (latar belakang merah/biru atau polos). Format JPG, PNG, atau WEBP, maks 3MB.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-emerald-50/70 border border-emerald-200 p-4 rounded-xl flex items-start gap-3">
                        <i data-lucide="info" class="w-5 h-5 text-brand-forest shrink-0 mt-0.5"></i>
                        <p class="text-xs text-emerald-900 leading-relaxed">
                            Masing-masing regu terdiri dari maksimal <strong>8 anggota</strong>, dengan tepat <strong>1 Pemimpin Regu (Pinru)</strong> dan <strong>1 Wakil Pemimpin Regu (Wapinru)</strong>. Data anggota yang ditambahkan akan otomatis terhubung ke sistem master siswa dan kartu tanda peserta (ID Card).
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('peserta.dashboard') }}"
                            class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-bold transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-brand-forest hover:bg-emerald-900 text-white text-xs font-bold rounded-xl shadow-md transition flex items-center gap-2">
                            <i data-lucide="save" class="w-4 h-4 text-brand-amber"></i> Simpan Anggota
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function previewSelectedPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('photoPreview');
                const placeholder = document.getElementById('photoPlaceholder');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush