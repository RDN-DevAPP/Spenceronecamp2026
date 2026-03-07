@extends('layouts.peserta')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="card-scout p-6 rounded-lg max-w-2xl mx-auto">
                <div class="flex items-center mb-6 border-b pb-4 border-scout-secondary">
                    <a href="{{ route('peserta.dashboard') }}"
                        class="mr-4 text-scout-secondary hover:text-scout-primary transition-colors">
                        <i data-lucide="arrow-left" class="w-6 h-6"></i>
                    </a>
                    <h2 class="text-2xl font-bold text-scout-primary">Edit Anggota Regu</h2>
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('peserta.anggota.update', $anggota->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $anggota->nama) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-scout-primary focus:ring focus:ring-scout-primary focus:ring-opacity-50 py-2 px-3 border">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="tingkatan_tku" class="block text-sm font-medium text-gray-700">Tingkatan TKU</label>
                            <select name="tingkatan_tku" id="tingkatan_tku" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-scout-primary focus:ring focus:ring-scout-primary focus:ring-opacity-50 py-2 px-3 border bg-white">
                                <option value="ramu" {{ old('tingkatan_tku', $anggota->tingkatan_tku) == 'ramu' ? 'selected' : '' }}>RAMU</option>
                                <option value="rakit" {{ old('tingkatan_tku', $anggota->tingkatan_tku) == 'rakit' ? 'selected' : '' }}>RAKIT</option>
                                <option value="terap" {{ old('tingkatan_tku', $anggota->tingkatan_tku) == 'terap' ? 'selected' : '' }}>TERAP</option>
                            </select>
                        </div>
                        <div>
                            <label for="jabatan" class="block text-sm font-medium text-gray-700">Jabatan</label>
                            <select name="jabatan" id="jabatan" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-scout-primary focus:ring focus:ring-scout-primary focus:ring-opacity-50 py-2 px-3 border bg-white">
                                <option value="anggota" {{ old('jabatan', $anggota->jabatan) == 'anggota' ? 'selected' : '' }}>
                                    Anggota</option>
                                <option value="pinru" {{ old('jabatan', $anggota->jabatan) == 'pinru' ? 'selected' : '' }}>
                                    Pinru
                                </option>
                                <option value="wapinru" {{ old('jabatan', $anggota->jabatan) == 'wapinru' ? 'selected' : '' }}>
                                    Wapinru</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Pastikan hanya ada 1 Pinru dan 1 Wapinru dalam regu.</p>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            class="btn-scout-primary py-2 px-6 rounded font-bold shadow-md transition transform hover:scale-105 flex items-center">
                            <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection