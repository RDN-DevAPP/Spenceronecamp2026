@extends('layouts.main')

@section('title', 'Kelola Kriteria Penilaian - Admin LTI 2026')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-scout-primary flex items-center gap-3">
                    <i data-lucide="list-checks" class="w-8 h-8"></i>
                    Kelola Kriteria Penilaian
                </h1>
                <p class="text-scout-primary/80 mt-2">Pilih mata lomba untuk mengatur kriteria penilaian dan rentang nilai.
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.dashboard') }}"
                    class="btn-scout-primary px-4 py-2 rounded-lg font-semibold flex items-center justify-center gap-2">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Info Banner -->
        <div
            class="bg-scout-accent/20 border border-scout-accent text-scout-primary rounded-xl p-4 mb-6 flex items-start gap-4">
            <i data-lucide="info" class="w-6 h-6 mt-0.5 flex-shrink-0"></i>
            <div>
                <h4 class="font-bold">Informasi</h4>
                <p class="text-sm mt-1">Kriteria yang diatur di sini akan digunakan oleh juri saat memberikan penilaian.
                    Pastikan total nilai maksimum setiap kriteria sesuai dengan ketentuan masing-masing lomba.</p>
            </div>
        </div>

        <!-- Mata Lomba Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($mataLombas as $lomba)
                <a href="{{ route('admin.kriteria.show', $lomba->id) }}"
                    class="card-scout rounded-xl p-6 transition-all duration-300 hover:-translate-y-1 block group">
                    <div class="flex items-start justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-scout-primary/10 rounded-lg flex items-center justify-center text-scout-primary group-hover:bg-scout-primary group-hover:text-white transition-colors">
                            <i data-lucide="target" class="w-6 h-6"></i>
                        </div>
                        <span
                            class="bg-scout-secondary/50 text-scout-primary px-3 py-1 rounded-full text-xs font-bold border border-scout-primary/20">
                            {{ $lomba->scoringCriteria()->count() }} Kriteria
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-scout-primary mb-2 group-hover:text-scout-accent transition-colors">
                        {{ $lomba->nama }}
                    </h3>
                    <p class="text-sm text-scout-primary/70 line-clamp-2 mb-4">
                        {{ $lomba->deskripsi ?? 'Tidak ada deskripsi' }}
                    </p>

                    <div class="flex items-center text-sm font-semibold text-scout-primary mt-auto">
                        <span>Atur Kriteria</span>
                        <i data-lucide="chevron-right" class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12 bg-white rounded-xl border-2 border-dashed border-scout-secondary">
                        <i data-lucide="file-x" class="w-16 h-16 text-scout-secondary mx-auto mb-4"></i>
                        <h3 class="text-lg font-bold text-scout-primary">Belum Ada Mata Lomba</h3>
                        <p class="text-scout-primary/70 mt-2">Mata lomba belum tersedia di database.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection