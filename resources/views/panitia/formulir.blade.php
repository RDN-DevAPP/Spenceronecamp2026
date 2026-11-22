@extends('layouts.main')

@section('title', 'Formulir ID Card & Sertifikat Panitia - Spencerone Camp II')

@section('content')
<div class="min-h-screen py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto space-y-6">

        {{-- Header Banner --}}
        <div class="bg-gradient-to-r from-brand-forest via-emerald-900 to-emerald-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl border-2 border-amber-400/40 relative overflow-hidden">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 relative z-10">
                <img src="{{ asset('images/season2/logo-season2.jpg') }}" alt="Logo Season II"
                    class="w-14 h-14 rounded-2xl object-cover border-2 border-amber-400 shadow-lg shrink-0">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-amber-400 text-emerald-950 mb-1.5">
                        <i data-lucide="id-card" class="w-3.5 h-3.5"></i> Formulir Resmi Panitia
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white uppercase">
                        Formulir ID Card Panitia
                    </h1>
                    <p class="text-xs sm:text-sm text-emerald-100 mt-1 leading-relaxed">
                        Isi data diri dan pasfoto resmi Anda untuk pencetakan <strong>ID Card Panitia</strong> serta penerbitan otomatis <strong>Sertifikat Panitia</strong> setelah kegiatan selesai.
                    </p>
                </div>
            </div>
        </div>

        @if(session('submitted_panitia'))
            @php $sub = session('submitted_panitia'); @endphp
            <div class="bg-emerald-50 border-2 border-emerald-300 rounded-2xl p-5 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                        <i data-lucide="check-check" class="w-5 h-5"></i>
                    </div>
                    <div class="text-xs text-emerald-950 space-y-1">
                        <h3 class="text-sm font-black uppercase text-emerald-900">Pendaftaran ID Card Panitia Tercatat</h3>
                        <p><strong>Nama:</strong> {{ $sub['nama'] }} &bull; <strong>Jabatan:</strong> {{ $sub['jabatan'] }}</p>
                        @if(!empty($sub['divisi']))
                            <p><strong>Bidang/Divisi:</strong> {{ $sub['divisi'] }}</p>
                        @endif
                        <p><strong>Asal/Pangkalan:</strong> {{ $sub['asal_instansi'] }}</p>
                        <p class="text-emerald-700 font-semibold pt-1">Data Anda telah masuk ke daftar ID Card &amp; Sertifikat Panitia Admin.</p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-xl shadow-xs">
                <p class="font-bold text-xs uppercase tracking-wider mb-1">Periksa Input Formulir:</p>
                <ul class="list-disc list-inside text-xs space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Card --}}
        <div class="bg-white rounded-3xl border border-gray-200 shadow-lg p-6 sm:p-8">
            <form method="POST" action="{{ route('panitia.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label for="nama" class="block text-xs font-bold text-gray-800 uppercase tracking-wider mb-1.5">
                        Nama Lengkap &amp; Gelar <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                        placeholder="Contoh: Ahmad Fauzi, S.Pd."
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm font-semibold text-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-brand-forest">
                    <p class="text-[11px] text-gray-500 mt-1">Nama ini akan dicetak langsung pada ID Card &amp; Sertifikat Panitia resmi.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="jabatan" class="block text-xs font-bold text-gray-800 uppercase tracking-wider mb-1.5">
                            Jabatan Kepanitiaan <span class="text-red-500">*</span>
                        </label>
                        <select name="jabatan" id="jabatan" required onchange="toggleCustomJabatan(this.value)"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm font-semibold text-gray-900 bg-white focus:ring-2 focus:ring-emerald-500 focus:border-brand-forest">
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach($jabatanPresets as $preset)
                                <option value="{{ $preset }}" {{ old('jabatan') === $preset ? 'selected' : '' }}>{{ $preset }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="custom-jabatan-wrap" class="{{ old('jabatan') === 'Lainnya' ? '' : 'hidden' }}">
                        <label for="jabatan_custom" class="block text-xs font-bold text-gray-800 uppercase tracking-wider mb-1.5">
                            Tulis Jabatan Lainnya <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="jabatan_custom" id="jabatan_custom" value="{{ old('jabatan_custom') }}"
                            placeholder="Contoh: Ketua Sangga Kerja"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm font-semibold text-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-brand-forest">
                    </div>

                    <div>
                        <label for="divisi" class="block text-xs font-bold text-gray-800 uppercase tracking-wider mb-1.5">
                            Bidang / Seksi <span class="text-gray-500 font-normal">(Opsional)</span>
                        </label>
                        <input type="text" name="divisi" id="divisi" value="{{ old('divisi') }}"
                            placeholder="Contoh: Seksi Acara / Lomba PBB"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm font-semibold text-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-brand-forest">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="asal_instansi" class="block text-xs font-bold text-gray-800 uppercase tracking-wider mb-1.5">
                            Asal Pangkalan / Instansi
                        </label>
                        <input type="text" name="asal_instansi" id="asal_instansi" value="{{ old('asal_instansi', 'SMP Negeri 1 Cerbon') }}"
                            placeholder="SMP Negeri 1 Cerbon"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm font-semibold text-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-brand-forest">
                    </div>
                    <div>
                        <label for="no_hp" class="block text-xs font-bold text-gray-800 uppercase tracking-wider mb-1.5">
                            No. HP / WhatsApp <span class="text-gray-500 font-normal">(Opsional)</span>
                        </label>
                        <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}"
                            placeholder="08xxxxxxxxxx"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm font-mono font-semibold text-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-brand-forest">
                    </div>
                </div>

                {{-- Pasfoto Upload --}}
                <div class="p-4 sm:p-5 rounded-2xl bg-amber-50/60 border border-amber-200/80">
                    <label class="block text-xs font-bold text-gray-800 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                        <i data-lucide="camera" class="w-4 h-4 text-amber-700"></i>
                        Pasfoto Resmi ID Card Panitia <span class="text-gray-500 font-normal normal-case">(Opsional, rasio 3:4 atau 4:5)</span>
                    </label>
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <div class="w-24 h-30 aspect-[4/5] rounded-xl bg-white border-2 border-dashed border-amber-300 flex items-center justify-center overflow-hidden shrink-0">
                            <img id="panitia-photo-preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                            <div id="panitia-photo-placeholder" class="text-center p-2">
                                <i data-lucide="user" class="w-8 h-8 text-amber-300 mx-auto mb-1"></i>
                                <span class="text-[10px] font-bold text-gray-500">Pasfoto</span>
                            </div>
                        </div>
                        <div class="flex-1 space-y-2 text-center sm:text-left">
                            <label class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-amber-300 text-amber-900 text-xs font-bold hover:bg-amber-100 cursor-pointer transition shadow-xs">
                                <i data-lucide="upload" class="w-4 h-4 text-amber-700"></i>
                                <span>Pilih File Pasfoto</span>
                                <input type="file" name="foto" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewPanitiaPhoto(this)">
                            </label>
                            <p class="text-[11px] text-gray-600 leading-relaxed">
                                Format JPG, PNG, atau WEBP (maks. 4MB). Pasfoto akan tampil di ID Card Panitia.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <a href="{{ route('home') }}" class="text-xs font-bold text-gray-600 hover:text-brand-forest transition flex items-center gap-1">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Kembali ke Beranda
                    </a>
                    <button type="submit"
                        class="w-full sm:w-auto px-6 py-3 rounded-xl bg-brand-forest hover:bg-emerald-900 text-white text-sm font-black uppercase tracking-wider shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                        <i data-lucide="send" class="w-4 h-4 text-amber-400"></i>
                        <span>Kirim Data ID Card Panitia</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleCustomJabatan(val) {
        const wrap = document.getElementById('custom-jabatan-wrap');
        const customInput = document.getElementById('jabatan_custom');
        if (!wrap) return;
        if (val === 'Lainnya') {
            wrap.classList.remove('hidden');
            if (customInput) customInput.required = true;
        } else {
            wrap.classList.add('hidden');
            if (customInput) customInput.required = false;
        }
    }

    function previewPanitiaPhoto(input) {
        if (!input.files || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('panitia-photo-preview');
            const ph = document.getElementById('panitia-photo-placeholder');
            if (img) {
                img.src = e.target.result;
                img.classList.remove('hidden');
            }
            if (ph) ph.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
</script>
@endpush
