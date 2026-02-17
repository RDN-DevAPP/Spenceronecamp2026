@extends('layouts.peserta')

@section('title', 'Babak 1 - Cerdas Cermat')

@section('content')
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden border border-scout-secondary">
            <div class="bg-scout-primary px-6 py-4 border-b border-scout-secondary flex items-center justify-between">
                <div class="flex items-center">
                    <h2 class="text-xl font-bold text-white">Babak 1: Pilihan Ganda</h2>
                </div>
                <span class="bg-white/20 text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">
                    Status: Menunggu
                </span>
            </div>

            <div class="p-8 text-center">
                <div
                    class="mb-6 inline-flex items-center justify-center w-20 h-20 rounded-full bg-scout-light text-scout-primary mb-6">
                    <i data-lucide="clock" class="w-10 h-10"></i>
                </div>

                <h3 class="text-2xl font-bold text-gray-900 mb-2">Persiapan Babak Pertama</h3>
                <p class="text-gray-600 mb-8 max-w-lg mx-auto">
                    Tim Anda telah terdaftar. Babak pertama akan segera dimulai. Pastikan seluruh anggota tim siap di depan
                    perangkat masing-masing.
                </p>

                <div class="bg-gray-50 rounded-lg p-6 max-w-md mx-auto mb-8 text-left border border-gray-200">
                    <h4 class="font-bold text-gray-800 mb-3 border-b pb-2">Anggota Tim:</h4>
                    <ul class="space-y-2">
                        <li class="flex items-center text-gray-700">
                            <i data-lucide="user" class="w-4 h-4 mr-2 text-scout-primary"></i>
                            {{ $session->name_1 }}
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i data-lucide="user" class="w-4 h-4 mr-2 text-scout-primary"></i>
                            {{ $session->name_2 }}
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i data-lucide="user" class="w-4 h-4 mr-2 text-scout-primary"></i>
                            {{ $session->name_3 }}
                        </li>
                    </ul>
                </div>

                <div class="flex justify-center space-x-4">
                    <a href="{{ route('peserta.cerdas-cermat.round-1') }}"
                        class="bg-scout-accent text-scout-primary px-8 py-3 rounded-lg font-bold hover:bg-yellow-500 transition shadow-lg flex items-center transform hover:scale-105 duration-200">
                        <i data-lucide="play-circle" class="w-5 h-5 mr-2"></i>
                        Mulai Babak 1
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection