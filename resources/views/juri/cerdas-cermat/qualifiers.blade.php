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
                    
                    <table class="min-w-full divide-y divide-gray-200">
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
@endsection
