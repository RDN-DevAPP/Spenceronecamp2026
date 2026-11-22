@extends('layouts.peserta')

@section('title', 'Hasta Karya: Scout Craftsmanship - ' . ($regu->nama_regu ?? 'Peserta'))

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-brand-forest to-emerald-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden mb-8 border border-emerald-700/40">
        <div class="absolute right-0 top-0 bottom-0 opacity-15 pointer-events-none hidden md:block">
            <img src="{{ asset('images/season2/mascot-duo.png') }}" alt="Mascot Duo" class="h-full object-contain object-right">
        </div>
        <div class="relative z-10 max-w-2xl">
            <div class="inline-flex items-center gap-2 bg-amber-400/20 text-amber-300 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider mb-3 border border-amber-400/30">
                <i data-lucide="sparkles" class="w-4 h-4"></i> Lomba Resmi Season II
            </div>
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white mb-2">
                Scout Craftsmanship: Karsa Pandu Nusantara
            </h1>
            <p class="text-emerald-100/90 text-sm sm:text-base leading-relaxed">
                Pamerkan daya cipta, kerapian ikatan simpul tali/bambu/bahan alam, serta nilai filosofis karya hasta karya terbaik regu Anda.
            </p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-600 p-4 rounded-xl shadow-sm mb-6 flex items-start gap-3">
            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5"></i>
            <div>
                <p class="font-bold text-emerald-900 text-sm">Berhasil!</p>
                <p class="text-emerald-700 text-xs">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-600 p-4 rounded-xl shadow-sm mb-6 flex items-start gap-3">
            <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 shrink-0 mt-0.5"></i>
            <div>
                <p class="font-bold text-red-900 text-sm">Periksa formulir Anda:</p>
                <ul class="list-disc list-inside text-xs text-red-700 mt-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left / Main Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-6 sm:p-8">
                <form id="hastaKaryaForm" action="{{ route('peserta.karya.store', 'hasta-karya') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <h2 class="text-lg font-black text-brand-forest uppercase tracking-wide border-b border-gray-100 pb-3 mb-6 flex items-center gap-2">
                        <i data-lucide="file-text" class="w-5 h-5 text-brand-amber"></i> Identitas & Konsep Karya
                    </h2>

                    <div class="space-y-5">
                        <!-- Judul Karya -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                Judul / Nama Karya <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="judul_karya" required value="{{ old('judul_karya', $karya->judul_karya ?? '') }}"
                                placeholder="Contoh: Tempat Lampu Badai Bambu Bernuansa Nusantara"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-brand-forest focus:border-brand-forest outline-none transition">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Kategori Bahan -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                    Kategori Bahan Utama <span class="text-red-500">*</span>
                                </label>
                                <select name="kategori_bahan" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-brand-forest focus:border-brand-forest outline-none transition bg-white">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach(['Bambu & Rotan Alami', 'Kayu & Ranting Hutan', 'Tali Temali & Makrame', 'Limbah Daur Ulang Ramah Lingkungan', 'Kombinasi Bahan Alam Campuran'] as $cat)
                                        <option value="{{ $cat }}" {{ old('kategori_bahan', $karya->kategori_bahan ?? '') === $cat ? 'selected' : '' }}>
                                            {{ $cat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Anggota Penanggung Jawab / Kreator -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                    Kreator / Presenter Regu
                                </label>
                                <select name="creator_id"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-brand-forest focus:border-brand-forest outline-none transition bg-white">
                                    <option value="">-- Seluruh Anggota Regu --</option>
                                    @foreach($anggota as $a)
                                        <option value="{{ $a->id }}" {{ old('creator_id', $karya->creator_id ?? '') == $a->id ? 'selected' : '' }}>
                                            {{ $a->nama }} ({{ strtoupper($a->jabatan) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Rincian Bahan & Alat -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                Rincian Bahan & Alat yang Digunakan
                            </label>
                            <textarea name="rincian_bahan_alat" rows="3"
                                placeholder="Sebutkan jenis bambu, lem perekat alami, cat vernis, gergaji, ampelas, dll..."
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-brand-forest focus:border-brand-forest outline-none transition">{{ old('rincian_bahan_alat', $karya->rincian_bahan_alat ?? '') }}</textarea>
                        </div>

                        <!-- Fungsi Guna -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                Fungsi Guna & Manfaat Praktis
                            </label>
                            <textarea name="fungsi_guna" rows="2"
                                placeholder="Jelaskan bagaimana karya ini dapat digunakan dalam kehidupan sehari-hari atau di perkemahan..."
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-brand-forest focus:border-brand-forest outline-none transition">{{ old('fungsi_guna', $karya->fungsi_guna ?? '') }}</textarea>
                        </div>

                        <!-- Deskripsi Filosofis & Makna -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                Filosofi & Makna Karsa Pandu
                            </label>
                            <textarea name="deskripsi_filosofi" rows="4"
                                placeholder="Ceritakan filosofi di balik bentuk, warna, atau ukiran karya yang melambangkan semangat kepanduan atau kearifan lokal Barito Kuala..."
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-brand-forest focus:border-brand-forest outline-none transition">{{ old('deskripsi_filosofi', $karya->deskripsi_filosofi ?? '') }}</textarea>
                        </div>
                    </div>

                    <!-- Upload Foto Dokumentasi (3 Sudut) -->
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h2 class="text-lg font-black text-brand-forest uppercase tracking-wide flex items-center gap-2">
                                    <i data-lucide="camera" class="w-5 h-5 text-brand-amber"></i> Dokumentasi Foto Karya (3 Sudut)
                                </h2>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Kompresi otomatis client-side aktif: foto dikompresi di ponsel Anda sebelum diunggah.
                                </p>
                            </div>
                        </div>

                        <!-- Progress Bar Container -->
                        <div id="compressionProgressContainer" class="hidden mb-4 p-3 bg-emerald-50 rounded-xl border border-emerald-200">
                            <div class="flex justify-between text-xs font-bold text-emerald-800 mb-1">
                                <span id="compressionStatusText">Mengompresi gambar...</span>
                                <span id="compressionPercentText">0%</span>
                            </div>
                            <div class="w-full bg-emerald-200 h-2 rounded-full overflow-hidden">
                                <div id="compressionProgressBar" class="bg-emerald-600 h-full transition-all duration-200" style="width: 0%"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Foto Utama / Depan -->
                            <div class="space-y-2">
                                <span class="block text-[11px] font-black uppercase tracking-wider text-gray-600">1. Foto Tampak Depan</span>
                                <div class="relative aspect-square bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center p-3 text-center hover:border-brand-forest/40 transition overflow-hidden">
                                    @if(!empty($karya->foto_utama_path))
                                        <img id="preview_utama" src="{{ asset('storage/' . $karya->foto_utama_path) }}" class="absolute inset-0 w-full h-full object-cover">
                                    @else
                                        <img id="preview_utama" class="absolute inset-0 w-full h-full object-cover hidden">
                                    @endif
                                    <label class="relative z-10 cursor-pointer flex flex-col items-center bg-white/85 backdrop-blur-xs p-3 rounded-xl shadow-xs border border-gray-200/80 hover:scale-105 transition-transform">
                                        <i data-lucide="upload-cloud" class="w-6 h-6 text-brand-forest mb-1"></i>
                                        <span class="text-[10px] font-bold text-gray-700">Pilih Foto</span>
                                        <input type="file" id="input_utama" accept="image/*" class="hidden" onchange="handleImageInput(this, 'foto_utama', 'preview_utama')">
                                    </label>
                                </div>
                                <input type="file" name="foto_utama" id="file_foto_utama" class="hidden">
                            </div>

                            <!-- Foto Detail Sambungan / Anyaman -->
                            <div class="space-y-2">
                                <span class="block text-[11px] font-black uppercase tracking-wider text-gray-600">2. Detail Sambungan</span>
                                <div class="relative aspect-square bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center p-3 text-center hover:border-brand-forest/40 transition overflow-hidden">
                                    @if(!empty($karya->foto_detail_path))
                                        <img id="preview_detail" src="{{ asset('storage/' . $karya->foto_detail_path) }}" class="absolute inset-0 w-full h-full object-cover">
                                    @else
                                        <img id="preview_detail" class="absolute inset-0 w-full h-full object-cover hidden">
                                    @endif
                                    <label class="relative z-10 cursor-pointer flex flex-col items-center bg-white/85 backdrop-blur-xs p-3 rounded-xl shadow-xs border border-gray-200/80 hover:scale-105 transition-transform">
                                        <i data-lucide="zoom-in" class="w-6 h-6 text-brand-forest mb-1"></i>
                                        <span class="text-[10px] font-bold text-gray-700">Pilih Foto</span>
                                        <input type="file" id="input_detail" accept="image/*" class="hidden" onchange="handleImageInput(this, 'foto_detail', 'preview_detail')">
                                    </label>
                                </div>
                                <input type="file" name="foto_detail" id="file_foto_detail" class="hidden">
                            </div>

                            <!-- Foto Proses Pembuatan -->
                            <div class="space-y-2">
                                <span class="block text-[11px] font-black uppercase tracking-wider text-gray-600">3. Proses Pembuatan</span>
                                <div class="relative aspect-square bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center p-3 text-center hover:border-brand-forest/40 transition overflow-hidden">
                                    @if(!empty($karya->foto_proses_path))
                                        <img id="preview_proses" src="{{ asset('storage/' . $karya->foto_proses_path) }}" class="absolute inset-0 w-full h-full object-cover">
                                    @else
                                        <img id="preview_proses" class="absolute inset-0 w-full h-full object-cover hidden">
                                    @endif
                                    <label class="relative z-10 cursor-pointer flex flex-col items-center bg-white/85 backdrop-blur-xs p-3 rounded-xl shadow-xs border border-gray-200/80 hover:scale-105 transition-transform">
                                        <i data-lucide="hammer" class="w-6 h-6 text-brand-forest mb-1"></i>
                                        <span class="text-[10px] font-bold text-gray-700">Pilih Foto</span>
                                        <input type="file" id="input_proses" accept="image/*" class="hidden" onchange="handleImageInput(this, 'foto_proses', 'preview_proses')">
                                    </label>
                                </div>
                                <input type="file" name="foto_proses" id="file_foto_proses" class="hidden">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 pt-4">
                        <button type="submit" id="submitKaryaBtn"
                            class="w-full py-4 bg-brand-forest hover:bg-emerald-900 text-white font-black text-sm uppercase tracking-wider rounded-xl shadow-lg transition flex items-center justify-center gap-2">
                            <i data-lucide="send" class="w-5 h-5 text-brand-amber"></i>
                            <span>Simpan & Kirim Portofolio Hasta Karya</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right / Information Card & Checklist -->
        <div class="space-y-6">

            <!-- Regu Profile Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 text-center">
                <div class="w-20 h-20 mx-auto mb-3 rounded-full bg-amber-500/10 border-2 border-brand-amber flex items-center justify-center text-brand-forest font-black text-2xl shadow-inner">
                    {{ $regu->nomor_regu }}
                </div>
                <h3 class="text-xl font-black text-gray-900 uppercase">{{ $regu->nama_regu }}</h3>
                <div class="inline-block mt-1 px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold uppercase">
                    Regu {{ $regu->jenis }} • {{ $regu->nomor_kavling ?? 'Kavling Tenda' }}
                </div>

                <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-2 gap-2 text-left text-xs">
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Status Portofolio</span>
                        <span class="font-bold {{ $karya ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ $karya ? 'Sudah Diajukan' : 'Belum Dikirim' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Total Anggota</span>
                        <span class="font-bold text-gray-700">{{ $anggota->count() }} Pramuka</span>
                    </div>
                </div>

            </div>

            <!-- Juklak & Rubrik Penilaian -->
            <div class="bg-gradient-to-br from-emerald-900 to-brand-forest rounded-2xl p-6 text-white shadow-sm">
                <h4 class="font-black text-sm uppercase tracking-wider text-amber-400 mb-3 flex items-center gap-2">
                    <i data-lucide="award" class="w-4 h-4"></i> Bobot Penilaian Juri
                </h4>
                <ul class="space-y-2.5 text-xs text-emerald-100/90">
                    <li class="flex items-start gap-2">
                        <span class="bg-amber-400 text-brand-forest font-black px-1.5 py-0.2 rounded text-[10px]">30%</span>
                        <span>Kerapian ikatan, simpul, dan kekuatan struktur.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="bg-amber-400 text-brand-forest font-black px-1.5 py-0.2 rounded text-[10px]">25%</span>
                        <span>Kreativitas desain & estetika visual.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="bg-amber-400 text-brand-forest font-black px-1.5 py-0.2 rounded text-[10px]">25%</span>
                        <span>Fungsi guna & kebermanfaatan dalam perkemahan/harian.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="bg-amber-400 text-brand-forest font-black px-1.5 py-0.2 rounded text-[10px]">20%</span>
                        <span>Filosofi karya & pemanfaatan bahan ramah lingkungan.</span>
                    </li>
                </ul>
            </div>

            @if($karya)
                <!-- Reset / Hapus Karya -->
                <form action="{{ route('peserta.karya.destroy', 'hasta-karya') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mereset portofolio karya ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-2.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 border border-red-200">
                        <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus Portofolio
                    </button>
                </form>
            @endif

        </div>

    </div>

</div>
@endsection

@push('scripts')
<script src="{{ asset('js/image-compressor.js') }}"></script>
<script>
    async function handleImageInput(inputElem, targetFieldName, previewId) {
        if (!inputElem.files || !inputElem.files[0]) return;

        const file = inputElem.files[0];
        const progressContainer = document.getElementById('compressionProgressContainer');
        const progressBar = document.getElementById('compressionProgressBar');
        const progressText = document.getElementById('compressionStatusText');
        const percentText = document.getElementById('compressionPercentText');
        const previewImg = document.getElementById(previewId);
        const hiddenFileInput = document.getElementById('file_' + targetFieldName);

        progressContainer.classList.remove('hidden');

        try {
            const compressedFile = await window.compressImage(file, {
                maxWidth: 1920,
                maxHeight: 1080,
                quality: 0.82,
                onProgress: function(percent, status) {
                    progressBar.style.width = percent + '%';
                    percentText.innerText = percent + '%';
                    progressText.innerText = status;
                }
            });

            // Set to DataTransfer and update hidden input
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(compressedFile);
            hiddenFileInput.files = dataTransfer.files;

            // Preview local image
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
            };
            reader.readAsDataURL(compressedFile);

            setTimeout(() => {
                progressContainer.classList.add('hidden');
            }, 1200);

        } catch (err) {
            console.error('Compression error:', err);
            alert('Gagal mengompres gambar: ' + err.message);
            progressContainer.classList.add('hidden');
        }
    }
</script>
@endpush
