@extends('layouts.peserta')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

        <!-- Welcome Message & Alerts -->
        <div class="px-4 py-6 sm:px-0">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
                    <p class="font-bold">Berhasil</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if ($regu)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Section: Anggota Regu -->
                    <div class="card-scout p-6 rounded-lg md:col-span-2">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 border-b border-scout-secondary/30 pb-4">
                            <div class="flex items-center">
                                <div class="bg-scout-accent p-3 rounded-full mr-4 text-scout-primary shadow-md border-2 border-scout-primary">
                                    <i data-lucide="users" class="w-8 h-8"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-black text-scout-primary uppercase tracking-tight">{{ $regu->nama_regu }}</h2>
                                    <div class="flex items-center space-x-2 text-sm font-bold text-scout-primary/70">
                                        <span class="bg-scout-secondary px-2 py-0.5 rounded text-[10px] uppercase">{{ $regu->jenis }}</span>
                                        <span>•</span>
                                        <span class="tracking-widest">{{ $regu->nomor_regu }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('peserta.anggota.create') }}" class="btn-scout-primary py-2 px-4 text-sm font-bold rounded-full shadow-lg flex items-center hover:bg-scout-accent hover:text-scout-primary transition border-2 border-scout-primary">
                                    <i data-lucide="plus" class="w-4 h-4 mr-1.5"></i> Tambah Anggota
                                </a>
                            </div>
                        </div>

                        @if ($anggota->isEmpty())
                            <p class="text-gray-500 italic">Belum ada data anggota. Silakan tambahkan anggota.</p>
                        @else
                            <div class="overflow-x-auto">
                                <!-- Mobile View -->
                                <div class="md:hidden divide-y divide-gray-100">
                                    @foreach ($anggota as $i => $a)
                                        <div class="p-4 hover:bg-scout-light/30 transition-colors">
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center min-w-0 mr-2">
                                                    <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-black text-scout-primary bg-scout-secondary rounded-full mr-2 shrink-0 shadow-sm border border-scout-primary/20">
                                                        {{ $i + 1 }}
                                                    </span>
                                                    <h3 class="text-sm font-bold text-scout-primary truncate" title="{{ $a->nama }}">
                                                        {{ $a->nama }}
                                                    </h3>
                                                </div>
                                                <div class="flex shrink-0">
                                                    @if($a->jabatan === 'pinru')
                                                        <span class="px-2 py-0.5 text-[9px] font-black uppercase rounded bg-red-600 text-white shadow-sm italic">Pinru</span>
                                                    @elseif($a->jabatan === 'wapinru')
                                                        <span class="px-2 py-0.5 text-[9px] font-black uppercase rounded bg-amber-500 text-white shadow-sm italic">Wapinru</span>
                                                    @else
                                                        <span class="px-2 py-0.5 text-[9px] font-black uppercase rounded bg-green-600 text-white shadow-sm italic">Anggota</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center text-[10px] font-bold text-scout-primary/50 uppercase tracking-tighter bg-gray-50 px-2 py-1 rounded border border-gray-100">
                                                    <i data-lucide="award" class="w-3 h-3 mr-1"></i> TKU: {{ $a->tingkatan_tku ?? '-' }}
                                                </div>
                                                <div class="flex items-center space-x-4">
                                                    <a href="{{ route('peserta.anggota.edit', $a->id) }}" class="text-yellow-600 hover:text-yellow-700 transition-transform active:scale-95">
                                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                                    </a>
                                                    <form action="{{ route('peserta.anggota.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:text-red-700 transition-transform active:scale-95">
                                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <!-- Desktop View -->
                                <table class="hidden md:table min-w-full divide-y divide-gray-200">
                                    <thead class="bg-scout-light">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b">No</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b">Nama</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b">TKU</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b">Jabatan</th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-b">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($anggota as $i => $a)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $i + 1 }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-scout-primary">{{ $a->nama }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-scout-primary uppercase">{{ $a->tingkatan_tku ?? '-' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    @if($a->jabatan === 'pinru')
                                                        <span class="px-3 py-1 inline-flex text-[10px] leading-none font-black uppercase rounded-full bg-red-600 text-white shadow-sm">Pinru</span>
                                                    @elseif($a->jabatan === 'wapinru')
                                                        <span class="px-3 py-1 inline-flex text-[10px] leading-none font-black uppercase rounded-full bg-amber-500 text-white shadow-sm">Wapinru</span>
                                                    @else
                                                        <span class="px-3 py-1 inline-flex text-[10px] leading-none font-black uppercase rounded-full bg-green-600 text-white shadow-sm">Anggota</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                    <div class="flex justify-center space-x-2">
                                                        <a href="{{ route('peserta.anggota.edit', $a->id) }}" class="text-yellow-500 hover:text-yellow-700">
                                                            <i data-lucide="edit-2" class="w-5 h-5"></i>
                                                        </a>
                                                        <form action="{{ route('peserta.anggota.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- Section: Gallery / Upload Poster & Photos (Full Width) -->
                    <div class="card-scout p-6 rounded-lg md:col-span-2">
                        <div class="flex items-center mb-4">
                            <div class="bg-scout-secondary p-2 rounded-full mr-3 text-scout-primary">
                                <i data-lucide="image" class="w-6 h-6"></i>
                            </div>
                            <h2 class="text-xl font-bold text-scout-primary">Galeri & Upload Foto</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Poster Digital -->
                            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition-all duration-300 group">
                                <div class="bg-scout-primary px-4 py-3 flex items-center justify-between">
                                    <h3 class="font-bold text-white text-lg flex items-center">
                                        <i data-lucide="image" class="w-5 h-5 mr-2"></i> Poster Digital
                                    </h3>
                                    @if ($regu->poster_digital_path)
                                        <div class="text-right">
                                            <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-0.5 rounded-full flex items-center border border-green-200 mb-1">
                                                <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i> Terupload
                                            </span>
                                            @if($regu->posterCreator)
                                                <span class="text-xs text-white/90 block">Oleh: {{ $regu->posterCreator->nama }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="bg-white/20 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                                            Belum Ada
                                        </span>
                                    @endif
                                </div>
                                <div class="p-5">
                                    @if ($regu->poster_digital_path)
                                        <div class="relative aspect-video bg-gray-100 rounded-lg overflow-hidden border border-gray-200 shadow-inner mb-4 group/image">
                                            <img src="{{ asset('storage/' . $regu->poster_digital_path) }}" 
                                                 alt="Poster Digital" 
                                                 class="w-full h-full object-cover cursor-pointer hover:scale-105 transition duration-500"
                                                 onclick="openModal('{{ asset('storage/' . $regu->poster_digital_path) }}', 'Poster Digital')">
                                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover/image:opacity-100 transition duration-300 pointer-events-none">
                                                 <span class="text-white text-sm font-semibold flex items-center">
                                                    <i data-lucide="zoom-in" class="w-4 h-4 mr-2"></i> Lihat
                                                 </span>
                                            </div>
                                        </div>
                                        <form action="{{ route('peserta.photo.delete') }}" method="POST" onsubmit="return confirm('Hapus foto ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="mata_lomba_slug" value="poster">
                                            <button type="submit" class="w-full py-2 text-sm text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg border border-transparent hover:border-red-200 transition flex items-center justify-center">
                                                <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i> Hapus Foto
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('peserta.poster.upload') }}" enctype="multipart/form-data">
                                            @csrf
                                            <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-scout-primary/20 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-scout-primary/5 transition duration-300 group/upload">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <div class="p-3 bg-white rounded-full shadow-sm mb-3 group-hover/upload:scale-110 transition duration-300">
                                                         <i data-lucide="upload-cloud" class="w-6 h-6 text-scout-primary"></i>
                                                    </div>
                                                    <p class="mb-1 text-sm text-gray-500"><span class="font-semibold text-scout-primary">Klik untuk upload</span></p>
                                                    <p class="text-xs text-gray-400">JPG, PNG, PDF (Max 5MB)</p>
                                                </div>
                                                <input type="file" name="poster" accept=".jpg,.jpeg,.png,.pdf" class="hidden" required onchange="this.form.submit()">
                                            </label>
                                            <div class="mt-2">
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Dibuat Oleh:</label>
                                                <select name="creator_id" class="block w-full text-xs rounded-md border-gray-300 shadow-sm focus:border-scout-primary focus:ring focus:ring-scout-primary focus:ring-opacity-50" required>
                                                    <option value="">Pilih Anggota</option>
                                                    @foreach($anggota as $a)
                                                        <option value="{{ $a->id }}">{{ $a->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <!-- Tapak Kemah -->
                            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition-all duration-300 group">
                                <div class="bg-scout-primary px-4 py-3 flex items-center justify-between">
                                    <h3 class="font-bold text-white text-lg flex items-center">
                                        <i data-lucide="tent" class="w-5 h-5 mr-2"></i> Tapak Kemah
                                    </h3>
                                    @if ($regu->foto_tenda_path)
                                        <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-0.5 rounded-full flex items-center border border-green-200">
                                            <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i> Terupload
                                        </span>
                                    @else
                                        <span class="bg-white/20 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                                            Belum Ada
                                        </span>
                                    @endif
                                </div>
                                <div class="p-5">
                                    @if ($regu->foto_tenda_path)
                                        <div class="relative aspect-video bg-gray-100 rounded-lg overflow-hidden border border-gray-200 shadow-inner mb-4 group/image">
                                            <img src="{{ asset('storage/' . $regu->foto_tenda_path) }}" 
                                                 alt="Tapak Kemah" 
                                                 class="w-full h-full object-cover cursor-pointer hover:scale-105 transition duration-500"
                                                 onclick="openModal('{{ asset('storage/' . $regu->foto_tenda_path) }}', 'Tapak Kemah')">
                                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover/image:opacity-100 transition duration-300 pointer-events-none">
                                                 <span class="text-white text-sm font-semibold flex items-center">
                                                    <i data-lucide="zoom-in" class="w-4 h-4 mr-2"></i> Lihat
                                                 </span>
                                            </div>
                                        </div>
                                        <form action="{{ route('peserta.photo.delete') }}" method="POST" onsubmit="return confirm('Hapus foto ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="mata_lomba_slug" value="tapak-kemah">
                                            <button type="submit" class="w-full py-2 text-sm text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg border border-transparent hover:border-red-200 transition flex items-center justify-center">
                                                <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i> Hapus Foto
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('peserta.upload.photo') }}" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="mata_lomba_slug" value="tapak-kemah">
                                            <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-scout-primary/20 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-scout-primary/5 transition duration-300 group/upload">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <div class="p-3 bg-white rounded-full shadow-sm mb-3 group-hover/upload:scale-110 transition duration-300">
                                                         <i data-lucide="upload-cloud" class="w-6 h-6 text-scout-primary"></i>
                                                    </div>
                                                    <p class="mb-1 text-sm text-gray-500"><span class="font-semibold text-scout-primary">Klik untuk upload</span></p>
                                                    <p class="text-xs text-gray-400">JPG, PNG (Max 5MB)</p>
                                                </div>
                                                <input type="file" name="photo" accept=".jpg,.jpeg,.png" class="hidden" required onchange="this.form.submit()">
                                            </label>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <!-- Masak Konvensional -->
                            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition-all duration-300 group">
                                <div class="bg-scout-primary px-4 py-3 flex items-center justify-between">
                                    <h3 class="font-bold text-white text-lg flex items-center">
                                        <i data-lucide="chef-hat" class="w-5 h-5 mr-2"></i> Masak Konvensional
                                    </h3>
                                    @if ($regu->foto_masakan_path)
                                        <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-0.5 rounded-full flex items-center border border-green-200">
                                            <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i> Terupload
                                        </span>
                                    @else
                                        <span class="bg-white/20 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                                            Belum Ada
                                        </span>
                                    @endif
                                </div>
                                <div class="p-5">
                                    @if ($regu->foto_masakan_path)
                                        <div class="relative aspect-video bg-gray-100 rounded-lg overflow-hidden border border-gray-200 shadow-inner mb-4 group/image">
                                            <img src="{{ asset('storage/' . $regu->foto_masakan_path) }}" 
                                                 alt="Masakan" 
                                                 class="w-full h-full object-cover cursor-pointer hover:scale-105 transition duration-500"
                                                 onclick="openModal('{{ asset('storage/' . $regu->foto_masakan_path) }}', 'Masak Konvensional')">
                                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover/image:opacity-100 transition duration-300 pointer-events-none">
                                                 <span class="text-white text-sm font-semibold flex items-center">
                                                    <i data-lucide="zoom-in" class="w-4 h-4 mr-2"></i> Lihat
                                                 </span>
                                            </div>
                                        </div>
                                        <form action="{{ route('peserta.photo.delete') }}" method="POST" onsubmit="return confirm('Hapus foto ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="mata_lomba_slug" value="masak-konvensional">
                                            <button type="submit" class="w-full py-2 text-sm text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg border border-transparent hover:border-red-200 transition flex items-center justify-center">
                                                <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i> Hapus Foto
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('peserta.upload.photo') }}" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="mata_lomba_slug" value="masak-konvensional">
                                            <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-scout-primary/20 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-scout-primary/5 transition duration-300 group/upload">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <div class="p-3 bg-white rounded-full shadow-sm mb-3 group-hover/upload:scale-110 transition duration-300">
                                                         <i data-lucide="upload-cloud" class="w-6 h-6 text-scout-primary"></i>
                                                    </div>
                                                    <p class="mb-1 text-sm text-gray-500"><span class="font-semibold text-scout-primary">Klik untuk upload</span></p>
                                                    <p class="text-xs text-gray-400">JPG, PNG (Max 5MB)</p>
                                                </div>
                                                <input type="file" name="photo" accept=".jpg,.jpeg,.png" class="hidden" required onchange="this.form.submit()">
                                            </label>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <!-- Upcycle Art -->
                            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition-all duration-300 group">
                                <div class="bg-scout-primary px-4 py-3 flex items-center justify-between">
                                    <h3 class="font-bold text-white text-lg flex items-center">
                                        <i data-lucide="recycle" class="w-5 h-5 mr-2"></i> Upcycle Art
                                    </h3>
                                    @if ($regu->foto_karya_path)
                                        <div class="text-right">
                                            <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-0.5 rounded-full flex items-center border border-green-200 mb-1">
                                                <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i> Terupload
                                            </span>
                                            @if($regu->upcycleCreator)
                                                <span class="text-xs text-white/90 block">Oleh: {{ $regu->upcycleCreator->nama }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="bg-white/20 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                                            Belum Ada
                                        </span>
                                    @endif
                                </div>
                                <div class="p-5">
                                    @if ($regu->foto_karya_path)
                                        <div class="relative aspect-video bg-gray-100 rounded-lg overflow-hidden border border-gray-200 shadow-inner mb-4 group/image">
                                            <img src="{{ asset('storage/' . $regu->foto_karya_path) }}" 
                                                 alt="Upcycle Art" 
                                                 class="w-full h-full object-cover cursor-pointer hover:scale-105 transition duration-500"
                                                 onclick="openModal('{{ asset('storage/' . $regu->foto_karya_path) }}', 'Upcycle Art')">
                                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover/image:opacity-100 transition duration-300 pointer-events-none">
                                                 <span class="text-white text-sm font-semibold flex items-center">
                                                    <i data-lucide="zoom-in" class="w-4 h-4 mr-2"></i> Lihat
                                                 </span>
                                            </div>
                                        </div>
                                        <form action="{{ route('peserta.photo.delete') }}" method="POST" onsubmit="return confirm('Hapus foto ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="mata_lomba_slug" value="upcycle-art">
                                            <button type="submit" class="w-full py-2 text-sm text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg border border-transparent hover:border-red-200 transition flex items-center justify-center">
                                                <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i> Hapus Foto
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('peserta.upload.photo') }}" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="mata_lomba_slug" value="upcycle-art">
                                            <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-scout-primary/20 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-scout-primary/5 transition duration-300 group/upload">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <div class="p-3 bg-white rounded-full shadow-sm mb-3 group-hover/upload:scale-110 transition duration-300">
                                                         <i data-lucide="upload-cloud" class="w-6 h-6 text-scout-primary"></i>
                                                    </div>
                                                    <p class="mb-1 text-sm text-gray-500"><span class="font-semibold text-scout-primary">Klik untuk upload</span></p>
                                                    <p class="text-xs text-gray-400">JPG, PNG (Max 5MB)</p>
                                                </div>
                                                <input type="file" name="photo" accept=".jpg,.jpeg,.png" class="hidden" required onchange="this.form.submit()">
                                            </label>
                                            <div class="mt-2">
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Dibuat Oleh:</label>
                                                <select name="creator_id" class="block w-full text-xs rounded-md border-gray-300 shadow-sm focus:border-scout-primary focus:ring focus:ring-scout-primary focus:ring-opacity-50" required>
                                                    <option value="">Pilih Anggota</option>
                                                    @foreach($anggota as $a)
                                                        <option value="{{ $a->id }}">{{ $a->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Image Modal -->
                    <div id="imageModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title"></h3>
                                        <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none" onclick="closeModal()">
                                            <span class="sr-only">Close</span>
                                            <i data-lucide="x" class="w-6 h-6"></i>
                                        </button>
                                    </div>
                                    <div class="mt-2">
                                        <img id="modal-image" src="" alt="" class="w-full h-auto rounded">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        function openModal(imageSrc, title) {
                            document.getElementById('modal-image').src = imageSrc;
                            document.getElementById('modal-title').innerText = title;
                            document.getElementById('imageModal').classList.remove('hidden');
                        }

                        function closeModal() {
                            document.getElementById('imageModal').classList.add('hidden');
                        }
                    </script>

                    <!-- Section: Rekap Nilai (Full Width) -->
                    <div class="card-scout p-6 rounded-lg md:col-span-2">
                        <div class="flex items-center mb-4">
                            <div class="bg-scout-secondary p-2 rounded-full mr-3 text-scout-primary">
                                <i data-lucide="clipboard-list" class="w-6 h-6"></i>
                            </div>
                            <h2 class="text-xl font-bold text-scout-primary">Rekap Nilai Lomba</h2>
                        </div>

                        @if($mataLombas->isEmpty())
                            <div class="text-center py-8 text-gray-500 bg-white rounded-lg border-2 border-dashed border-gray-300">
                                <i data-lucide="clock" class="w-12 h-12 mx-auto mb-2 opacity-50"></i>
                                <p>Belum ada kompetisi yang terdaftar.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <!-- Mobile View -->
                                <div class="md:hidden border border-gray-200 rounded-lg bg-white overflow-hidden">
                                    <div class="bg-scout-light px-4 py-3 flex justify-between items-center border-b border-gray-200 font-bold">
                                        <span class="text-gray-700 text-sm">Total Nilai Lomba</span>
                                        <span class="text-scout-primary text-lg">{{ number_format($scores->sum('nilai'), 2) }}</span>
                                    </div>
                                    <div class="divide-y divide-gray-100">
                                        @foreach ($mataLombas as $i => $lomba)
                                            @php
                                                $score = $scores->get($lomba->id);
                                            @endphp
                                            <div class="p-4 hover:bg-amber-50 transition-colors">
                                                <div class="flex justify-between items-start mb-1">
                                                    <div class="flex items-center">
                                                        <span class="text-xs font-bold text-gray-400 mr-2 w-4">{{ $i + 1 }}.</span>
                                                        <span class="text-sm font-bold text-scout-primary">{{ $lomba->nama }}</span>
                                                    </div>
                                                    @if($score)
                                                        <span class="text-base font-black text-gray-900 bg-gray-100 px-2 py-0.5 rounded">{{ number_format($score->nilai, 2) }}</span>
                                                    @else
                                                        <span class="text-base font-black text-gray-400 bg-gray-50 px-2 py-0.5 rounded">-</span>
                                                    @endif
                                                </div>
                                                @if($score && $score->catatan)
                                                    <div class="mt-2 pl-6">
                                                        <div class="text-xs text-gray-500 bg-gray-50 p-2 rounded-md border border-gray-100 italic flex items-start">
                                                            <i data-lucide="message-square" class="w-3 h-3 mr-1 mt-0.5 flex-shrink-0"></i>
                                                            {{ $score->catatan }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Desktop View -->
                                <table class="hidden md:table min-w-full divide-y divide-gray-200 border border-gray-200">
                                    <thead class="bg-scout-light">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b">
                                                No</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b">
                                                Mata Lomba</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b">
                                                Nilai</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b">
                                                Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($mataLombas as $i => $lomba)
                                            @php
                                                $score = $scores->get($lomba->id);
                                            @endphp
                                            <tr class="hover:bg-amber-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $i + 1 }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-scout-primary">
                                                    {{ $lomba->nama }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                                    {{ $score ? number_format($score->nilai, 2) : '-' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 italic">
                                                    {{ $score && $score->catatan ? $score->catatan : '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-scout-light font-bold">
                                        <tr>
                                            <td colspan="2"
                                                class="px-6 py-4 text-right text-scout-primary uppercase text-sm tracking-wider">
                                                Total Nilai</td>
                                            <td class="px-6 py-4 text-scout-primary text-lg">
                                                {{ number_format($scores->sum('nilai'), 2) }}</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @endif
                    </div>

                </div>
            @else
                <div class="card-scout p-8 text-center rounded-lg">
                    <i data-lucide="alert-triangle" class="w-16 h-16 mx-auto text-yellow-500 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Profil Regu Tidak Ditemukan</h3>
                    <p class="text-gray-600">Akun Anda tidak terhubung dengan profil regu manapun. Silakan hubungi panitia untuk
                        verifikasi.</p>
                </div>
            @endif
        </div>
    </div>
@endsection