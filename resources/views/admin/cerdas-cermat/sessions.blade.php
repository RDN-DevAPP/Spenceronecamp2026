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
                    <table class="min-w-full divide-y divide-gray-200">
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