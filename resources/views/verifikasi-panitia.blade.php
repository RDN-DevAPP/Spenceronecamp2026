@extends('layouts.main')

@section('title', 'Verifikasi ID Card Panitia - ' . $panitia->nama)

@section('content')
<div class="min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg mx-auto bg-white rounded-3xl shadow-xl border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-brand-forest to-emerald-900 p-6 text-white text-center">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-amber-400 text-emerald-950 mb-2">
                <i data-lucide="badge-check" class="w-3.5 h-3.5"></i> Panitia Resmi Terverifikasi
            </span>
            <h1 class="text-xl font-black uppercase text-white tracking-tight">Spencerone Camp Season II</h1>
            <p class="text-xs text-emerald-100 mt-0.5">Gudep 03.059 - 03.060 SMP Negeri 1 Cerbon</p>
        </div>

        <div class="p-6 sm:p-8 text-center space-y-5">
            <div class="w-28 h-36 mx-auto rounded-2xl overflow-hidden border-2 border-brand-forest bg-emerald-50 flex items-center justify-center shadow-md">
                @if($panitia->foto_path)
                    <img src="{{ asset('storage/' . $panitia->foto_path) }}" alt="{{ $panitia->nama }}" class="w-full h-full object-cover">
                @else
                    <i data-lucide="user" class="w-12 h-12 text-emerald-700/50"></i>
                @endif
            </div>

            <div>
                <h2 class="text-2xl font-black text-brand-forest uppercase">{{ $panitia->nama }}</h2>
                <div class="inline-block mt-2 px-3 py-1 rounded-full bg-amber-100 text-amber-950 border border-amber-300 text-xs font-black uppercase tracking-wider">
                    {{ $panitia->jabatan }}
                </div>
            </div>

            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-200 text-xs space-y-2.5 text-left">
                @if($panitia->divisi)
                    <div class="flex justify-between gap-2 pb-2 border-b border-gray-200">
                        <span class="font-bold text-gray-500 uppercase">Bidang / Divisi</span>
                        <span class="font-bold text-gray-900 text-right">{{ $panitia->divisi }}</span>
                    </div>
                @endif
                <div class="flex justify-between gap-2 pb-2 border-b border-gray-200">
                    <span class="font-bold text-gray-500 uppercase">Asal / Pangkalan</span>
                    <span class="font-bold text-gray-900 text-right">{{ $panitia->asal_instansi ?: 'SMP Negeri 1 Cerbon' }}</span>
                </div>
                <div class="flex justify-between gap-2">
                    <span class="font-bold text-gray-500 uppercase">Status</span>
                    <span class="font-black text-emerald-700 text-right">Panitia Aktif</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
