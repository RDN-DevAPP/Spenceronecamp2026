@extends('layouts.main')

@section('title', 'Kriteria: ' . $mataLomba->nama . ' - Admin LTI 2026')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <a href="{{ route('admin.informasi-lomba.edit', $mataLomba->id) }}"
                        class="text-scout-primary/70 hover:text-scout-accent transition-colors font-semibold text-sm flex items-center gap-1">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i> Edit Informasi Lomba
                    </a>
                    <span class="text-scout-primary/50 text-sm">/</span>
                    <span class="text-scout-primary text-sm font-bold">{{ $mataLomba->nama }}</span>
                </div>
                @php
                    $icon = match ($mataLomba->slug) {
                        'cerdas-cermat' => 'brain',
                        'tapak-kemah' => 'tent',
                        'masak-konvensional' => 'chef-hat',
                        'upcycle-art' => 'recycle',
                        'desain-poster-digital' => 'image',
                        default => 'users',
                    };
                @endphp
                <h1 class="text-3xl font-bold text-scout-primary flex items-center gap-3">
                    <i data-lucide="{{ $icon }}" class="w-8 h-8"></i>
                    Kriteria {{ $mataLomba->nama }}
                </h1>
            </div>
            <div class="flex gap-3">
                @if($mataLomba->slug !== 'cerdas-cermat')
                    <a href="{{ route('admin.kriteria.create', $mataLomba->id) }}"
                        class="btn-scout-primary px-4 py-2 rounded-lg font-semibold flex items-center justify-center gap-2">
                        <i data-lucide="plus" class="w-4 h-4"></i> Tambah Kriteria
                    </a>
                @endif
            </div>
        </div>

        @if($mataLomba->slug === 'cerdas-cermat')
            <!-- Cerdas Cermat Dash View -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                <!-- Main CC Card -->
                <div class="lg:col-span-3 bg-white border-2 border-scout-secondary rounded-2xl p-8 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-8 opacity-5">
                        <i data-lucide="brain" class="w-48 h-48"></i>
                    </div>
                    
                    <div class="relative z-10">
                        <h2 class="text-2xl font-black text-scout-primary mb-2 flex items-center gap-2">
                            Penilaian Otomatis: {{ $mataLomba->nama }}
                        </h2>
                        <p class="text-scout-primary/60 font-medium mb-8 max-w-2xl leading-relaxed">
                            Lomba ini menggunakan sistem akumulasi skor otomatis dari setiap butir soal. Anda tidak perlu menentukan kriteria manual karena nilai akhir dihitung langsung oleh sistem.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-scout-secondary/10 p-5 rounded-xl border border-scout-secondary/20">
                                <p class="text-xs font-bold text-scout-primary/40 uppercase mb-1">Total Skor Lomba</p>
                                <p class="text-3xl font-black text-scout-accent">{{ (int) $mataLomba->nilai_maksimal }} <span class="text-xs font-bold text-scout-primary/40">PTS</span></p>
                            </div>
                            <div class="bg-scout-secondary/10 p-5 rounded-xl border border-scout-secondary/20">
                                <p class="text-xs font-bold text-scout-primary/40 uppercase mb-1">Total Butir Soal</p>
                                <p class="text-3xl font-black text-scout-primary">{{ array_sum($cerdasCermatCounts) }}</p>
                            </div>
                            <div class="flex items-end">
                                <a href="{{ route('admin.cerdas-cermat.index') }}"
                                    class="w-full btn-scout-accent py-4 rounded-xl font-black flex items-center justify-center gap-2 shadow-lg shadow-scout-accent/30 hover:scale-[1.02] transition-all">
                                    <i data-lucide="settings-2" class="w-6 h-6"></i>
                                    Atur Soal & Skor
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Type Breakdown -->
                <div class="bg-white border-2 border-scout-secondary rounded-2xl p-6 shadow-sm">
                    <h3 class="text-sm font-black text-scout-primary/60 uppercase mb-4 tracking-tighter">Rincian Tipe Soal</h3>
                    <div class="space-y-4">
                        @foreach($cerdasCermatCounts as $type => $count)
                            <div class="flex items-center justify-between p-3 bg-scout-secondary/5 rounded-lg border border-scout-secondary/10">
                                <span class="text-sm font-bold text-scout-primary">{{ $type }}</span>
                                <span class="bg-scout-primary text-white text-[10px] font-black px-2 py-1 rounded-full">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 pt-4 border-t border-scout-secondary/20">
                        <p class="text-[10px] text-scout-primary/40 italic leading-tight">
                            *Setiap perubahan skor pada butir soal akan otomatis memperbarui nilai maksimal lomba.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <!-- Regular Competition Dash View -->
            <!-- Configuration Card -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Summary Stats -->
                <div class="bg-white border-2 border-scout-secondary rounded-xl p-6 shadow-sm flex flex-col justify-center">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-bold text-scout-primary/60 uppercase tracking-wider mb-1">Total Kriteria</p>
                            <p class="text-3xl font-black text-scout-primary">{{ $criteria->count() }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-scout-primary/60 uppercase tracking-wider mb-1">Total Skor Kriteria</p>
                            <p class="text-3xl font-black text-scout-accent">{{ $criteria->sum('nilai_max') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Set Target Score -->
                <div class="lg:col-span-2 bg-white border-2 border-scout-secondary rounded-xl p-6 shadow-sm">
                    <form action="{{ route('admin.informasi-lomba.update', $mataLomba->id) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="from_kriteria" value="1">
                        <div class="flex flex-col md:flex-row md:items-end gap-4">
                            <div class="flex-grow">
                                <label for="nilai_maksimal" class="block text-sm font-bold text-scout-primary mb-2">
                                    Target Nilai Maksimal Lomba
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-scout-primary/40 font-bold">PTS</span>
                                    <input type="number" name="nilai_maksimal" id="target_nilai_maks" 
                                        value="{{ old('nilai_maksimal', (int) $mataLomba->nilai_maksimal) }}"
                                        class="w-full pl-14 pr-4 py-3 rounded-xl border-2 border-scout-secondary/50 focus:border-scout-primary focus:ring-0 text-xl font-bold text-scout-primary @error('nilai_maksimal') border-red-500 @enderror">
                                </div>
                                @error('nilai_maksimal')
                                    <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex gap-2">
                                <button type="button" 
                                    onclick="document.getElementById('target_nilai_maks').value = '{{ $criteria->sum('nilai_max') }}'"
                                    class="px-4 py-3 rounded-xl bg-scout-secondary/20 text-scout-primary font-bold text-sm hover:bg-scout-secondary/40 transition-colors flex items-center gap-2"
                                    title="Samakan dengan total skor kriteria saat ini">
                                    <i data-lucide="refresh-cw" class="w-4 h-4 text-scout-accent"></i>
                                    Auto Set
                                </button>
                                <button type="submit" 
                                    class="px-6 py-3 rounded-xl bg-scout-primary text-white font-bold hover:bg-scout-primary/90 transition-all flex items-center gap-2 shadow-lg shadow-scout-primary/20">
                                    <i data-lucide="save" class="w-5 h-5"></i>
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>
                    <p class="mt-3 text-xs text-scout-primary/50 flex items-center gap-1 font-medium">
                        <i data-lucide="info" class="w-3.5 h-3.5"></i>
                        Ini adalah nilai total yang menjadi acuan penilaian juri. Pastikan sama dengan total skor kriteria.
                    </p>
                </div>
            </div>

            @if($criteria->sum('nilai_max') != $mataLomba->nilai_maksimal && $criteria->count() > 0)
                <div class="mb-6 bg-red-50 border-2 border-red-200 text-red-700 p-4 rounded-xl flex items-center gap-3 animate-pulse">
                    <i data-lucide="alert-circle" class="w-6 h-6 flex-shrink-0"></i>
                    <div>
                        <p class="font-bold text-sm text-red-800">Mismatch Skor Terdeteksi!</p>
                        <p class="text-xs">Total kriteria ({{ $criteria->sum('nilai_max') }}) tidak sesuai dengan Target Lomba ({{ $mataLomba->nilai_maksimal }}). Gunakan tombol <strong>Auto Set</strong> untuk menyamakan.</p>
                    </div>
                </div>
            @endif

            @if($criteria->isEmpty())
                <div class="text-center py-12 bg-white rounded-xl border-2 border-dashed border-scout-secondary">
                    <i data-lucide="clipboard-x" class="w-16 h-16 text-scout-secondary mx-auto mb-4"></i>
                    <h3 class="text-lg font-bold text-scout-primary">Belum Ada Kriteria Penilaian</h3>
                    <p class="text-scout-primary/70 mt-2 mb-4">Tambahkan kriteria pertama agar juri dapat memberikan nilai untuk lomba ini.</p>
                    <a href="{{ route('admin.kriteria.create', $mataLomba->id) }}"
                        class="inline-flex items-center gap-2 btn-scout-accent px-4 py-2 rounded-lg font-bold">
                        <i data-lucide="plus" class="w-4 h-4"></i> Tambah Kriteria
                    </a>
                </div>
            @else
                <!-- Mobile View (Cards) -->
                <div class="block md:hidden space-y-4">
                    @foreach($criteria as $k)
                        <div class="card-scout rounded-xl p-4 flex flex-col gap-3">
                            <div class="flex justify-between items-start">
                                <h4 class="font-bold text-scout-primary text-lg leading-tight">{{ $k->nama_kriteria }}</h4>
                                <span
                                    class="bg-scout-secondary/50 text-scout-primary px-2 py-1 rounded text-xs font-bold border border-scout-primary/10 flex-shrink-0">
                                    Urutan: {{ $k->urutan }}
                                </span>
                            </div>

                            <div class="bg-white rounded-lg p-3 grid grid-cols-2 gap-2 border border-scout-secondary/50">
                                <div>
                                    <p class="text-xs text-scout-primary/60 font-semibold mb-1">Nilai Min</p>
                                    <p class="font-bold text-scout-primary">{{ $k->nilai_min }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-scout-primary/60 font-semibold mb-1">Nilai Max</p>
                                    <p class="font-bold text-scout-accent">{{ $k->nilai_max }}</p>
                                </div>
                            </div>

                            <div class="flex gap-2 mt-2 pt-2 border-t border-scout-primary/10">
                                <a href="{{ route('admin.kriteria.edit', $k->id) }}"
                                    class="flex-1 text-center py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg text-sm font-bold transition flex items-center justify-center gap-1">
                                    <i data-lucide="edit" class="w-4 h-4"></i> Edit
                                </a>
                                <button type="button" onclick="confirmDelete('{{ $k->id }}')"
                                    class="flex-1 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg text-sm font-bold transition flex items-center justify-center gap-1">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus
                                </button>
                                <form id="delete-form-{{ $k->id }}" action="{{ route('admin.kriteria.destroy', $k->id) }}" method="POST"
                                    class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Desktop View (Table) -->
                <div class="hidden md:block card-scout rounded-xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-scout-secondary">
                            <thead class="bg-scout-primary text-scout-light">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider w-16 text-center">
                                        Urutan</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Nama
                                        Kriteria</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider w-32">
                                        Nilai Minimal</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider w-32">
                                        Nilai Maksimal</th>
                                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider w-32">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-scout-secondary/50">
                                @foreach($criteria as $k)
                                    <tr class="hover:bg-scout-secondary/10 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-scout-primary text-center font-bold">
                                            {{ $k->urutan }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-bold text-scout-primary">
                                            {{ $k->nama_kriteria }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-scout-primary font-bold text-right">
                                            {{ $k->nilai_min }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-scout-accent font-bold text-right">
                                            {{ $k->nilai_max }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('admin.kriteria.edit', $k->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 p-2 rounded-lg transition"
                                                    title="Edit Kriteria">
                                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                                </a>
                                                <button type="button" onclick="confirmDelete('{{ $k->id }}')"
                                                    class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 p-2 rounded-lg transition"
                                                    title="Hapus Kriteria">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                                <form id="desktop-delete-form-{{ $k->id }}"
                                                    action="{{ route('admin.kriteria.destroy', $k->id) }}" method="POST" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Kriteria?',
                text: "Kriteria yang dihapus tidak dapat dikembalikan dan mungkin berakibat pada skor yang sudah diberikan oleh juri.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#5D4037',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Determine which form to submit based on visibility (mobile vs desktop)
                    let form = document.getElementById('delete-form-' + id) || document.getElementById('desktop-delete-form-' + id);
                    if (form) form.submit();
                }
            });
        }
    </script>
@endpush