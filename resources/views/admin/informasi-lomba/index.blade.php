@extends('layouts.main')

@section('title', 'Kelola Info Lomba - Admin LTI 2026')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-scout-primary flex items-center gap-3">
                    <i data-lucide="info" class="w-8 h-8"></i>
                    Kelola Informasi Lomba
                </h1>
                <p class="text-scout-primary/70 mt-1 font-medium italic">Edit Deskripsi, Juknis, Ketentuan, dan Kriteria
                    Penilaian</p>
            </div>
        </div>

        @if (session('success'))
            <div
                class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 font-bold rounded-r-lg shadow-sm flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Mata Lomba Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($mataLombas as $lomba)
                <div class="card-scout rounded-xl p-6 transition-all duration-300 hover:-translate-y-1 block group">
                    <div class="flex items-start justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-scout-primary/10 rounded-xl flex items-center justify-center text-scout-primary group-hover:bg-scout-primary group-hover:text-white transition-colors duration-300">
                            <i data-lucide="{{ $lomba->slug === 'cerdas-cermat' ? 'brain' : ($lomba->slug === 'tapak-kemah' ? 'tent' : ($lomba->slug === 'masak-konvensional' ? 'chef-hat' : ($lomba->slug === 'upcycle-art' ? 'recycle' : ($lomba->slug === 'poster-digital' ? 'image' : 'users')))) }}"
                                class="w-6 h-6"></i>
                        </div>
                        <span
                            class="bg-scout-secondary/30 text-scout-primary text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider">Lomba
                            #{{ $lomba->urutan }}</span>
                    </div>

                    <h3 class="text-xl font-bold text-scout-primary mb-2">{{ $lomba->nama }}</h3>
                    <p class="text-sm text-scout-primary/70 line-clamp-2 mb-6 h-10">
                        {{ $lomba->deskripsi ?? 'Belum ada deskripsi.' }}
                    </p>

                    <div class="flex flex-col gap-2">
                        <a href="{{ route('admin.informasi-lomba.edit', $lomba->id) }}"
                            class="w-full btn-scout-primary py-2.5 rounded-lg font-bold text-sm tracking-wide flex items-center justify-center gap-2">
                            <i data-lucide="edit" class="w-4 h-4"></i> Edit Informasi
                        </a>
                        <a href="{{ route('admin.kriteria.show', $lomba->id) }}"
                            class="w-full py-2.5 rounded-lg font-bold text-sm tracking-wide bg-white text-scout-primary border-2 border-scout-secondary hover:border-scout-primary text-center transition flex items-center justify-center gap-2">
                            <i data-lucide="list-checks" class="w-4 h-4"></i> Atur Kriteria Skor
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection