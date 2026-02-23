@extends('layouts.main')

@section('title', 'Kelola Peserta Cerdas Cermat - Admin')

@section('content')
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4 sm:gap-0">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Peserta Cerdas Cermat</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.cerdas-cermat.index') }}"
                        class="bg-scout-secondary text-scout-primary border-2 border-scout-primary px-4 py-2 rounded-md hover:bg-scout-secondary/80 transition shadow-md font-bold flex items-center">
                        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Kelola Soal
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-6 shadow-sm"
                    role="alert">
                    <span class="block sm:inline font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white shadow-lg border-2 border-scout-secondary rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <!-- Mobile View -->
                    <div class="md:hidden divide-y divide-gray-200">
                        @forelse($sessions as $session)
                            <div class="p-4 bg-white hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $session->reguProfile->nama_regu }}</div>
                                        <div class="text-xs text-gray-500">{{ $session->reguProfile->jenis }} - {{ $session->reguProfile->nomor_regu }}</div>
                                    </div>
                                    <span class="px-2 py-1 inline-flex text-[10px] leading-4 font-bold rounded-full bg-blue-100 text-blue-800 uppercase">
                                        {{ str_replace('_', ' ', $session->status) }}
                                    </span>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Peserta</div>
                                    <ul class="list-disc list-inside text-xs text-gray-700">
                                        <li>{{ $session->name_1 }}</li>
                                        <li>{{ $session->name_2 }}</li>
                                        <li>{{ $session->name_3 }}</li>
                                    </ul>
                                </div>
                                
                                <div class="grid grid-cols-3 gap-2 mb-4 bg-gray-50 p-2 rounded-lg border border-gray-100">
                                    <div class="text-center">
                                        <div class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-0.5">R1</div>
                                        <div class="text-sm font-bold text-gray-900">{{ $session->score_round_1 ?? '-' }}</div>
                                    </div>
                                    <div class="text-center border-l border-r border-gray-200">
                                        <div class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-0.5">R2</div>
                                        <div class="text-sm font-bold text-gray-900">{{ $session->score_round_2 ?? '-' }}</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-0.5">R3</div>
                                        <div class="text-sm font-bold text-gray-900">{{ $session->score_round_3 ?? '-' }}</div>
                                    </div>
                                </div>
                                
                                <div class="flex justify-end pt-2 border-t border-gray-100">
                                    <form action="{{ route('admin.cerdas-cermat.resetSession', $session->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin mereset/menghapus data peserta ini? Mereka harus mendaftar ulang dan mulai dari awal.')" class="w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full justify-center inline-flex items-center px-4 py-2 bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 rounded-md text-sm font-bold transition-colors">
                                            <i data-lucide="refresh-ccw" class="w-4 h-4 mr-2"></i> Reset Peserta
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-500">
                                Belum ada peserta yang mendaftar.
                            </div>
                        @endforelse
                    </div>

                    <!-- Desktop View -->
                    <table class="hidden md:table min-w-full divide-y divide-gray-200">
                        <thead class="bg-scout-light">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold text-scout-primary uppercase tracking-wider">
                                    Regu</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold text-scout-primary uppercase tracking-wider">
                                    Peserta</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-bold text-scout-primary uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-bold text-scout-primary uppercase tracking-wider">
                                    R1 Check</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-bold text-scout-primary uppercase tracking-wider">
                                    R2 Check</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-bold text-scout-primary uppercase tracking-wider">
                                    R3 Check</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-bold text-scout-primary uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($sessions as $session)
                                <tr class="hover:bg-scout-light transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $session->reguProfile->nama_regu }}
                                        <div class="text-xs text-gray-500">{{ $session->reguProfile->jenis }} -
                                            {{ $session->reguProfile->nomor_regu }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <ul class="list-disc list-inside text-xs">
                                            <li>{{ $session->name_1 }}</li>
                                            <li>{{ $session->name_2 }}</li>
                                            <li>{{ $session->name_3 }}</li>
                                        </ul>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 uppercase">
                                            {{ str_replace('_', ' ', $session->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-900">
                                        {{ $session->score_round_1 ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-900">
                                        {{ $session->score_round_2 ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-900">
                                        {{ $session->score_round_3 ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <form action="{{ route('admin.cerdas-cermat.resetSession', $session->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin mereset/menghapus data peserta ini? Mereka harus mendaftar ulang dan mulai dari awal.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 font-bold bg-red-50 hover:bg-red-100 px-3 py-1 rounded transition border border-red-200">
                                                <i data-lucide="refresh-ccw" class="w-4 h-4 inline-block mr-1"></i> Reset
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-gray-500 italic">
                                        Belum ada peserta yang mendaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection