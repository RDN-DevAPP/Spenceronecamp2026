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
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm" role="alert">
                    <p class="font-bold">Terjadi Kesalahan</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($regu)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Section: Anggota Regu -->
                    <div class="card-scout p-6 rounded-2xl md:col-span-2">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 border-b border-gray-100 pb-4">
                            <div class="flex items-center gap-4">
                                <img src="{{ asset('images/season2/logo-season2.jpg') }}" alt="Logo Season II"
                                    class="w-14 h-14 rounded-2xl object-cover shadow-md border-2 border-amber-400">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h2 class="text-2xl font-black text-brand-forest uppercase tracking-tight">{{ $regu->nama_regu }}</h2>
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-black uppercase bg-emerald-100 text-emerald-800">
                                            Regu {{ $regu->jenis }}
                                        </span>
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
                                            {{ $anggota->count() }}/8 Anggota
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-xs font-bold text-gray-500 mt-1">
                                        <span>Nomor Regu: <strong class="text-brand-forest">{{ $regu->nomor_regu }}</strong></span>
                                        <span>•</span>
                                        <span>Kavling: <strong class="text-brand-amber">{{ $regu->nomor_kavling ?? 'KAV-01' }}</strong></span>
                                        <span>•</span>
                                        <span>Gudep 03.059 - 03.060 SMPN 1 Cerbon</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2.5">
                                @php
                                    $ccEligible = $regu->isEligibleForCerdasCermat();
                                    $minAnggota = \App\Models\ReguProfile::getMinAnggotaRequired();
                                @endphp
                                @if($ccEligible)
                                    <a href="{{ route('peserta.cerdas-cermat.index') }}"
                                        class="px-4 py-2.5 bg-blue-800 hover:bg-blue-900 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                                        <i data-lucide="brain" class="w-4 h-4 text-amber-300"></i> Scout Cyber Quiz Arena
                                    </a>
                                @else
                                    <span title="Lengkapi minimal {{ $minAnggota }} anggota regu untuk membuka menu Cerdas Cermat"
                                        class="px-4 py-2.5 bg-gray-100 text-gray-400 font-bold text-xs rounded-xl border border-gray-200 cursor-not-allowed flex items-center gap-2">
                                        <i data-lucide="lock" class="w-4 h-4"></i> Cyber Quiz Terkunci (Min. {{ $minAnggota }} Anggota)
                                    </span>
                                @endif
                                <a href="{{ route('peserta.administrasi.index') }}"
                                    class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                                    <i data-lucide="book-open" class="w-4 h-4 text-white"></i> 6 Buku Administrasi Regu
                                </a>
                                @if($anggota->count() < 8)
                                    <a href="{{ route('peserta.anggota.create') }}"
                                        class="px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                                        <i data-lucide="user-plus" class="w-4 h-4 text-emerald-200"></i> Tambah Anggota
                                    </a>
                                @endif
                            </div>
                        </div>

                        @if ($anggota->isEmpty())
                            <div class="py-12 px-6 text-center bg-gray-50/80 rounded-2xl border-2 border-dashed border-gray-200 my-2">
                                <div class="w-16 h-16 bg-emerald-100 text-brand-forest rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                                    <i data-lucide="users" class="w-8 h-8"></i>
                                </div>
                                <h3 class="text-base font-bold text-gray-800 mb-1">Belum Ada Anggota Terdaftar</h3>
                                <p class="text-xs text-gray-500 max-w-md mx-auto mb-6 leading-relaxed">
                                    Regu Anda belum memiliki data anggota. Silakan daftarkan anggota regu (maksimal 8 orang) termasuk Pemimpin Regu (Pinru) dan Wakil Pemimpin Regu (Wapinru).
                                </p>
                                <a href="{{ route('peserta.anggota.create') }}"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-brand-forest hover:bg-emerald-900 text-white text-xs font-bold rounded-xl shadow-md transition transform active:scale-95">
                                    <i data-lucide="user-plus" class="w-4 h-4 text-amber-300"></i> Tambah Anggota Sekarang
                                </a>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <!-- Mobile View -->
                                <div class="md:hidden divide-y divide-gray-100">
                                    @foreach ($anggota as $i => $a)
                                        <div class="p-4 hover:bg-emerald-50/30 transition-colors">
                                            <div class="flex items-center justify-between mb-2.5">
                                                <div class="flex items-center min-w-0 mr-2 gap-2.5">
                                                    <div class="relative shrink-0">
                                                        @if($a->foto_path)
                                                            <img src="{{ asset('storage/' . $a->foto_path) }}" alt="{{ $a->nama }}"
                                                                onclick="openModal('{{ asset('storage/' . $a->foto_path) }}', 'Foto ID Card - {{ addslashes($a->nama) }}')"
                                                                class="w-10 h-12 rounded-lg object-cover border-2 border-amber-300 shadow-xs cursor-pointer hover:opacity-90 transition"
                                                                title="Lihat Foto">
                                                        @else
                                                            <button type="button" onclick="openPhotoModal({{ $a->id }}, '{{ addslashes($a->nama) }}', '')"
                                                                class="w-10 h-12 rounded-lg bg-amber-50 border border-dashed border-amber-300 flex flex-col items-center justify-center text-amber-700 hover:bg-amber-100 transition"
                                                                title="Unggah Foto ID Card">
                                                                <i data-lucide="camera" class="w-4 h-4 text-amber-600"></i>
                                                                <span class="text-[8px] font-bold text-amber-800 mt-0.5">+ Foto</span>
                                                            </button>
                                                        @endif
                                                    </div>
                                                    <div class="min-w-0">
                                                        <div class="flex items-center gap-1.5">
                                                            <span class="inline-flex items-center justify-center w-4 h-4 text-[9px] font-black text-brand-forest bg-emerald-100 rounded-full shrink-0">
                                                                {{ $i + 1 }}
                                                            </span>
                                                            <h3 class="text-sm font-bold text-brand-forest truncate" title="{{ $a->nama }}">
                                                                {{ $a->nama }}
                                                            </h3>
                                                        </div>
                                                        <span class="text-[10px] text-gray-500 font-mono">{{ $a->nta ?? $a->nisn ?? 'No NTA' }}</span>
                                                    </div>
                                                </div>
                                                <div class="flex shrink-0">
                                                    @if($a->jabatan === 'pinru')
                                                        <span class="px-2.5 py-0.5 text-[9px] font-black uppercase rounded-full bg-red-600 text-white shadow-sm italic">Pinru</span>
                                                    @elseif($a->jabatan === 'wapinru')
                                                        <span class="px-2.5 py-0.5 text-[9px] font-black uppercase rounded-full bg-amber-500 text-white shadow-sm italic">Wapinru</span>
                                                    @else
                                                        <span class="px-2.5 py-0.5 text-[9px] font-black uppercase rounded-full bg-emerald-600 text-white shadow-sm italic">Anggota</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
                                                <div class="flex flex-wrap items-center gap-1.5">
                                                    <span class="text-[10px] font-bold text-gray-700 bg-gray-100 px-2 py-0.5 rounded-lg">
                                                        Kelas {{ $a->kelas }}
                                                    </span>
                                                    <span class="text-[10px] font-bold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-200 uppercase">
                                                        TKU: {{ $a->tingkatan_tku ?? '-' }}
                                                    </span>
                                                    @if($a->foto_path)
                                                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-200 flex items-center gap-1">
                                                            <i data-lucide="check" class="w-3 h-3"></i> Foto ID Siap
                                                        </span>
                                                    @else
                                                        <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-200 flex items-center gap-1">
                                                            <i data-lucide="alert-circle" class="w-3 h-3"></i> Belum ada foto
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-2 self-end sm:self-auto">
                                                    <button type="button" onclick="openPhotoModal({{ $a->id }}, '{{ addslashes($a->nama) }}', '{{ $a->foto_path ? asset('storage/' . $a->foto_path) : '' }}')"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-bold rounded-lg border border-emerald-300 transition shadow-2xs"
                                                        title="{{ $a->foto_path ? 'Ganti Foto' : 'Unggah Foto' }}">
                                                        <i data-lucide="camera" class="w-3.5 h-3.5 text-emerald-700"></i> Foto
                                                    </button>
                                                    <a href="{{ route('peserta.anggota.edit', $a->id) }}"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold rounded-lg border border-amber-300 transition shadow-sm"
                                                        title="Edit Anggota">
                                                        <i data-lucide="edit-3" class="w-3.5 h-3.5 text-amber-700"></i> Edit
                                                    </a>
                                                    <form action="{{ route('peserta.anggota.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota \'{{ $a->nama }}\'?');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="inline-flex items-center gap-1 px-2 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-bold rounded-lg border border-red-200 transition shadow-sm"
                                                            title="Hapus Anggota">
                                                            <i data-lucide="trash-2" class="w-3.5 h-3.5 text-red-600"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <!-- Desktop View -->
                                <table class="hidden md:table min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-5 py-3.5 text-left text-xs font-black text-brand-forest uppercase tracking-wider border-b">No</th>
                                            <th scope="col" class="px-4 py-3.5 text-center text-xs font-black text-brand-forest uppercase tracking-wider border-b">Foto ID</th>
                                            <th scope="col" class="px-5 py-3.5 text-left text-xs font-black text-brand-forest uppercase tracking-wider border-b">Nama Lengkap</th>
                                            <th scope="col" class="px-4 py-3.5 text-left text-xs font-black text-brand-forest uppercase tracking-wider border-b">NTA</th>
                                            <th scope="col" class="px-4 py-3.5 text-left text-xs font-black text-brand-forest uppercase tracking-wider border-b">Kelas</th>
                                            <th scope="col" class="px-4 py-3.5 text-left text-xs font-black text-brand-forest uppercase tracking-wider border-b">TKU</th>
                                            <th scope="col" class="px-4 py-3.5 text-left text-xs font-black text-brand-forest uppercase tracking-wider border-b">Jabatan</th>
                                            <th scope="col" class="px-5 py-3.5 text-center text-xs font-black text-brand-forest uppercase tracking-wider border-b">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($anggota as $i => $a)
                                            <tr class="hover:bg-emerald-50/20 transition-colors">
                                                <td class="px-5 py-4 whitespace-nowrap text-sm font-bold text-gray-500">{{ $i + 1 }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                                    @if($a->foto_path)
                                                        <div class="inline-flex items-center gap-1.5 group relative">
                                                            <img src="{{ asset('storage/' . $a->foto_path) }}" alt="{{ $a->nama }}"
                                                                onclick="openModal('{{ asset('storage/' . $a->foto_path) }}', 'Foto ID Card - {{ addslashes($a->nama) }}')"
                                                                class="w-10 h-12 rounded-lg object-cover border-2 border-amber-300 shadow-xs cursor-pointer hover:scale-105 transition-transform"
                                                                title="Klik untuk melihat foto penuh">
                                                            <button type="button" onclick="openPhotoModal({{ $a->id }}, '{{ addslashes($a->nama) }}', '{{ asset('storage/' . $a->foto_path) }}')"
                                                                class="p-1 text-gray-400 hover:text-amber-700 rounded-md hover:bg-amber-50 transition" title="Ganti Foto">
                                                                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <button type="button" onclick="openPhotoModal({{ $a->id }}, '{{ addslashes($a->nama) }}', '')"
                                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 text-[11px] font-bold rounded-lg border border-dashed border-amber-300 transition shadow-2xs cursor-pointer"
                                                            title="Tambahkan foto anggota untuk cetak ID Card">
                                                            <i data-lucide="camera" class="w-3.5 h-3.5 text-amber-700"></i>
                                                            <span>+ Foto</span>
                                                        </button>
                                                    @endif
                                                </td>
                                                <td class="px-5 py-4 whitespace-nowrap text-sm font-bold text-brand-forest">{{ $a->nama }}</td>
                                                <td class="px-4 py-4 whitespace-nowrap text-xs font-mono text-gray-500">{{ $a->nta ?? $a->nisn ?? '-' }}</td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600 font-semibold">Kelas {{ $a->kelas }}</td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm font-bold text-emerald-800 uppercase">{{ $a->tingkatan_tku ?? '-' }}</td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm">
                                                    @if($a->jabatan === 'pinru')
                                                        <span class="px-3 py-1 inline-flex text-[10px] leading-none font-black uppercase rounded-full bg-red-600 text-white shadow-sm">Pinru</span>
                                                    @elseif($a->jabatan === 'wapinru')
                                                        <span class="px-3 py-1 inline-flex text-[10px] leading-none font-black uppercase rounded-full bg-amber-500 text-white shadow-sm">Wapinru</span>
                                                    @else
                                                        <span class="px-3 py-1 inline-flex text-[10px] leading-none font-black uppercase rounded-full bg-emerald-600 text-white shadow-sm">Anggota</span>
                                                    @endif
                                                </td>
                                                <td class="px-5 py-4 whitespace-nowrap text-sm text-center">
                                                    <div class="flex items-center justify-center gap-2">
                                                        <a href="{{ route('peserta.anggota.edit', $a->id) }}"
                                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold rounded-lg border border-amber-300 transition shadow-sm"
                                                            title="Edit Anggota & Foto">
                                                            <i data-lucide="edit-3" class="w-3.5 h-3.5 text-amber-700"></i>
                                                            <span>Edit</span>
                                                        </a>
                                                        <form action="{{ route('peserta.anggota.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota \'{{ $a->nama }}\'?');" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-bold rounded-lg border border-red-200 transition shadow-sm"
                                                                title="Hapus Anggota">
                                                                <i data-lucide="trash-2" class="w-3.5 h-3.5 text-red-600"></i>
                                                                <span>Hapus</span>
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

                    <!-- Section: Portofolio & Karya Lomba Season II (Full Width) -->
                    <div class="card-scout p-6 rounded-2xl md:col-span-2">
                        <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                            <div class="bg-amber-100 p-3 rounded-2xl text-brand-amber shrink-0">
                                <i data-lucide="palette" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-brand-forest uppercase">Portofolio Lomba & Karya Regu</h2>
                                <p class="text-xs text-gray-500">Unggah dan kelola dokumentasi karya resmi Spencerone Camp Season II</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Foto Bersama Regu (Untuk Voting Terfavorit) -->
                            <div class="bg-white rounded-2xl border-2 border-emerald-500/60 shadow-md overflow-hidden flex flex-col group hover:shadow-lg transition">
                                <div class="bg-gradient-to-r from-emerald-800 to-teal-900 p-4 text-white">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-black uppercase tracking-wider bg-amber-400 text-emerald-950 px-2 py-0.5 rounded shadow-xs font-mono">
                                            VOTING TERFAVORIT
                                        </span>
                                        <i data-lucide="award" class="w-5 h-5 text-amber-300"></i>
                                    </div>
                                    <h3 class="font-black text-base mt-2 text-white leading-tight">Foto Resmi Regu</h3>
                                    <p class="text-[11px] text-emerald-200 mt-0.5">Tampil di Live Display & ajang vote favorit</p>
                                </div>
                                <div class="p-4 flex-1 flex flex-col justify-between space-y-4">
                                    @if ($regu->foto_regu_path)
                                        <div>
                                            <div class="aspect-[4/3] bg-gray-100 rounded-xl overflow-hidden mb-2 border border-emerald-200 relative group/img">
                                                <img src="{{ asset('storage/' . $regu->foto_regu_path) }}" alt="Foto Regu {{ $regu->nama_regu }}" 
                                                    onclick="openModal('{{ asset('storage/' . $regu->foto_regu_path) }}', 'Foto Regu - {{ addslashes($regu->nama_regu) }}')"
                                                    class="w-full h-full object-cover cursor-pointer group-hover/img:scale-105 transition duration-300">
                                                <div class="absolute bottom-2 right-2 bg-black/60 backdrop-blur-xs text-white text-[10px] px-2 py-0.5 rounded-full font-bold flex items-center gap-1">
                                                    <i data-lucide="eye" class="w-3 h-3"></i> Klik lihat
                                                </div>
                                            </div>
                                            @if($regu->foto_regu_caption)
                                                <p class="text-xs text-gray-700 italic bg-emerald-50/60 p-2 rounded-lg border border-emerald-100">"{{ $regu->foto_regu_caption }}"</p>
                                            @endif
                                            <div class="mt-2 flex items-center justify-between text-[11px] text-gray-500 font-medium">
                                                <span class="flex items-center gap-1 text-emerald-700 font-bold">
                                                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-600"></i> Siap divote di panggung
                                                </span>
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            <!-- Form ganti foto -->
                                            <form action="{{ route('peserta.foto-regu.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                                                @csrf
                                                <label class="block w-full text-center py-2 bg-emerald-50 hover:bg-emerald-100 text-brand-forest font-bold text-xs rounded-xl cursor-pointer transition border border-emerald-300">
                                                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5 inline mr-1"></i> Ganti Foto Regu
                                                    <input type="file" name="foto_regu" accept="image/*" class="hidden" onchange="this.form.submit()">
                                                </label>
                                            </form>
                                            <!-- Form hapus foto -->
                                            <form action="{{ route('peserta.foto-regu.delete') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto regu ini? Foto tidak akan tampil lagi di ajang voting.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full py-2 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold rounded-xl transition flex items-center justify-center gap-1.5">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus Foto
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <form method="POST" action="{{ route('peserta.foto-regu.upload') }}" enctype="multipart/form-data" class="space-y-3">
                                            @csrf
                                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-emerald-300 rounded-xl cursor-pointer hover:bg-emerald-50/50 transition">
                                                <i data-lucide="users" class="w-7 h-7 text-emerald-700 mb-1"></i>
                                                <span class="text-xs font-bold text-gray-700">Pilih Foto Seluruh Anggota</span>
                                                <span class="text-[10px] text-gray-400 mt-0.5">Format JPG/PNG/WebP, maks 10MB</span>
                                                <input type="file" name="foto_regu" accept="image/*" class="hidden" required>
                                            </label>
                                            <input type="text" name="foto_regu_caption" placeholder="Slogan / kata-kata regu (opsional)..." class="w-full text-xs rounded-lg border-gray-200 p-2 focus:ring-emerald-500 focus:border-emerald-500">
                                            <button type="submit" class="w-full py-2.5 bg-emerald-700 text-white text-xs font-bold rounded-xl hover:bg-emerald-800 transition shadow-sm flex items-center justify-center gap-1.5">
                                                <i data-lucide="upload" class="w-4 h-4 text-amber-300"></i> Upload Foto Regu
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <!-- Hasta Karya Card (Season II Spotlight) -->
                            <div class="bg-white rounded-2xl border-2 border-brand-amber/40 shadow-sm overflow-hidden flex flex-col group hover:shadow-md transition">
                                <div class="bg-gradient-to-r from-brand-forest to-emerald-900 p-4 text-white">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-black uppercase tracking-wider bg-brand-amber px-2 py-0.5 rounded text-white">
                                            Lomba Utama
                                        </span>
                                        <i data-lucide="hammer" class="w-5 h-5 text-amber-300"></i>
                                    </div>
                                    <h3 class="font-black text-base mt-2 text-white leading-tight">Hasta Karya: Scout Craftsmanship</h3>
                                </div>
                                <div class="p-4 flex-1 flex flex-col justify-between space-y-4">
                                    @php
                                        $hastaKarya = $regu->karyaLombas->firstWhere('mata_lomba_slug', 'hasta-karya');
                                    @endphp
                                    @if($hastaKarya)
                                        <div>
                                            <div class="aspect-video bg-gray-100 rounded-xl overflow-hidden mb-2">
                                                @if($hastaKarya->foto_utama_path)
                                                    <img src="{{ asset('storage/' . $hastaKarya->foto_utama_path) }}" alt="{{ $hastaKarya->judul_karya }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-xs text-gray-400">Tanpa Foto</div>
                                                @endif
                                            </div>
                                            <h4 class="font-bold text-sm text-gray-900 truncate">{{ $hastaKarya->judul_karya }}</h4>
                                            <p class="text-xs text-gray-500 line-clamp-2 mt-1">{{ $hastaKarya->deskripsi_filosofi ?? $hastaKarya->fungsi_guna }}</p>
                                        </div>
                                        <a href="{{ route('peserta.hasta-karya') }}" class="w-full py-2.5 bg-emerald-50 hover:bg-emerald-100 text-brand-forest font-bold text-xs rounded-xl text-center transition flex items-center justify-center gap-1.5 border border-emerald-200">
                                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit Portofolio
                                        </a>
                                    @else
                                        <div class="py-6 text-center text-gray-400 my-auto">
                                            <i data-lucide="file-question" class="w-10 h-10 mx-auto mb-2 opacity-40"></i>
                                            <p class="text-xs">Belum ada portofolio diajukan</p>
                                        </div>
                                        <a href="{{ route('peserta.hasta-karya') }}" class="w-full py-2.5 bg-brand-forest hover:bg-emerald-900 text-white font-bold text-xs rounded-xl text-center transition flex items-center justify-center gap-1.5 shadow-sm">
                                            <i data-lucide="plus-circle" class="w-3.5 h-3.5 text-brand-amber"></i> Buat Portofolio Baru
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Tapak Kemah -->
                            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col group hover:shadow-md transition">
                                <div class="bg-brand-forest p-4 text-white">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-bold uppercase tracking-wider bg-white/10 px-2 py-0.5 rounded text-white">
                                            Giat Alam
                                        </span>
                                        <i data-lucide="tent" class="w-5 h-5 text-amber-300"></i>
                                    </div>
                                    <h3 class="font-bold text-base mt-2 text-white leading-tight">Tapak Kemah: Satya Bhuana</h3>
                                </div>
                                <div class="p-4 flex-1 flex flex-col justify-between space-y-4">
                                    @if ($regu->foto_tenda_path)
                                        <div>
                                            <div class="aspect-video bg-gray-100 rounded-xl overflow-hidden mb-2">
                                                <img src="{{ asset('storage/' . $regu->foto_tenda_path) }}" alt="Tapak Kemah" class="w-full h-full object-cover">
                                            </div>
                                            <p class="text-xs text-gray-600 line-clamp-2">{{ $regu->tenda_description ?? 'Kavling tenda resmi terverifikasi.' }}</p>
                                        </div>
                                        <form action="{{ route('peserta.photo.delete') }}" method="POST" onsubmit="return confirm('Hapus foto ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="mata_lomba_slug" value="tapak-kemah">
                                            <button type="submit" class="w-full py-2 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold rounded-xl transition flex items-center justify-center gap-1.5">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus Foto
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('peserta.upload.photo') }}" enctype="multipart/form-data" class="space-y-3">
                                            @csrf
                                            <input type="hidden" name="mata_lomba_slug" value="tapak-kemah">
                                            <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                                                <i data-lucide="upload-cloud" class="w-6 h-6 text-brand-forest mb-1"></i>
                                                <span class="text-[11px] font-bold text-gray-600">Pilih Foto Kavling</span>
                                                <span class="text-[9px] text-gray-400">JPG/PNG maks 10MB</span>
                                                <input type="file" name="photo" accept="image/*" class="hidden">
                                            </label>
                                            <textarea name="description" rows="2" placeholder="Catatan kavling/tenda..." class="w-full text-xs rounded-lg border-gray-200 p-2"></textarea>
                                            <button type="submit" class="w-full py-2 bg-brand-forest text-white text-xs font-bold rounded-xl hover:bg-emerald-900 transition">
                                                Upload Foto Tenda
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <!-- Masak Rimba ("Isi Piringku") -->
                            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col group hover:shadow-md transition">
                                <div class="bg-brand-forest p-4 text-white">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-bold uppercase tracking-wider bg-white/10 px-2 py-0.5 rounded text-white">
                                            Giat Alam
                                        </span>
                                        <i data-lucide="chef-hat" class="w-5 h-5 text-amber-300"></i>
                                    </div>
                                    <h3 class="font-bold text-base mt-2 text-white leading-tight">Masak Rimba: Cita Rasa Rimba</h3>
                                </div>
                                <div class="p-4 flex-1 flex flex-col justify-between space-y-4">
                                    @if ($regu->foto_masakan_path)
                                        <div>
                                            <div class="aspect-video bg-gray-100 rounded-xl overflow-hidden mb-2">
                                                <img src="{{ asset('storage/' . $regu->foto_masakan_path) }}" alt="Masakan Rimba" class="w-full h-full object-cover">
                                            </div>
                                            <p class="text-xs text-gray-600 line-clamp-2">{{ $regu->masakan_description ?? 'Sajian rimba bergizi seimbang.' }}</p>
                                        </div>
                                        <form action="{{ route('peserta.photo.delete') }}" method="POST" onsubmit="return confirm('Hapus foto ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="mata_lomba_slug" value="masak-konvensional">
                                            <button type="submit" class="w-full py-2 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold rounded-xl transition flex items-center justify-center gap-1.5">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus Foto
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('peserta.upload.photo') }}" enctype="multipart/form-data" class="space-y-3">
                                            @csrf
                                            <input type="hidden" name="mata_lomba_slug" value="masak-konvensional">
                                            <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                                                <i data-lucide="upload-cloud" class="w-6 h-6 text-brand-forest mb-1"></i>
                                                <span class="text-[11px] font-bold text-gray-600">Pilih Foto Sajian</span>
                                                <span class="text-[9px] text-gray-400">JPG/PNG maks 10MB</span>
                                                <input type="file" name="photo" accept="image/*" class="hidden">
                                            </label>
                                            <textarea name="description" rows="2" placeholder="Komposisi gizi & menu rimba..." class="w-full text-xs rounded-lg border-gray-200 p-2"></textarea>
                                            <button type="submit" class="w-full py-2 bg-brand-forest text-white text-xs font-bold rounded-xl hover:bg-emerald-900 transition">
                                                Upload Sajian Masak
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            @if(isset($lombaWajibFoto))
                                @foreach($lombaWajibFoto as $lombaWajib)
                                    @if(!in_array($lombaWajib->slug, ['hasta-karya', 'tapak-kemah', 'masak-konvensional', 'masak-rimba']))
                                        @php
                                            $customKarya = $regu->karyaLombas->firstWhere('mata_lomba_slug', $lombaWajib->slug);
                                        @endphp
                                        <div class="bg-white rounded-2xl border-2 border-purple-200/80 shadow-sm overflow-hidden flex flex-col group hover:shadow-md transition">
                                            <div class="bg-gradient-to-r from-purple-800 to-indigo-900 p-4 text-white">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-[10px] font-black uppercase tracking-wider bg-purple-500/40 px-2 py-0.5 rounded text-white border border-purple-300/30">
                                                        Wajib Upload Foto
                                                    </span>
                                                    <i data-lucide="camera" class="w-5 h-5 text-purple-300"></i>
                                                </div>
                                                <h3 class="font-bold text-base mt-2 text-white leading-tight">{{ $lombaWajib->nama }}</h3>
                                            </div>
                                            <div class="p-4 flex-1 flex flex-col justify-between space-y-4">
                                                @if($customKarya && $customKarya->foto_utama_path)
                                                    <div>
                                                        <div class="aspect-video bg-gray-100 rounded-xl overflow-hidden mb-2">
                                                            <img src="{{ asset('storage/' . $customKarya->foto_utama_path) }}" alt="{{ $lombaWajib->nama }}" class="w-full h-full object-cover">
                                                        </div>
                                                        <p class="text-xs text-gray-600 line-clamp-2">{{ $customKarya->deskripsi_filosofi ?? 'Foto karya berhasil diunggah.' }}</p>
                                                    </div>
                                                    <form action="{{ route('peserta.photo.delete') }}" method="POST" onsubmit="return confirm('Hapus foto ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="mata_lomba_slug" value="{{ $lombaWajib->slug }}">
                                                        <button type="submit" class="w-full py-2 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold rounded-xl transition flex items-center justify-center gap-1.5">
                                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus Foto
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('peserta.upload.photo') }}" enctype="multipart/form-data" class="space-y-3">
                                                        @csrf
                                                        <input type="hidden" name="mata_lomba_slug" value="{{ $lombaWajib->slug }}">
                                                        
                                                        @if($lombaWajib->instruksi_upload_foto)
                                                            <div class="p-2.5 bg-purple-50 rounded-xl border border-purple-200/60 text-[11px] text-purple-900 leading-snug">
                                                                <strong>Instruksi:</strong> {{ $lombaWajib->instruksi_upload_foto }}
                                                            </div>
                                                        @endif

                                                        <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-purple-200 rounded-xl cursor-pointer hover:bg-purple-50/50 transition">
                                                            <i data-lucide="upload-cloud" class="w-6 h-6 text-purple-600 mb-1"></i>
                                                            <span class="text-[11px] font-bold text-gray-600 text-center px-2">Pilih Foto Karya (Maks {{ $lombaWajib->max_foto ?? 1 }} foto)</span>
                                                            <span class="text-[9px] text-gray-400">JPG/PNG maks 10MB</span>
                                                            <input type="file" name="photo" accept="image/*" class="hidden" required>
                                                        </label>
                                                        <textarea name="description" rows="2" placeholder="Keterangan karya..." class="w-full text-xs rounded-lg border-gray-200 p-2"></textarea>
                                                        <button type="submit" class="w-full py-2 bg-purple-700 text-white text-xs font-bold rounded-xl hover:bg-purple-800 transition">
                                                            Upload Foto {{ $lombaWajib->nama }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Modal Upload Foto ID Card Cepat -->
                    <div id="quickPhotoModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="quickPhotoModalTitle" role="dialog" aria-modal="true">
                        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                            <div class="fixed inset-0 bg-gray-500/75 backdrop-blur-xs transition-opacity" onclick="closePhotoModal()"></div>
                            <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100">
                                <div class="bg-gradient-to-r from-brand-forest to-emerald-900 p-4 text-white flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="camera" class="w-5 h-5 text-amber-400"></i>
                                        <h3 class="font-black text-sm uppercase tracking-wider text-white" id="quickPhotoModalTitle">Upload Foto ID Card</h3>
                                    </div>
                                    <button type="button" class="text-white/80 hover:text-white" onclick="closePhotoModal()">
                                        <i data-lucide="x" class="w-5 h-5"></i>
                                    </button>
                                </div>
                                <form id="quickPhotoForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                                    @csrf
                                    <div>
                                        <p class="text-xs text-gray-500 mb-0.5">Nama Anggota Regu:</p>
                                        <p class="text-sm font-black text-brand-forest" id="quickPhotoMemberName">-</p>
                                    </div>

                                    <div class="flex flex-col items-center justify-center gap-3 p-4 bg-amber-50/50 border border-amber-200 rounded-xl">
                                        <div class="w-24 h-32 rounded-xl bg-white border-2 border-dashed border-amber-300 overflow-hidden flex items-center justify-center shadow-inner relative">
                                            <img id="quickPhotoPreview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                                            <div id="quickPhotoPlaceholder" class="text-center p-2">
                                                <i data-lucide="user" class="w-8 h-8 text-amber-300 mx-auto mb-1"></i>
                                                <span class="text-[10px] font-bold text-gray-400">Pasfoto 3x4</span>
                                            </div>
                                        </div>
                                        <label class="px-4 py-2 bg-white border border-amber-300 hover:bg-amber-100 text-amber-800 text-xs font-bold rounded-xl cursor-pointer transition shadow-2xs flex items-center gap-1.5">
                                            <i data-lucide="upload" class="w-4 h-4 text-amber-700"></i>
                                            <span>Pilih Foto dari Perangkat</span>
                                            <input type="file" name="foto" id="quickFileInput" accept="image/jpeg,image/png,image/jpg,image/webp" required class="hidden" onchange="previewQuickPhoto(this)">
                                        </label>
                                        <p class="text-[10px] text-gray-500 text-center">Format JPG, PNG, atau WEBP maks 3MB (pasfoto resmi seragam pramuka).</p>
                                    </div>

                                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                                        <button type="button" onclick="closePhotoModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-100 transition">
                                            Batal
                                        </button>
                                        <button type="submit" class="px-5 py-2 bg-brand-forest hover:bg-emerald-900 text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5 shadow-sm">
                                            <i data-lucide="save" class="w-3.5 h-3.5 text-amber-400"></i>
                                            Simpan Foto
                                        </button>
                                    </div>
                                </form>
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
                        function openPhotoModal(anggotaId, anggotaNama, currentPhotoUrl) {
                            const modal = document.getElementById('quickPhotoModal');
                            const form = document.getElementById('quickPhotoForm');
                            const nameElem = document.getElementById('quickPhotoMemberName');
                            const preview = document.getElementById('quickPhotoPreview');
                            const placeholder = document.getElementById('quickPhotoPlaceholder');
                            const fileInput = document.getElementById('quickFileInput');

                            nameElem.innerText = anggotaNama;
                            form.action = `/peserta/anggota/${anggotaId}/upload-foto`;
                            fileInput.value = '';

                            if (currentPhotoUrl) {
                                preview.src = currentPhotoUrl;
                                preview.classList.remove('hidden');
                                placeholder.classList.add('hidden');
                            } else {
                                preview.src = '';
                                preview.classList.add('hidden');
                                placeholder.classList.remove('hidden');
                            }

                            modal.classList.remove('hidden');
                            if (typeof lucide !== 'undefined') lucide.createIcons();
                        }

                        function closePhotoModal() {
                            document.getElementById('quickPhotoModal').classList.add('hidden');
                        }

                        function previewQuickPhoto(input) {
                            if (input.files && input.files[0]) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    const preview = document.getElementById('quickPhotoPreview');
                                    const placeholder = document.getElementById('quickPhotoPlaceholder');
                                    preview.src = e.target.result;
                                    preview.classList.remove('hidden');
                                    placeholder.classList.add('hidden');
                                };
                                reader.readAsDataURL(input.files[0]);
                            }
                        }

                        function openModal(imageSrc, title) {
                            document.getElementById('modal-image').src = imageSrc;
                            document.getElementById('modal-title').innerText = title;
                            document.getElementById('imageModal').classList.remove('hidden');
                        }

                        function closeModal() {
                            document.getElementById('imageModal').classList.add('hidden');
                        }

                        // Add filename preview to upload forms
                        document.addEventListener('change', function(e) {
                            if (e.target && e.target.type === 'file' && e.target.name === 'photo') {
                                const file = e.target.files[0];
                                if (file) {
                                    const label = e.target.closest('label');
                                    if (label) {
                                        const textElement = label.querySelector('p.text-gray-500');
                                        if (textElement) {
                                            textElement.innerHTML = `<span class="font-semibold text-green-600">File terpilih:</span> ${file.name}`;
                                        }
                                        const iconContainer = label.querySelector('div.bg-white');
                                        if (iconContainer) {
                                            iconContainer.classList.add('bg-green-50');
                                            const icon = iconContainer.querySelector('i');
                                            if (icon) {
                                                icon.setAttribute('data-lucide', 'check-circle-2');
                                                icon.classList.remove('text-scout-primary');
                                                icon.classList.add('text-green-600');
                                                if (typeof lucide !== 'undefined') {
                                                    lucide.createIcons();
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    </script>

                    <!-- Section: Rekap Nilai (Full Width) -->
                    <div class="card-scout p-6 rounded-lg md:col-span-2">
                        <div class="flex items-center mb-4">
                            <div class="bg-emerald-100 p-2 rounded-full mr-3 text-brand-forest">
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
                                    <div class="bg-scout-light px-4 py-3 flex justify-between items-center border-b border-gray-200 font-bold bg-gradient-to-r from-scout-light to-white">
                                        <span class="text-gray-700 text-xs sm:text-sm uppercase tracking-wider">Total Nilai Lomba</span>
                                        <span class="text-scout-primary text-base sm:text-lg font-black">{{ number_format($scores->sum('nilai'), 2) }}</span>
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