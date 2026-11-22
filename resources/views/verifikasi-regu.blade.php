@extends('layouts.main')

@section('title', 'Verifikasi Profil Regu - ' . $regu->nama_regu)

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">

    <!-- Card Container -->
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-200">

        <!-- Header -->
        <div class="bg-gradient-to-r from-brand-forest to-emerald-900 text-white p-6 sm:p-8 relative">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/season2/logo-season2.jpg') }}" alt="Logo Season II"
                        class="w-16 h-16 rounded-full object-cover border-2 border-amber-400 shadow-md">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-amber-400 text-brand-forest mb-1">
                            <i data-lucide="badge-check" class="w-3.5 h-3.5"></i> Regu Resmi Terverifikasi
                        </span>
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white uppercase">{{ $regu->nama_regu }}</h1>
                        <p class="text-xs text-emerald-100 font-medium">Gudep 03.059 - 03.060 SMPN 1 Cerbon • Barito Kuala</p>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-xs border border-white/20 rounded-2xl p-3 text-center sm:text-right shrink-0">
                    <span class="text-[10px] uppercase font-bold text-amber-300 block">Nomor Regu / Kavling</span>
                    <span class="text-2xl font-black text-white block">{{ $regu->nomor_regu }}</span>
                    <span class="text-xs text-white/80 font-bold block">{{ $regu->nomor_kavling ?? 'KAV-01' }}</span>
                </div>
            </div>
        </div>

        <!-- Detail Kontingen & Anggota -->
        <div class="p-6 sm:p-8 space-y-6">
            
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-3 flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4 text-brand-forest"></i> Susunan Anggota Regu ({{ $anggota->count() }} Orang)
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($anggota as $i => $m)
                        <div class="p-3.5 rounded-xl border border-gray-100 bg-gray-50/50 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="w-6 h-6 rounded-full bg-brand-forest/10 text-brand-forest flex items-center justify-center text-xs font-black">
                                    {{ $i + 1 }}
                                </span>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 leading-tight">{{ $m->nama }}</h4>
                                    <div class="flex items-center gap-2 text-[10px] text-gray-500 mt-0.5">
                                        <span>Kelas {{ $m->kelas }}</span>
                                        <span>•</span>
                                        <span class="uppercase">TKU: {{ $m->tingkatan_tku ?? '-' }}</span>
                                        @if($m->nisn)
                                            <span>•</span>
                                            <span class="font-mono">NISN: {{ $m->nisn }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase
                                {{ $m->jabatan === 'pinru' ? 'bg-red-100 text-red-800' : ($m->jabatan === 'wapinru' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800') }}">
                                {{ $m->jabatan }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Karya Lomba Terdaftar -->
            <div class="pt-6 border-t border-gray-100">
                <h3 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-3 flex items-center gap-2">
                    <i data-lucide="trophy" class="w-4 h-4 text-brand-amber"></i> Portofolio Karya Lomba
                </h3>

                @if($regu->karyaLombas->isEmpty())
                    <div class="p-4 bg-gray-50 rounded-xl text-center text-xs text-gray-400 italic">
                        Belum ada karya lomba yang diunggah oleh regu ini.
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($regu->karyaLombas as $k)
                            <div class="p-4 rounded-xl border border-gray-100 bg-emerald-50/30 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-black uppercase text-brand-forest">{{ $k->judul_karya }}</span>
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-100 text-emerald-800 uppercase">
                                        {{ $k->mata_lomba_slug }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600 line-clamp-2">{{ $k->deskripsi_filosofi ?? $k->fungsi_guna }}</p>
                                @if($k->foto_utama_path)
                                    <img src="{{ asset('storage/' . $k->foto_utama_path) }}" alt="{{ $k->judul_karya }}" class="w-full h-32 object-cover rounded-lg">
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="pt-6 border-t border-gray-100 text-center">
                <a href="{{ route('home') }}" class="text-xs font-bold text-brand-forest hover:text-brand-amber underline">
                    &larr; Kembali ke Beranda Spencerone Camp
                </a>
            </div>

        </div>

    </div>

</div>
@endsection
