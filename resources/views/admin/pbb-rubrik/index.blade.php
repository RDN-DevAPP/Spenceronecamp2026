@extends('layouts.main')

@section('title', 'Lembar Penilaian PBB - Admin')

@section('content')
<div class="min-h-screen bg-[#F8FAF8] py-8 sm:py-12">
    <div class="max-w-[100rem] mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 mb-2">
                    <i data-lucide="clipboard-list" class="w-3.5 h-3.5"></i>
                    <span>{{ $lomba->nama }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight">Lembar Penilaian Juri PBB</h1>
                <p class="text-sm text-gray-500 mt-1 max-w-3xl">
                    Edit setiap poin materi dan nilai tiap level (Kosong s.d. Sangat Baik). Perubahan langsung dipakai pada form penilaian juri.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.pbb-rubrik.scan') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-brand-forest hover:bg-emerald-900 text-white rounded-xl text-xs font-bold shadow-xs transition cursor-pointer">
                    <i data-lucide="scan-line" class="w-4 h-4"></i> Scan Formulir PBB
                </a>
                <a href="{{ route('admin.pbb-rubrik.print') }}" target="_blank"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 rounded-xl text-xs font-bold shadow-xs transition">
                    <i data-lucide="printer" class="w-4 h-4"></i> Cetak Formulir OMR
                </a>
                <form method="POST" action="{{ route('admin.pbb-rubrik.load-default') }}"
                    onsubmit="return confirm('Muat ulang template bawaan (dokumen FORBASI)? Semua poin yang sekarang akan diganti.');">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-300 rounded-xl text-xs font-bold shadow-xs transition cursor-pointer">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Muat Template Bawaan
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm font-medium">{{ session('error') }}</div>
        @endif
        @if(isset($errors) && $errors->any())
            <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm">
                <ul class="list-disc ml-5">
                    @foreach($errors->unique() as $err)<li>{{ $err }}</li>@endforeach
                </ul>
            </div>
        @endif

        @if($groups->isEmpty())
            <div class="mb-6 bg-amber-50 border border-amber-300 text-amber-900 px-4 py-4 rounded-xl text-sm">
                Belum ada poin penilaian. Klik <strong>Muat Template Bawaan</strong> untuk mengisi otomatis dari dokumen
                <em>Lembar Penilaian U-16 PBB &amp; Komandan Pasukan</em>, atau tambahkan bagian baru secara manual.
            </div>
        @endif

        <form method="POST" action="{{ route('admin.pbb-rubrik.update') }}" id="rubrik-form" onsubmit="return prepareSubmit()">
            @csrf
            @method('PUT')

            <div id="groups" class="space-y-6">
                @foreach($groups as $kelompok => $items)
                    @include('admin.pbb-rubrik._group', ['kelompok' => $kelompok, 'items' => $items, 'labels' => $labels])
                @endforeach
            </div>

            <div class="mt-6 flex flex-col sm:flex-row gap-3 items-center justify-between sticky bottom-4 bg-white/95 backdrop-blur border border-gray-200 rounded-2xl shadow-lg p-4">
                <div class="flex items-center gap-4 text-sm">
                    <button type="button" onclick="addGroup()"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-white hover:bg-gray-50 text-brand-forest border border-emerald-300 rounded-xl text-xs font-bold transition cursor-pointer">
                        <i data-lucide="folder-plus" class="w-4 h-4"></i> Tambah Bagian
                    </button>
                    <span class="text-gray-500">Total nilai maksimal lomba:
                        <strong class="text-brand-forest text-base" id="grand-total">{{ rtrim(rtrim(number_format($totalMax, 2, '.', ''), '0'), '.') }}</strong>
                    </span>
                </div>
                <button type="submit"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-brand-forest hover:bg-emerald-900 text-white rounded-xl text-sm font-black uppercase tracking-wider shadow-md transition cursor-pointer">
                    <i data-lucide="save" class="w-4 h-4 text-amber-400"></i> Simpan Lembar Penilaian
                </button>
            </div>
        </form>

        <p class="mt-4 text-xs text-gray-500 leading-relaxed">
            Catatan: mengubah nilai level tidak mengubah nilai juri yang sudah tersimpan. Poin yang sudah memiliki nilai juri tidak dapat dihapus.
        </p>
    </div>
</div>

{{-- Templates untuk baris & bagian baru --}}
<template id="tpl-row">
    @include('admin.pbb-rubrik._row', ['i' => '__I__', 'row' => null, 'kelompok' => '', 'labels' => $labels])
</template>
<template id="tpl-group">
    @include('admin.pbb-rubrik._group', ['kelompok' => '', 'items' => collect(), 'labels' => $labels, 'isTemplate' => true])
</template>
@endsection

@push('scripts')
<script>
    let rowCounter = {{ $groups->flatten(1)->count() + 1000 }};

    function nextIndex() { return rowCounter++; }

    function addRow(btn) {
        const group = btn.closest('.rubrik-group');
        const html = document.getElementById('tpl-row').innerHTML.replaceAll('__I__', nextIndex());
        group.querySelector('tbody').insertAdjacentHTML('beforeend', html);
        refreshUi();
    }

    function addGroup() {
        const html = document.getElementById('tpl-group').innerHTML;
        document.getElementById('groups').insertAdjacentHTML('beforeend', html);
        const groups = document.querySelectorAll('.rubrik-group');
        const g = groups[groups.length - 1];
        g.querySelector('.group-title').focus();
        addRow(g.querySelector('.btn-add-row'));
        g.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function removeRow(btn) {
        if (!confirm('Hapus poin ini dari lembar penilaian?')) return;
        btn.closest('tr').remove();
        refreshUi();
    }

    function removeGroup(btn) {
        if (!confirm('Hapus seluruh bagian beserta semua poinnya?')) return;
        btn.closest('.rubrik-group').remove();
        refreshUi();
    }

    function refreshUi() {
        document.querySelectorAll('.rubrik-group').forEach(g => {
            let n = 0;
            g.querySelectorAll('tbody tr').forEach(tr => {
                tr.querySelector('.row-no').textContent = ++n;
                const vals = Array.from(tr.querySelectorAll('.lvl')).map(i => parseFloat(i.value) || 0);
                tr.querySelector('.row-max').textContent = vals.length ? Math.max(...vals) : 0;
            });
            g.querySelector('.group-sum').textContent = Array.from(g.querySelectorAll('.row-max'))
                .reduce((a, c) => a + (parseFloat(c.textContent) || 0), 0);
        });
        const total = Array.from(document.querySelectorAll('.group-sum')).reduce((a, c) => a + (parseFloat(c.textContent) || 0), 0);
        document.getElementById('grand-total').textContent = total;
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function prepareSubmit() {
        let ok = true;
        document.querySelectorAll('.rubrik-group').forEach(g => {
            const title = g.querySelector('.group-title').value.trim();
            if (!title && g.querySelectorAll('tbody tr').length) { ok = false; g.querySelector('.group-title').focus(); }
            g.querySelectorAll('.row-kelompok').forEach(h => h.value = title);
        });
        if (!ok) alert('Nama bagian wajib diisi.');
        return ok;
    }

    document.addEventListener('input', e => { if (e.target.classList.contains('lvl')) refreshUi(); });
    document.addEventListener('DOMContentLoaded', refreshUi);
</script>
@endpush
