@extends('layouts.main')

@section('title', 'Kelola Info & Mata Lomba - Admin Spencerone Camp Season II')

@section('content')
    <div class="py-10 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200/60 text-xs font-semibold uppercase tracking-wider mb-2">
                        <i data-lucide="award" class="w-3.5 h-3.5 text-emerald-600"></i>
                        Manajemen Lomba
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight">
                        Kelola Informasi & Mata Lomba
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Atur daftar lomba, juknis, ketentuan pelaksanaan, serta kriteria penilaian dewan juri.
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.informasi-lomba.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-xs sm:text-sm text-white bg-brand-forest hover:bg-emerald-900 border border-emerald-800 shadow-sm transition">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Tambah Mata Lomba</span>
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 font-bold rounded-2xl shadow-2xs flex items-center gap-3 text-sm">
                    <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 text-emerald-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 font-bold rounded-2xl shadow-2xs flex items-center gap-3 text-sm">
                    <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 text-red-600"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Mata Lomba Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($mataLombas as $lomba)
                    @php
                        $isProtected = ($lomba->slug === 'cerdas-cermat' || strtoupper($lomba->kode) === 'CRC001');
                        $icon = match ($lomba->slug) {
                            'cerdas-cermat' => 'brain',
                            'tapak-kemah' => 'tent',
                            'lkbb' => 'shield',
                            'masak-rimba' => 'flame',
                            'hasta-karya' => 'sparkles',
                            default => 'award',
                        };
                    @endphp
                    <div class="bg-white rounded-2xl p-5 sm:p-6 transition-all duration-200 hover:shadow-md flex flex-col justify-between border border-gray-200/80 shadow-sm hover:border-emerald-500/40">
                        <div>
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-11 h-11 bg-emerald-50 text-brand-forest rounded-xl flex items-center justify-center border border-emerald-200/50">
                                    <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
                                </div>
                                <div class="flex items-center gap-1.5 flex-wrap justify-end">
                                    @if($lomba->wajib_upload_foto)
                                        <span class="bg-purple-50 text-purple-800 text-[10px] font-black px-2.5 py-0.5 rounded-full uppercase tracking-wider border border-purple-200/60 flex items-center gap-1">
                                            <i data-lucide="camera" class="w-3 h-3 text-purple-600"></i>
                                            Foto ({{ $lomba->max_foto ?? 1 }})
                                        </span>
                                    @endif
                                    @if($lomba->is_scored)
                                        <span class="bg-emerald-50 text-emerald-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider border border-emerald-200/60">
                                            Scored
                                        </span>
                                    @else
                                        <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider border border-gray-200">
                                            Non-Skor
                                        </span>
                                    @endif
                                    <span class="bg-amber-50 text-amber-900 text-[10px] font-black px-2 py-0.5 rounded-md uppercase tracking-wider border border-amber-200/60">
                                        #{{ $lomba->urutan }}
                                    </span>
                                </div>
                            </div>

                            <div class="mb-1">
                                <h3 class="text-lg font-black text-gray-900 leading-snug">{{ $lomba->nama }}</h3>
                            </div>

                            <!-- Kode Mata Lomba & Max Points -->
                            <div class="mb-3.5 flex flex-wrap items-center gap-2.5 bg-gray-50/80 p-2.5 rounded-xl border border-gray-100 text-xs">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[10px] text-gray-400 font-bold uppercase">Kode:</span>
                                    <span class="px-2 py-0.5 bg-amber-100/80 text-amber-950 text-xs font-black rounded-md border border-amber-300/60 font-mono tracking-wider">
                                        {{ $lomba->kode ?? '------' }}
                                    </span>
                                </div>
                                <div class="h-3.5 w-[1px] bg-gray-200"></div>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[10px] text-gray-400 font-bold uppercase">Maks:</span>
                                    <span class="px-2 py-0.5 bg-emerald-50 text-brand-forest text-xs font-black rounded-md border border-emerald-200/60">
                                        {{ (int) $lomba->nilai_maksimal }} Poin
                                    </span>
                                </div>
                            </div>

                            <p class="text-xs text-gray-500 line-clamp-2 mb-4 leading-relaxed">
                                {{ $lomba->deskripsi ?? 'Belum ada deskripsi singkat untuk lomba ini.' }}
                            </p>
                        </div>

                        <!-- Actions Panel -->
                        <div class="space-y-2 pt-3 border-t border-gray-100">
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('admin.informasi-lomba.edit', $lomba->id) }}"
                                    class="py-2 px-3 rounded-xl font-bold text-xs bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 flex items-center justify-center gap-1.5 transition">
                                    <i data-lucide="edit" class="w-3.5 h-3.5"></i> Edit Juknis
                                </a>

                                @if($lomba->is_scored)
                                    <a href="{{ route('admin.kriteria.show', $lomba->id) }}"
                                        class="py-2 px-3 rounded-xl font-bold text-xs bg-brand-forest hover:bg-emerald-900 text-white flex items-center justify-center gap-1.5 shadow-2xs transition">
                                        <i data-lucide="list-checks" class="w-3.5 h-3.5 text-amber-400"></i> Kriteria
                                    </a>
                                @else
                                    <span class="py-2 px-3 rounded-xl font-semibold text-xs text-gray-400 bg-gray-50 border border-gray-100 flex items-center justify-center">
                                        Non-Skor
                                    </span>
                                @endif
                            </div>

                            <!-- Delete or Protected Button -->
                            @if($isProtected)
                                <div class="w-full py-1.5 bg-blue-50/70 border border-blue-200/60 text-blue-800 text-[11px] font-bold rounded-xl flex items-center justify-center gap-1.5 select-none"
                                    title="Mata lomba Cerdas Cermat terhubung dengan engine real-time WebSocket dan tidak boleh dihapus.">
                                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-blue-600"></i>
                                    <span>Modul Inti Quiz (Terkunci)</span>
                                </div>
                            @else
                                <form action="{{ route('admin.informasi-lomba.destroy', $lomba->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata lomba {{ $lomba->nama }}? Seluruh juri, kriteria penilaian, dan data terkait akan dihapus secara permanen.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full py-1.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200/60 rounded-xl font-semibold text-[11px] flex items-center justify-center gap-1.5 transition cursor-pointer">
                                        <i data-lucide="trash-2" class="w-3 h-3"></i>
                                        <span>Hapus Mata Lomba</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
@endsection