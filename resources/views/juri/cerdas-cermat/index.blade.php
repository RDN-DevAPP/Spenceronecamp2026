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
                    <div class="overflow-x-auto">
                        <!-- Mobile View -->
                        <div class="md:hidden divide-y divide-gray-200">
                            @forelse($sessions as $session)
                                <div class="p-4 bg-white hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <div class="text-sm font-bold text-gray-900">{{ $session->reguProfile->nama_regu }}</div>
                                            <div class="text-xs text-gray-500">Regu {{ $session->reguProfile->nomor_regu }}</div>
                                        </div>
                                        <span class="px-2 py-1 inline-flex text-[10px] leading-none font-semibold rounded-full bg-blue-100 text-blue-800 tracking-wide uppercase">
                                            {{ str_replace('_', ' ', $session->status) }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 mt-3 mb-4">
                                        <div class="bg-gray-50 p-2 rounded-md border border-gray-100 text-center">
                                            <div class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Babak 2 (Isian)</div>
                                            <div class="text-sm font-black text-scout-primary">{{ $session->score_round_2 ?? 0 }} <span class="text-xs font-normal text-gray-500">Poin</span></div>
                                        </div>
                                        <div class="bg-gray-50 p-2 rounded-md border border-gray-100 text-center">
                                            <div class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Babak 3 (Uraian)</div>
                                            <div class="text-sm font-black text-scout-primary">{{ $session->score_round_3 ?? 0 }} <span class="text-xs font-normal text-gray-500">Poin</span></div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col space-y-2">
                                        @if(in_array($session->status, ['round_2_done', 'round_3_ongoing', 'round_3_done', 'finished']))
                                            <a href="{{ route('juri.cerdas-cermat.show', [$session->id, 2]) }}" class="text-center font-bold text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-2 rounded-md transition-colors text-sm border border-indigo-100">
                                                Nilai Babak 2
                                            </a>
                                        @endif
                                        
                                        @if(in_array($session->status, ['round_3_done', 'finished']))
                                            <a href="{{ route('juri.cerdas-cermat.show', [$session->id, 3]) }}" class="text-center font-bold text-pink-600 hover:text-pink-900 bg-pink-50 px-3 py-2 rounded-md transition-colors text-sm border border-pink-100">
                                                Nilai Babak 3
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-gray-500">Belum ada sesi yang dapat dinilai.</div>
                            @endforelse
                        </div>

                        <!-- Desktop View -->
                        <table class="hidden md:table min-w-full divide-y divide-gray-200">
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
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 tracking-wide uppercase">
                                            {{ str_replace('_', ' ', $session->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-scout-primary">
                                        {{ $session->score_round_2 ?? 0 }} <span class="font-normal text-gray-500">Poin</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-scout-primary">
                                        {{ $session->score_round_3 ?? 0 }} <span class="font-normal text-gray-500">Poin</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        @if(in_array($session->status, ['round_2_done', 'round_3_ongoing', 'round_3_done', 'finished']))
                                            <a href="{{ route('juri.cerdas-cermat.show', [$session->id, 2]) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 font-bold rounded-md border border-indigo-100">Nilai B.2</a>
                                        @endif
                                        
                                        @if(in_array($session->status, ['round_3_done', 'finished']))
                                            <a href="{{ route('juri.cerdas-cermat.show', [$session->id, 3]) }}" class="text-pink-600 hover:text-pink-900 bg-pink-50 px-3 py-1 font-bold rounded-md border border-pink-100">Nilai B.3</a>
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
