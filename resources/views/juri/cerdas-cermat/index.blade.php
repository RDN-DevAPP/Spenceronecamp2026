@extends('layouts.main')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Penilaian Cerdas Cermat</h1>
        
        <div class="mb-6 flex justify-end">
             <a href="{{ route('juri.cerdas-cermat.qualifiers') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md font-bold hover:bg-indigo-700 transition shadow-md flex items-center">
                <i data-lucide="check-circle" class="mr-2 w-4 h-4"></i>
                Verifikasi Peserta Babak 2
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Regu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Babak 2 (Isian)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Babak 3 (Uraian)</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($sessions as $session)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ $session->reguProfile->nama_regu }}</div>
                                    <div class="text-sm text-gray-500">Regu {{ $session->reguProfile->nomor_regu }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $session->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $session->score_round_2 ?? 0 }} Poin
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $session->score_round_3 ?? 0 }} Poin
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    @if(in_array($session->status, ['round_2_done', 'round_3_ongoing', 'round_3_done', 'finished']))
                                        <a href="{{ route('juri.cerdas-cermat.show', [$session->id, 2]) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md">Nilai Babak 2</a>
                                    @endif
                                    
                                    @if(in_array($session->status, ['round_3_done', 'finished']))
                                        <a href="{{ route('juri.cerdas-cermat.show', [$session->id, 3]) }}" class="text-pink-600 hover:text-pink-900 bg-pink-50 px-3 py-1 rounded-md">Nilai Babak 3</a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada sesi yang dapat dinilai.</td>
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
