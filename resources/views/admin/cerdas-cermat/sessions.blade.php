@extends('layouts.main')

@section('title', 'Manajemen Sesi Peserta Cerdas Cermat - Admin Spencerone Camp 2026')

@section('content')
    <div class="py-10 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200/60 text-xs font-semibold uppercase tracking-wider mb-2">
                        <i data-lucide="activity" class="w-3.5 h-3.5 text-emerald-600"></i>
                        Sesi Langsung
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight">
                        Peserta & Sesi Cerdas Cermat
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Pantau progres pengerjaan regu, reset sesi yang bermasalah, atau reset total kompetisi.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <form action="{{ route('admin.cerdas-cermat.sessions.destroyAll') }}" method="POST"
                        onsubmit="return confirm('PERINGATAN KRITIS: Anda akan mereset TOTAL seluruh kompetisi. Ini akan menghapus SEMUA sesi, jawaban, pelanggaran, dan mengembalikan Kontrol Babak ke posisi awal. Lanjutkan?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-red-700 bg-red-50 hover:bg-red-600 hover:text-white border border-red-200 transition shadow-2xs">
                            <i data-lucide="refresh-ccw" class="w-3.5 h-3.5"></i> Reset Total Kompetisi
                        </button>
                    </form>
                    <a href="{{ route('admin.cerdas-cermat.index') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-brand-forest bg-white hover:bg-gray-50 border border-gray-200 transition shadow-2xs">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Kelola Soal
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm font-semibold flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white shadow-sm border border-gray-200/80 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/70">
                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                        <i data-lucide="users" class="w-4 h-4 text-emerald-700"></i>
                        <span>Daftar Sesi Regu Terdaftar</span>
                    </h3>
                    <span class="text-xs font-bold bg-emerald-50 text-brand-forest px-3 py-1 rounded-full border border-emerald-200/60">
                        {{ count($sessions) }} Regu
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <!-- Mobile View -->
                    <div class="md:hidden divide-y divide-gray-100">
                        @forelse($sessions as $session)
                            <div class="p-4 bg-white hover:bg-gray-50/60 transition">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $session->reguProfile->nama_regu }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ ucfirst($session->reguProfile->jenis) }} &bull; Regu {{ $session->reguProfile->nomor_regu }}</div>
                                    </div>
                                    <span class="px-2.5 py-0.5 inline-flex text-[10px] font-bold rounded-full bg-emerald-50 text-brand-forest border border-emerald-200/60 uppercase">
                                        {{ str_replace('_', ' ', $session->status) }}
                                    </span>
                                </div>
                                
                                <div class="mb-3 bg-gray-50/80 p-2.5 rounded-xl border border-gray-100">
                                    <div class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-1">Anggota Tim:</div>
                                    <p class="text-xs text-gray-700 font-medium leading-relaxed">
                                        {{ $session->name_1 }}, {{ $session->name_2 }}, {{ $session->name_3 }}
                                    </p>
                                </div>
                                
                                <div class="grid grid-cols-3 gap-2 mb-3 bg-gray-50/60 p-2 rounded-xl border border-gray-100 text-center">
                                    <div>
                                        <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Babak 1</div>
                                        <div class="text-sm font-black text-brand-forest">{{ $session->score_round_1 ?? '-' }}</div>
                                    </div>
                                    <div class="border-x border-gray-200">
                                        <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Babak 2</div>
                                        <div class="text-sm font-black text-brand-forest">{{ $session->score_round_2 ?? '-' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Babak 3</div>
                                        <div class="text-sm font-black text-brand-forest">{{ $session->score_round_3 ?? '-' }}</div>
                                    </div>
                                </div>
                                
                                <div class="flex justify-end pt-2 border-t border-gray-100">
                                    <form action="{{ route('admin.cerdas-cermat.resetSession', $session->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin mereset/menghapus data peserta ini? Mereka harus mendaftar ulang dan mulai dari awal.')" class="w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full justify-center inline-flex items-center gap-1.5 px-4 py-2 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200/80 rounded-xl text-xs font-bold transition">
                                            <i data-lucide="refresh-ccw" class="w-3.5 h-3.5"></i> Reset Sesi Regu
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-400">
                                <i data-lucide="users" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                                <p class="text-sm">Belum ada peserta yang mendaftar.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Desktop View -->
                    <table class="hidden md:table min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/80 text-gray-600">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Regu</th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Nama Peserta</th>
                                <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold uppercase tracking-wider">Babak 1</th>
                                <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold uppercase tracking-wider">Babak 2</th>
                                <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold uppercase tracking-wider">Babak 3</th>
                                <th scope="col" class="px-6 py-3.5 text-right text-xs font-bold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($sessions as $session)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        {{ $session->reguProfile->nama_regu }}
                                        <div class="text-xs text-gray-400 font-normal mt-0.5">{{ ucfirst($session->reguProfile->jenis) }} &bull; Regu {{ $session->reguProfile->nomor_regu }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-600">
                                        <div class="font-medium leading-relaxed">{{ $session->name_1 }}, {{ $session->name_2 }}, {{ $session->name_3 }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2.5 py-0.5 inline-flex text-[10px] font-bold rounded-full bg-emerald-50 text-brand-forest border border-emerald-200/60 uppercase">
                                            {{ str_replace('_', ' ', $session->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-black text-brand-forest">
                                        {{ $session->score_round_1 ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-black text-brand-forest">
                                        {{ $session->score_round_2 ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-black text-brand-forest">
                                        {{ $session->score_round_3 ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs">
                                        <form action="{{ route('admin.cerdas-cermat.resetSession', $session->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin mereset/menghapus data peserta ini? Mereka harus mendaftar ulang dan mulai dari awal.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 font-bold bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-xl border border-red-200/60 transition inline-flex items-center gap-1">
                                                <i data-lucide="refresh-ccw" class="w-3.5 h-3.5"></i> Reset
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                        <i data-lucide="users" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                                        <p class="text-sm">Belum ada peserta yang mendaftar.</p>
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