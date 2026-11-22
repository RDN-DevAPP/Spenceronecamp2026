@extends('layouts.main')

@section('title', 'Verifikasi Kartu Tanda Peserta - ' . $anggota->nama)

@section('content')
<div class="min-h-screen bg-[#F8FAF8] py-8 sm:py-12">
    <div class="max-w-xl mx-auto px-4 sm:px-6">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-200/80">
            
            {{-- Header --}}
            <div class="bg-gradient-to-r from-[#0B2317] to-[#1B4332] text-white p-6 text-center relative">
                <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-400 text-emerald-950 mb-2 shadow-xs">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                    <span>ID Card Resmi Terverifikasi</span>
                </div>
                <h1 class="text-xl font-black uppercase tracking-tight text-white">Spencerone Camp Season II</h1>
                <p class="text-xs text-emerald-200/90 font-medium mt-0.5">Gerakan Pramuka SMPN 1 Cerbon • Barito Kuala</p>
            </div>

            {{-- Body Info --}}
            <div class="p-6 sm:p-8 space-y-6">
                <div class="text-center">
                    <div class="relative w-28 h-28 mx-auto mb-3">
                        <div class="w-full h-full rounded-2xl bg-emerald-50 border-2 border-brand-forest overflow-hidden flex items-center justify-center shadow-inner">
                            @if($anggota->foto_path)
                                <img src="{{ asset('storage/' . $anggota->foto_path) }}" alt="{{ $anggota->nama }}" class="w-full h-full object-cover">
                            @else
                                <i data-lucide="user" class="w-12 h-12 text-emerald-800/40"></i>
                            @endif
                        </div>
                        <span class="absolute -bottom-2 inset-x-0 mx-auto w-fit px-3 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider text-white shadow-xs border border-white
                            {{ $anggota->jabatan === 'pinru' ? 'bg-rose-600' : ($anggota->jabatan === 'wapinru' ? 'bg-amber-600' : 'bg-emerald-700') }}">
                            {{ strtoupper($anggota->jabatan) }}
                        </span>
                    </div>

                    <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight mt-3">{{ $anggota->nama }}</h2>
                    @if($anggota->nisn)
                        <p class="text-xs font-mono font-bold text-gray-500">NISN: {{ $anggota->nisn }}</p>
                    @endif

                    <div class="flex items-center justify-center gap-2 mt-2">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold bg-amber-100 text-amber-900">
                            Kelas {{ $anggota->kelas }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold bg-emerald-100 text-emerald-900 uppercase">
                            TKU: {{ $anggota->tingkatan_tku ?? '-' }}
                        </span>
                    </div>
                </div>

                {{-- Regu Info Card --}}
                <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 space-y-3">
                    <div class="flex justify-between items-center pb-2 border-b border-gray-200/60">
                        <span class="text-xs font-bold text-gray-500 uppercase">Regu</span>
                        <span class="text-xs font-black text-brand-forest uppercase">{{ $regu->nama_regu }} ({{ ucfirst($regu->jenis) }})</span>
                    </div>
                    <div class="flex justify-between items-center pb-2 border-b border-gray-200/60">
                        <span class="text-xs font-bold text-gray-500 uppercase">Gugus Depan</span>
                        <span class="text-xs font-bold text-gray-800">{{ $regu->jenis === 'putri' ? 'Gudep 03.060' : 'Gudep 03.059' }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-2 border-b border-gray-200/60">
                        <span class="text-xs font-bold text-gray-500 uppercase">Pangkalan / Sekolah</span>
                        <span class="text-xs font-bold text-gray-800">{{ $regu->sekolah ?? 'SMPN 1 Cerbon' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-500 uppercase">Kavling Tenda</span>
                        <span class="text-xs font-mono font-bold text-amber-800">{{ $regu->nomor_kavling ?? 'Kavling Tenda' }}</span>
                    </div>
                </div>

                {{-- Verification Footer --}}
                <div class="text-center pt-2">
                    @if($anggota->verified_at)
                        <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs font-bold text-emerald-800 flex items-center justify-center gap-2">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i>
                            <span>Telah Check-In & Hadir di Bumi Perkemahan</span>
                        </div>
                    @else
                        <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs font-bold text-amber-800 flex items-center justify-center gap-2">
                            <i data-lucide="clock" class="w-4 h-4 text-amber-600"></i>
                            <span>Terdaftar Resmi • Menunggu Check-In Lokasi</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
