@extends('layouts.main')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Verifikasi Peserta Babak 2</h1>
                <a href="{{ route('juri.cerdas-cermat.index') }}" class="text-gray-600 hover:text-gray-900 border border-gray-300 bg-white px-4 py-2 rounded-md font-medium shadow-sm">
                    &larr; Kembali
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p class="mb-4 text-gray-600">Berikut adalah daftar 5 besar regu dari Babak 1. Silakan verifikasi regu yang berhak melaju ke Babak 2.</p>
                    
                    <div class="overflow-x-auto">
                        <!-- Mobile View -->
                        <div class="md:hidden divide-y divide-gray-200">
                            @forelse($qualifiers as $index => $session)
                                <div class="p-4 bg-white hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center space-x-3">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-scout-primary text-white font-black">
                                                {{ $index + 1 }}
                                            </span>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $session->reguProfile->nama_regu }}</div>
                                                <div class="text-xs text-gray-500">Regu {{ $session->reguProfile->nomor_regu }}</div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-0.5">Skor B.1</div>
                                            <span class="text-lg font-black text-scout-primary">{{ $session->score_round_1 }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-gray-50 p-3 rounded-lg border border-gray-100">
                                        <div class="flex items-center">
                                            @if($session->is_verified_round_2)
                                                <span class="px-2 py-1 inline-flex text-xs font-bold rounded bg-green-100 text-green-800">
                                                    <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i> Terverifikasi
                                                </span>
                                            @else
                                                <span class="px-2 py-1 inline-flex text-xs font-bold rounded bg-red-100 text-red-800">
                                                    <i data-lucide="x-circle" class="w-4 h-4 mr-1"></i> Belum Verifikasi
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="w-full sm:w-auto">
                                            @if(!$session->is_verified_round_2)
                                                <form action="{{ route('juri.cerdas-cermat.verify', $session->id) }}" method="POST" class="w-full">
                                                    @csrf
                                                    <button type="submit" class="w-full flex justify-center items-center text-indigo-700 bg-indigo-50 border border-indigo-200 px-4 py-2 rounded-md font-bold transition hover:bg-indigo-100" onclick="return confirm('Verifikasi regu ini untuk lanjut ke Babak 2?')">
                                                        Verifikasi
                                                    </button>
                                                </form>
                                            @else
                                                <div class="text-gray-400 text-sm font-medium text-center italic cursor-not-allowed">
                                                    Selesai
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-gray-500">Belum ada data skor Babak 1.</div>
                            @endforelse
                        </div>

                        <!-- Desktop View -->
                        <table class="hidden md:table min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peringkat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Regu</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor Babak 1</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Verifikasi</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($qualifiers as $index => $session)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $session->reguProfile->nama_regu }}</div>
                                            <div class="text-xs text-gray-500">Regu {{ $session->reguProfile->nomor_regu }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-scout-primary">
                                            {{ $session->score_round_1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($session->is_verified_round_2)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Terverifikasi
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Belum Diverifikasi
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if(!$session->is_verified_round_2)
                                                <form action="{{ route('juri.cerdas-cermat.verify', $session->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md font-bold transition hover:bg-indigo-100" onclick="return confirm('Verifikasi regu ini untuk lanjut ke Babak 2?')">
                                                        Verifikasi
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 cursor-not-allowed">
                                                    Sudah Diverifikasi
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data skor Babak 1.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
