@extends('layouts.main')

@section('title', 'Pengaturan Rekening & Keuntungan Sponsor - Admin Spencerone Camp 2026')

@section('content')
<div class="py-10 min-h-screen bg-[#F8FAF8]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200/60 text-xs font-semibold uppercase tracking-wider mb-2">
                    <i data-lucide="handshake" class="w-3.5 h-3.5 text-emerald-600"></i>
                    <span>Kemitraan & Sponsorship</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight">
                    Pengaturan Rekening & Keuntungan
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    Atur rekening bank resmi tujuan transfer dan daftar keuntungan/apresiasi yang ditampilkan kepada calon sponsor.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('sponsorship.daftar') }}" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-xs text-brand-forest bg-white hover:bg-gray-50 border border-gray-200 shadow-xs transition">
                    <i data-lucide="external-link" class="w-4 h-4 text-emerald-600"></i>
                    <span>Lihat Halaman Publik</span>
                </a>
                <a href="{{ route('admin.sponsorships.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-xs text-gray-600 bg-white hover:bg-gray-50 border border-gray-200 shadow-xs transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 font-bold rounded-2xl shadow-2xs flex items-center gap-3 text-sm">
                <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-2xl shadow-xs text-xs">
                <div class="flex items-center gap-2 font-bold text-red-800 mb-1">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-red-600"></i>
                    <span>Terdapat kesalahan validasi:</span>
                </div>
                <ul class="list-disc pl-5 space-y-0.5 text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.sponsorships.settings.update') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                {{-- KOLOM KIRI: PENGATURAN REKENING BANK --}}
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-6 sm:p-7">
                        <div class="flex items-center gap-3 pb-4 mb-5 border-b border-gray-100">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-700 flex items-center justify-center font-bold">
                                <i data-lucide="credit-card" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-black text-brand-forest uppercase tracking-tight">Rekening Bank Panitia</h2>
                                <p class="text-xs text-gray-500">Tujuan transfer dana sponsorship resmi</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Nama Bank <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="bank_name" id="input_bank_name"
                                    value="{{ old('bank_name', $bankSettings['bank_name']) }}" required
                                    placeholder="Contoh: Bank BRI / BCA / Mandiri / BSI"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Nomor Rekening <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="bank_account" id="input_bank_account"
                                    value="{{ old('bank_account', $bankSettings['bank_account']) }}" required
                                    placeholder="Contoh: 0643-01-002891-53-4"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-mono font-bold focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Atas Nama Pemilik Rekening <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="bank_owner" id="input_bank_owner"
                                    value="{{ old('bank_owner', $bankSettings['bank_owner']) }}" required
                                    placeholder="Contoh: Gudep SMPN 1 Cerbon"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Instruksi / Pengantar Transfer
                                </label>
                                <textarea name="bank_instruction" id="input_bank_instruction" rows="3"
                                    placeholder="Instruksi pengantar transfer..."
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition">{{ old('bank_instruction', $bankSettings['bank_instruction']) }}</textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Catatan / Pengingat Bukti
                                </label>
                                <input type="text" name="bank_note" id="input_bank_note"
                                    value="{{ old('bank_note', $bankSettings['bank_note']) }}"
                                    placeholder="Contoh: Harap simpan bukti transfer untuk dilampirkan pada formulir."
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition">
                            </div>
                        </div>

                        {{-- Live Preview Box Rekening --}}
                        <div class="mt-6 pt-5 border-t border-gray-100">
                            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-3">Preview Tampilan di Halaman Pendaftaran:</span>
                            <div class="bg-[#0B2317] rounded-2xl p-5 text-white border border-amber-500/40 shadow-inner relative overflow-hidden">
                                <div class="flex items-center gap-2.5 mb-3">
                                    <div class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center">
                                        <i data-lucide="credit-card" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <span class="text-[9px] font-black uppercase tracking-wider text-amber-400 block">Rekening Resmi Panitia</span>
                                        <h4 class="text-xs font-bold text-white leading-tight">Bank Penampung Dana</h4>
                                    </div>
                                </div>
                                <p class="text-[11px] text-emerald-100/80 mb-3" id="preview_bank_instruction">
                                    {{ $bankSettings['bank_instruction'] }}
                                </p>
                                <div class="bg-[#071910] p-3 rounded-xl border border-emerald-500/30 space-y-1.5 font-mono text-xs">
                                    <div class="flex justify-between items-center text-gray-400">
                                        <span class="text-[10px]">Bank:</span>
                                        <span class="text-white font-bold" id="preview_bank_name">{{ $bankSettings['bank_name'] }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-gray-400">
                                        <span class="text-[10px]">Nomor Rekening:</span>
                                        <span class="text-amber-400 font-black tracking-wider text-xs" id="preview_bank_account">{{ $bankSettings['bank_account'] }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-gray-400">
                                        <span class="text-[10px]">Atas Nama:</span>
                                        <span class="text-white font-bold text-xs" id="preview_bank_owner">{{ $bankSettings['bank_owner'] }}</span>
                                    </div>
                                </div>
                                <div class="mt-3 pt-2.5 border-t border-emerald-800/60 flex items-center gap-1.5 text-[10px] text-emerald-200/80">
                                    <i data-lucide="info" class="w-3.5 h-3.5 text-amber-400 shrink-0"></i>
                                    <span id="preview_bank_note">{{ $bankSettings['bank_note'] }}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- KOLOM KANAN: PENGATURAN KEUNTUNGAN / BENEFIT SPONSOR --}}
                <div class="lg:col-span-7 space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-6 sm:p-7">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 mb-5 border-b border-gray-100 gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-700 flex items-center justify-center font-bold">
                                    <i data-lucide="sparkles" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-black text-brand-forest uppercase tracking-tight">Keuntungan Untuk Sponsor</h2>
                                    <p class="text-xs text-gray-500">Poin apresiasi dan hak yang diperoleh mitra sponsor</p>
                                </div>
                            </div>
                            <button type="button" onclick="addBenefitRow()"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-brand-forest hover:bg-emerald-900 border border-emerald-800 shadow-2xs transition shrink-0 cursor-pointer">
                                <i data-lucide="plus-circle" class="w-4 h-4 text-amber-400"></i>
                                <span>Tambah Keuntungan</span>
                            </button>
                        </div>

                        {{-- Dynamic Repeater Container --}}
                        <div id="benefits_container" class="space-y-4">
                            @forelse($benefits as $index => $benefit)
                                <div class="benefit-card bg-gray-50 p-4 sm:p-5 rounded-2xl border border-gray-200/80 transition relative" data-index="{{ $index }}">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-2">
                                            <span class="benefit-number w-6 h-6 rounded-lg bg-emerald-700 text-white flex items-center justify-center text-xs font-black">
                                                {{ $index + 1 }}
                                            </span>
                                            <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Poin Keuntungan Sponsor</span>
                                        </div>
                                        <button type="button" onclick="removeBenefitRow(this)"
                                            class="text-rose-500 hover:text-rose-700 p-1 rounded-lg hover:bg-rose-50 transition text-xs flex items-center gap-1 cursor-pointer"
                                            title="Hapus poin ini">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            <span class="hidden sm:inline font-semibold">Hapus</span>
                                        </button>
                                    </div>

                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">
                                                Judul Keuntungan <span class="text-rose-500">*</span>
                                            </label>
                                            <input type="text" name="benefits[{{ $index }}][title]"
                                                value="{{ $benefit['title'] ?? '' }}" required
                                                placeholder="Contoh: Logo Ditampilkan di Beranda Web"
                                                class="w-full px-3.5 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-900 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">
                                                Deskripsi / Hak Sponsor
                                            </label>
                                            <textarea name="benefits[{{ $index }}][description]" rows="2"
                                                placeholder="Rincian hak dan manfaat yang didapat mitra..."
                                                class="w-full px-3.5 py-2 bg-white border border-gray-200 rounded-xl text-xs font-medium text-gray-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition">{{ $benefit['description'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div id="empty_benefits_notice" class="p-8 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                                    <i data-lucide="award" class="w-8 h-8 text-gray-400 mx-auto mb-2"></i>
                                    <p class="text-xs text-gray-500 font-semibold">Belum ada poin keuntungan sponsor yang ditambahkan.</p>
                                    <p class="text-[11px] text-gray-400 mt-0.5">Klik tombol "+ Tambah Keuntungan" di atas untuk membuat poin baru.</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- Bottom Add Button --}}
                        <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end">
                            <button type="button" onclick="addBenefitRow()"
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-brand-forest bg-emerald-50 hover:bg-emerald-100 border border-emerald-200/80 transition cursor-pointer">
                                <i data-lucide="plus" class="w-4 h-4 text-emerald-700"></i>
                                <span>+ Tambah Baris Keuntungan</span>
                            </button>
                        </div>
                    </div>

                    {{-- Tombol Simpan Aksi Utama --}}
                    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-left">
                            <span class="text-xs font-bold text-gray-700 block">Pastikan data sudah sesuai</span>
                            <span class="text-[11px] text-gray-400 block">Perubahan akan langsung diterapkan pada halaman publik pendaftaran sponsor.</span>
                        </div>
                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <a href="{{ route('admin.sponsorships.index') }}"
                                class="w-full sm:w-auto px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-xs text-center transition">
                                Batal
                            </a>
                            <button type="submit"
                                class="w-full sm:w-auto px-6 py-2.5 bg-brand-forest hover:bg-emerald-900 text-white font-black rounded-xl text-xs shadow-md hover:shadow-lg transition flex items-center justify-center gap-2 border border-amber-400/40 cursor-pointer">
                                <i data-lucide="save" class="w-4 h-4 text-amber-400"></i>
                                <span>Simpan Semua Pengaturan</span>
                            </button>
                        </div>
                    </div>

                </div>

            </div>

        </form>

    </div>
</div>

<script>
    let benefitCounter = {{ count($benefits) }};

    function addBenefitRow() {
        const emptyNotice = document.getElementById('empty_benefits_notice');
        if (emptyNotice) {
            emptyNotice.remove();
        }

        const container = document.getElementById('benefits_container');
        const index = benefitCounter++;
        const currentCount = container.querySelectorAll('.benefit-card').length + 1;

        const rowHtml = `
            <div class="benefit-card bg-gray-50 p-4 sm:p-5 rounded-2xl border border-gray-200/80 transition relative" data-index="${index}">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="benefit-number w-6 h-6 rounded-lg bg-emerald-700 text-white flex items-center justify-center text-xs font-black">
                            ${currentCount}
                        </span>
                        <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Poin Keuntungan Sponsor</span>
                    </div>
                    <button type="button" onclick="removeBenefitRow(this)"
                        class="text-rose-500 hover:text-rose-700 p-1 rounded-lg hover:bg-rose-50 transition text-xs flex items-center gap-1 cursor-pointer"
                        title="Hapus poin ini">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        <span class="hidden sm:inline font-semibold">Hapus</span>
                    </button>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">
                            Judul Keuntungan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="benefits[${index}][title]" required
                            placeholder="Contoh: Logo Ditampilkan di Beranda Web"
                            class="w-full px-3.5 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-900 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">
                            Deskripsi / Hak Sponsor
                        </label>
                        <textarea name="benefits[${index}][description]" rows="2"
                            placeholder="Rincian hak dan manfaat yang didapat mitra..."
                            class="w-full px-3.5 py-2 bg-white border border-gray-200 rounded-xl text-xs font-medium text-gray-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition"></textarea>
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', rowHtml);
        lucide.createIcons();
        renumberBenefits();
    }

    function removeBenefitRow(button) {
        const card = button.closest('.benefit-card');
        if (card) {
            card.remove();
            renumberBenefits();
        }
    }

    function renumberBenefits() {
        const cards = document.querySelectorAll('#benefits_container .benefit-card');
        cards.forEach((card, idx) => {
            const numBadge = card.querySelector('.benefit-number');
            if (numBadge) {
                numBadge.textContent = idx + 1;
            }
        });
    }

    // Live preview updates for bank account
    document.getElementById('input_bank_name')?.addEventListener('input', function(e) {
        document.getElementById('preview_bank_name').textContent = e.target.value || 'Bank -';
    });
    document.getElementById('input_bank_account')?.addEventListener('input', function(e) {
        document.getElementById('preview_bank_account').textContent = e.target.value || '0000-00-000000-00-0';
    });
    document.getElementById('input_bank_owner')?.addEventListener('input', function(e) {
        document.getElementById('preview_bank_owner').textContent = e.target.value || 'Panitia Pelaksana';
    });
    document.getElementById('input_bank_instruction')?.addEventListener('input', function(e) {
        document.getElementById('preview_bank_instruction').textContent = e.target.value || '-';
    });
    document.getElementById('input_bank_note')?.addEventListener('input', function(e) {
        document.getElementById('preview_bank_note').textContent = e.target.value || '-';
    });
</script>
@endsection
