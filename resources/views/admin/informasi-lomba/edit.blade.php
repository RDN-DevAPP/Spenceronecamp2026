@extends('layouts.main')

@section('title', 'Edit Info: ' . $mataLomba->nama . ' - Admin LTI 2026')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('admin.informasi-lomba.index') }}"
                    class="text-scout-primary/70 hover:text-scout-accent transition-colors font-semibold text-sm flex items-center gap-1">
                    Kelola Info Lomba
                </a>
                <span class="text-scout-primary/50 text-sm">/</span>
                <span class="text-scout-primary text-sm font-bold">Edit {{ $mataLomba->nama }}</span>
            </div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-extrabold text-scout-primary flex items-center gap-3">
                        <i data-lucide="edit-3" class="w-8 h-8"></i>
                        Edit: {{ $mataLomba->nama }}
                    </h1>
                    <p class="text-scout-primary/70 mt-1 font-medium italic">Perbarui informasi dan juknis lomba</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.kriteria.show', $mataLomba->id) }}"
                        class="btn-scout-accent px-5 py-2.5 rounded-xl font-bold flex items-center justify-center gap-2 shadow-sm text-sm">
                        <i data-lucide="list-checks" class="w-4 h-4"></i>
                        Atur Kriteria Penilaian
                    </a>
                    <a href="{{ route('admin.informasi-lomba.index') }}" class="text-scout-primary/60 hover:text-scout-primary font-bold flex items-center gap-2 transition-colors px-3">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="card-scout rounded-2xl p-6 sm:p-8">
            <form action="{{ route('admin.informasi-lomba.update', $mataLomba->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-8">
                    <!-- Nama -->
                    <div class="bg-scout-surface/30 p-5 rounded-xl border border-scout-secondary/20">
                        <label for="nama" class="block text-base font-bold text-scout-primary mb-3 flex items-center gap-2">
                            <i data-lucide="tag" class="w-5 h-5 text-scout-accent"></i>
                            Nama Mata Lomba
                        </label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $mataLomba->nama) }}" required
                            class="w-full px-4 py-3 rounded-xl border-2 border-scout-secondary/50 focus:border-scout-primary focus:ring-0 bg-white text-scout-primary transition-all @error('nama') border-red-500 @enderror">
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi Singkat -->
                    <div class="bg-scout-surface/30 p-5 rounded-xl border border-scout-secondary/20">
                        <label for="deskripsi"
                            class="block text-base font-bold text-scout-primary mb-3 flex items-center gap-2">
                            <i data-lucide="align-left" class="w-5 h-5 text-scout-accent"></i>
                            Deskripsi Singkat Lomba
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="3"
                            class="w-full px-4 py-3 rounded-xl border-2 border-scout-secondary/50 focus:border-scout-primary focus:ring-0 bg-white text-scout-primary transition-all @error('deskripsi') border-red-500 @enderror"
                            placeholder="Deskripsi singkat yang akan muncul di kartu informasi...">{{ old('deskripsi', $mataLomba->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Petunjuk Teknis -->
                    <div class="bg-scout-surface/30 p-5 rounded-xl border border-scout-secondary/20">
                        <label for="petunjuk_teknis"
                            class="block text-base font-bold text-scout-primary mb-3 flex items-center gap-2">
                            <i data-lucide="file-text" class="w-5 h-5 text-scout-accent"></i>
                            Petunjuk Teknis (Juknis)
                        </label>
                        <textarea name="petunjuk_teknis" id="petunjuk_teknis" rows="6"
                            class="w-full px-4 py-3 rounded-xl border-2 border-scout-secondary/50 focus:border-scout-primary focus:ring-0 bg-white text-scout-primary transition-all @error('petunjuk_teknis') border-red-500 @enderror"
                            placeholder="Tuliskan petunjuk teknis pelaksanaan lomba...">{{ old('petunjuk_teknis', $mataLomba->petunjuk_teknis) }}</textarea>
                        @error('petunjuk_teknis')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ketentuan Pelaksanaan -->
                    <div class="bg-scout-surface/30 p-5 rounded-xl border border-scout-secondary/20">
                        <label for="ketentuan_pelaksanaan"
                            class="block text-base font-bold text-scout-primary mb-3 flex items-center gap-2">
                            <i data-lucide="shield-alert" class="w-5 h-5 text-scout-accent"></i>
                            Ketentuan Pelaksanaan
                        </label>
                        <textarea name="ketentuan_pelaksanaan" id="ketentuan_pelaksanaan" rows="6"
                            class="w-full px-4 py-3 rounded-xl border-2 border-scout-secondary/50 focus:border-scout-primary focus:ring-0 bg-white text-scout-primary transition-all @error('ketentuan_pelaksanaan') border-red-500 @enderror"
                            placeholder="Tuliskan ketentuan dan syarat pelaksanaan lomba...">{{ old('ketentuan_pelaksanaan', $mataLomba->ketentuan_pelaksanaan) }}</textarea>
                        @error('ketentuan_pelaksanaan')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>


                </div>

                <!-- Action Buttons -->
                <div class="mt-10 flex flex-col md:flex-row gap-4 pt-8 border-t border-scout-primary/10">
                    <button type="submit"
                        class="w-full md:w-auto btn-scout-accent px-10 py-4 rounded-xl font-bold flex items-center justify-center gap-2 shadow-lg shadow-scout-accent/20 hover:scale-[1.02] transition-transform">
                        <i data-lucide="save" class="w-5 h-5"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.informasi-lomba.index') }}"
                        class="w-full md:w-auto px-10 py-4 rounded-xl font-bold bg-white text-scout-primary border-2 border-scout-secondary hover:border-scout-primary text-center transition flex items-center justify-center gap-2">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection