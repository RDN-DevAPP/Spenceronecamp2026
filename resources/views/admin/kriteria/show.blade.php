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

        <!-- Alert / Summary -->
        <div class="bg-white border-2 border-scout-secondary rounded-xl p-6 mb-6 shadow-sm">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @if($mataLomba->slug === 'cerdas-cermat')
                    <div>
                        <p class="text-sm font-semibold text-scout-primary/70">Total Soal</p>
                        <p class="text-2xl font-bold text-scout-primary">{{ \App\Models\CerdasCermatQuestion::count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-scout-primary/70">Total Nilai Maksimal</p>
                        <p class="text-2xl font-bold text-scout-accent">{{ (int) $mataLomba->nilai_maksimal }}</p>
                    </div>
                @else
                    <div>
                        <p class="text-sm font-semibold text-scout-primary/70">Total Kriteria</p>
                        <p class="text-2xl font-bold text-scout-primary">{{ $criteria->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-scout-primary/70">Akumulasi Nilai Maks</p>
                        <div class="flex items-center gap-2">
                            <span class="text-2xl font-bold text-scout-accent">{{ $criteria->sum('nilai_max') }}</span>
                            <span class="text-scout-primary/30">/</span>
                            <form action="{{ route('admin.informasi-lomba.update', $mataLomba->id) }}" method="POST"
                                class="flex items-center gap-1">
                                @csrf @method('PUT')
                                <input type="number" name="nilai_maksimal" value="{{ (int) $mataLomba->nilai_maksimal }}"
                                    class="w-16 px-1.5 py-0.5 text-lg font-bold border-b-2 border-scout-secondary focus:border-scout-primary transition-colors text-scout-primary bg-transparent focus:outline-none">
                                <button type="submit" class="p-1 text-scout-primary hover:text-scout-accent transition-colors"
                                    title="Update Nilai Maksimal">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            @if($mataLomba->slug !== 'cerdas-cermat' && $criteria->sum('nilai_max') != $mataLomba->nilai_maksimal && $criteria->count() > 0)
                <div class="mt-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-3 text-sm rounded-r-lg">
                    <div class="flex items-start gap-2">
                        <i data-lucide="alert-triangle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                        <p><strong>Peringatan:</strong> Total nilai maksimal kriteria ({{ $criteria->sum('nilai_max') }}) tidak
                            sama dengan nilai maksimal lomba ({{ $mataLomba->nilai_maksimal }}). Mohon sesuaikan kriteria agar
                            sesuai dengan ketentuan.</p>
                    </div>
                </div>
            @endif
        </div>

        @if($mataLomba->slug === 'cerdas-cermat')
            <div class="text-center py-16 bg-white rounded-xl border-2 border-scout-secondary shadow-sm">
                <div class="bg-scout-secondary/20 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="brain" class="w-10 h-10 text-scout-primary"></i>
                </div>
                <h3 class="text-2xl font-bold text-scout-primary mb-3">Lomba Cerdas Cermat</h3>
                <p class="text-scout-primary/70 max-w-lg mx-auto mb-8 leading-relaxed">
                    Sistem penilaian untuk Cerdas Cermat dikelola melalui manajemen soal. Penilaian juri dilakukan secara
                    otomatis per soal atau manual per uraian sesuai bobot yang ditentukan pada setiap butir soal.
                </p>
                <a href="{{ route('admin.cerdas-cermat.index') }}"
                    class="btn-scout-accent px-8 py-3 rounded-xl font-bold flex items-center justify-center gap-2 inline-flex shadow-lg shadow-scout-accent/20">
                    <i data-lucide="settings-2" class="w-5 h-5"></i> Ke Manajemen Soal & Skor
                </a>
            </div>
        @elseif($criteria->isEmpty())
            <div class="text-center py-12 bg-white rounded-xl border-2 border-dashed border-scout-secondary">
                <i data-lucide="clipboard-x" class="w-16 h-16 text-scout-secondary mx-auto mb-4"></i>
                <h3 class="text-lg font-bold text-scout-primary">Belum Ada Kriteria Penilaian</h3>
                <p class="text-scout-primary/70 mt-2 mb-4">Tambahkan kriteria pertama agar juri dapat memberikan nilai untuk
                    lomba ini.</p>
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