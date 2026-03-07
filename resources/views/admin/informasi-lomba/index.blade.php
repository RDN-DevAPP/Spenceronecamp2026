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
                            @php
                                $icon = match ($lomba->slug) {
                                    'cerdas-cermat' => 'brain',
                                    'tapak-kemah' => 'tent',
                                    'masak-konvensional' => 'chef-hat',
                                    'upcycle-art' => 'recycle',
                                    'desain-poster-digital' => 'image',
                                    default => 'users',
                                };
                            @endphp
                            <i data-lucide="{{ $icon }}" class="w-6 h-6"></i>
                        </div>
                        <span
                            class="bg-scout-secondary/30 text-scout-primary text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider">Lomba
                            #{{ $lomba->urutan }}</span>
                    </div>

                    <h3 class="text-xl font-bold text-scout-primary mb-1">{{ $lomba->nama }}</h3>

                    <!-- Kode Mata Lomba -->
                    <div class="mb-3">
                        <form action="{{ route('admin.informasi-lomba.update', $lomba->id) }}" method="POST"
                            class="flex flex-wrap items-center gap-2">
                            @csrf
                            @method('PUT')
                            <div class="flex items-center gap-1">
                                <span class="text-[10px] text-scout-primary/60 font-semibold uppercase">KODE:</span>
                                <input type="text" name="kode" value="{{ $lomba->kode }}" maxlength="6" minlength="6"
                                    pattern="[A-Za-z0-9]{6}" placeholder="------"
                                    class="w-20 px-2 py-1 text-[10px] font-mono font-bold uppercase tracking-wider border border-scout-secondary rounded-lg text-center text-scout-primary bg-white focus:ring-2 focus:ring-scout-accent/30 focus:border-scout-primary transition"
                                    oninput="this.value=this.value.toUpperCase().replace(/[^A-Z0-9]/g,'')">
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="text-[10px] text-scout-primary/60 font-semibold uppercase">MAKS:</span>
                                @if($lomba->slug === 'cerdas-cermat')
                                    <span
                                        class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold rounded border border-blue-200">
                                        {{ (int) $lomba->nilai_maksimal }} (Auto)
                                    </span>
                                @else
                                    <input type="number" name="nilai_maksimal" value="{{ (int) $lomba->nilai_maksimal }}"
                                        class="w-16 px-2 py-1 text-[10px] font-bold border border-scout-secondary rounded-lg text-center text-scout-primary bg-white focus:ring-2 focus:ring-scout-accent/30 focus:border-scout-primary transition">
                                @endif
                            </div>
                            @if($lomba->slug !== 'cerdas-cermat')
                                <button type="submit"
                                    class="p-1 px-2 bg-scout-primary text-white rounded-lg text-xs font-bold hover:bg-scout-primary/80 transition shadow-sm">
                                    <i data-lucide="save" class="w-3.5 h-3.5"></i>
                                </button>
                            @endif
                        </form>
                    </div>

                    <p class="text-sm text-scout-primary/70 line-clamp-2 mb-4 h-10">
                        {{ $lomba->deskripsi ?? 'Belum ada deskripsi.' }}
                    </p>

                    <div class="flex flex-col gap-2">
                        <a href="{{ route('admin.informasi-lomba.edit', $lomba->id) }}"
                            class="w-full btn-scout-primary py-2.5 rounded-lg font-bold text-sm tracking-wide flex items-center justify-center gap-2">
                            <i data-lucide="edit" class="w-4 h-4"></i> Kelola Lomba
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection