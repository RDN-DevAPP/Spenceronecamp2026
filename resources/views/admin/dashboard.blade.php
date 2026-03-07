@extends('layouts.main')

@section('title', 'Admin Dashboard - LT-I Spencerone Camp 2026')

@section('content')
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Admin Dashboard</h1>

            <!-- Controls / Filters Row -->
            <div class="mb-8 w-full max-w-3xl mx-auto">
                <!-- Global Settings -->
                <div class="bg-white p-6 rounded-lg shadow-md border-2 border-scout-secondary/30">
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        <i data-lucide="settings" class="w-5 h-5 mr-2 text-gray-600"></i>
                        Pengaturan Publik
                    </h2>
                    <form action="{{ route('admin.toggle.reveal') }}" method="POST">
                        @csrf
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-medium text-gray-900">Buka Sensor Juara Umum</h3>
                                <p class="text-xs text-gray-500 mt-1">Tampilkan regu 3 besar.</p>
                            </div>
                            <label for="reveal-toggle" class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="reveal" value="0">
                                <input type="checkbox" id="reveal-toggle" name="reveal" value="1" class="sr-only peer" onchange="this.form.submit()" {{ $revealLeaderboard == '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-4 ring-transparent peer-focus:ring-scout-accent/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-scout-primary"></div>
                            </label>
                        </div>
                    </form>

                    <form action="{{ route('admin.toggle.financial-report') }}" method="POST">
                        @csrf
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div>
                                <h3 class="font-medium text-gray-900">Tombol Laporan Keuangan</h3>
                                <p class="text-xs text-gray-500 mt-1">Ganti "Info Lomba" di beranda.</p>
                            </div>
                            <label for="show-report-toggle" class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="show" value="0">
                                <input type="checkbox" id="show-report-toggle" name="show" value="1" class="sr-only peer" onchange="this.form.submit()" {{ $showFinancialReport == '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-4 ring-transparent peer-focus:ring-scout-accent/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-scout-primary"></div>
                            </label>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Juara Umum Leaderboard -->
            @if(count($juaraUmum) > 0)
                <div class="bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl shadow-lg mb-8 overflow-hidden">
                    <div class="px-6 py-5 flex justify-between items-center bg-white/10 border-b border-white/20">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <i data-lucide="award" class="w-6 h-6 mr-2 text-yellow-300"></i>
                            Klasemen Juara Umum
                        </h3>
                    </div>
                    <!-- Desktop View -->
                    <div class="hidden md:block overflow-x-auto bg-white">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 border-b-2 border-orange-200">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider w-20">Rank</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Regu</th>
                                    @foreach($allMataLombaFiltered as $ml)
                                        <th scope="col" class="px-4 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">{{ $ml->nama }}</th>
                                    @endforeach
                                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-orange-700 uppercase tracking-wider">Total Poin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($juaraUmum as $ju)
                                    <tr class="hover:bg-orange-50/50 transition-colors {{ $loop->iteration <= 3 ? 'bg-orange-50/30' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex justify-center">
                                                @if($ju['peringkat'] == 1)
                                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-yellow-300 to-yellow-500 text-white font-bold shadow-md ring-4 ring-yellow-100">1</span>
                                                @elseif($ju['peringkat'] == 2)
                                                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-gradient-to-br from-gray-300 to-gray-400 text-white font-bold shadow-md ring-4 ring-gray-100">2</span>
                                                @elseif($ju['peringkat'] == 3)
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-orange-300 to-orange-500 text-white font-bold shadow-md ring-4 ring-orange-100">3</span>
                                                @else
                                                    <span class="font-bold text-gray-600">{{ $ju['peringkat'] }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-base font-bold text-gray-900">{{ $ju['reguProfile']->nama_regu }}</div>
                                            <div class="text-xs text-gray-500 mt-1">{{ ucfirst($ju['reguProfile']->jenis) }} - Regu {{ $ju['reguProfile']->nomor_regu }}</div>
                                        </td>
                                        @foreach($allMataLombaFiltered as $ml)
                                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                                @if(isset($ju['lomba_poin'][$ml->id]))
                                                    @if($ju['lomba_poin'][$ml->id] == 3)
                                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-yellow-100 text-yellow-700 font-bold text-xs ring-1 ring-yellow-300" title="Juara 1">3</span>
                                                    @elseif($ju['lomba_poin'][$ml->id] == 2)
                                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-100 text-gray-700 font-bold text-xs ring-1 ring-gray-300" title="Juara 2">2</span>
                                                    @elseif($ju['lomba_poin'][$ml->id] == 1)
                                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-orange-100 text-orange-800 font-bold text-xs ring-1 ring-orange-300" title="Juara 3">1</span>
                                                    @endif
                                                @else
                                                    <span class="text-gray-300">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="px-6 py-4 whitespace-nowrap text-center bg-orange-50/50">
                                            <div class="text-2xl font-black text-orange-600">{{ $ju['poin'] }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile View -->
                    <div class="md:hidden bg-white">
                        @foreach($juaraUmum as $ju)
                            <div class="p-4 border-b border-gray-100 {{ $loop->iteration <= 3 ? 'bg-orange-50/30' : '' }}">
                                <div class="flex justify-between items-center mb-3">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            @if($ju['peringkat'] == 1)
                                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-yellow-300 to-yellow-500 text-white font-bold shadow-md ring-2 ring-yellow-200">1</span>
                                            @elseif($ju['peringkat'] == 2)
                                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-gray-300 to-gray-400 text-white font-bold shadow-md ring-2 ring-gray-200">2</span>
                                            @elseif($ju['peringkat'] == 3)
                                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-orange-300 to-orange-500 text-white font-bold shadow-md ring-2 ring-orange-200">3</span>
                                            @else
                                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-50 text-gray-600 font-bold border border-gray-200">{{ $ju['peringkat'] }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900">{{ $ju['reguProfile']->nama_regu }}</div>
                                            <div class="text-xs text-gray-500">{{ ucfirst($ju['reguProfile']->jenis) }} - Regu {{ $ju['reguProfile']->nomor_regu }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-[10px] text-gray-500 uppercase tracking-wider font-semibold">Total Poin</div>
                                        <div class="text-xl font-black text-orange-600">{{ $ju['poin'] }}</div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 mt-2 bg-gray-50 rounded-lg p-3 border border-gray-100">
                                    @foreach($allMataLombaFiltered as $ml)
                                        @if(isset($ju['lomba_poin'][$ml->id]))
                                            <div class="text-center p-1">
                                                <div class="text-[9px] text-gray-500 font-medium mb-1 truncate" title="{{ $ml->nama }}">{{ Str::limit($ml->nama, 10) }}</div>
                                                @if($ju['lomba_poin'][$ml->id] == 3)
                                                    <div class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-yellow-100 text-yellow-700 font-bold text-xs ring-1 ring-yellow-300" title="Juara 1">3</div>
                                                @elseif($ju['lomba_poin'][$ml->id] == 2)
                                                    <div class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-100 text-gray-700 font-bold text-xs ring-1 ring-gray-300" title="Juara 2">2</div>
                                                @elseif($ju['lomba_poin'][$ml->id] == 1)
                                                    <div class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-orange-100 text-orange-800 font-bold text-xs ring-1 ring-orange-300" title="Juara 3">1</div>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Leaderboard Tables -->
            @forelse($leaderboards as $lombaId => $lombaData)
                @php
                    $lomba = $lombaData['mata_lomba'];
                    $lb = $lombaData['leaderboard'];
                    $juriCols = $lombaData['juri_columns'];
                    // Ensure up to 3 Juri columns for display as requested
                    $juriColsForDisplay = $juriCols->take(3)->values();
                @endphp
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
                    <div class="px-6 py-5 flex justify-between items-center bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-bold text-gray-900 flex items-center">
                            <i data-lucide="trophy" class="w-5 h-5 mr-2 text-yellow-500"></i>
                            Leaderboard - {{ $lomba->nama }}
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <!-- Mobile View -->
                        <div class="md:hidden">
                            @forelse($lb as $row)
                                <div class="p-4 border-b border-gray-200 {{ $loop->iteration <= 3 ? 'bg-amber-50/30' : '' }}">
                                    <div class="flex justify-between items-center mb-3">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                @if($row['peringkat'] == 1)
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100 text-yellow-800 font-bold border border-yellow-300 shadow-sm">1</span>
                                                @elseif($row['peringkat'] == 2)
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-800 font-bold border border-gray-300 shadow-sm">2</span>
                                                @elseif($row['peringkat'] == 3)
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-orange-100 text-orange-800 font-bold border border-orange-300 shadow-sm">3</span>
                                                @else
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-50 text-gray-600 font-bold border border-gray-200">
                                                        {{ $row['peringkat'] }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $row['reguProfile']->nama_regu }}</div>
                                                <div class="text-xs text-gray-500">{{ ucfirst($row['reguProfile']->jenis) }} - Regu {{ $row['reguProfile']->nomor_regu }}</div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Total</div>
                                            <div class="text-lg font-bold text-scout-primary">{{ number_format($row['total_nilai'], 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2 mt-2 bg-gray-50 rounded-lg p-2 border border-gray-100">
                                        @for($i = 0; $i < 3; $i++)
                                            <div class="text-center">
                                                <div class="text-xs text-gray-500 font-medium mb-1">Juri {{ $i + 1 }}</div>
                                                @if(isset($juriColsForDisplay[$i]) && isset($row['juri_scores'][$juriColsForDisplay[$i]]))
                                                    <div class="text-sm font-semibold text-gray-800">{{ $row['juri_scores'][$juriColsForDisplay[$i]] }}</div>
                                                @else
                                                    <div class="text-sm text-gray-400">-</div>
                                                @endif
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-500">
                                    <i data-lucide="clipboard-x" class="w-10 h-10 text-gray-300 mx-auto mb-2"></i>
                                    <p>Belum ada regu yang dinilai.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Desktop View -->
                        <table class="hidden md:table min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">
                                        <div class="flex justify-center">Peringkat</div>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Nama Regu
                                    </th>
                                    @for($i = 1; $i <= 3; $i++)
                                        <th scope="col" class="px-4 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Juri {{ $i }}
                                        </th>
                                    @endfor
                                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-scout-primary uppercase tracking-wider bg-indigo-50/30">
                                        Total Nilai
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($lb as $row)
                                    <tr class="hover:bg-gray-50/50 transition-colors {{ $loop->iteration <= 3 ? 'bg-amber-50/20' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex justify-center">
                                                @if($row['peringkat'] == 1)
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-yellow-300 to-yellow-500 text-white font-bold shadow-sm ring-2 ring-yellow-200 ring-offset-1">1</span>
                                                @elseif($row['peringkat'] == 2)
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-gray-300 to-gray-400 text-white font-bold shadow-sm ring-2 ring-gray-200 ring-offset-1">2</span>
                                                @elseif($row['peringkat'] == 3)
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-orange-300 to-orange-500 text-white font-bold shadow-sm ring-2 ring-orange-200 ring-offset-1">3</span>
                                                @else
                                                    <span class="font-bold text-gray-500">{{ $row['peringkat'] }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $row['reguProfile']->nama_regu }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">{{ ucfirst($row['reguProfile']->jenis) }} - Regu {{ $row['reguProfile']->nomor_regu }}</div>
                                        </td>
                                        @for($i = 0; $i < 3; $i++)
                                            <td class="px-4 py-4 whitespace-nowrap text-center border-l border-gray-50">
                                                @if(isset($juriColsForDisplay[$i]) && isset($row['juri_scores'][$juriColsForDisplay[$i]]))
                                                    <span class="text-sm text-gray-700 font-medium inline-block px-3 py-1 bg-gray-50 rounded-md">{{ $row['juri_scores'][$juriColsForDisplay[$i]] }}</span>
                                                @else
                                                    <span class="text-sm text-gray-400">-</span>
                                                @endif
                                            </td>
                                        @endfor
                                        <td class="px-6 py-4 whitespace-nowrap text-center bg-indigo-50/10 border-l border-gray-100">
                                            <div class="text-lg font-black text-scout-primary">{{ number_format($row['total_nilai'], 0, ',', '.') }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="bg-gray-50 p-4 rounded-full mb-3">
                                                    <i data-lucide="clipboard-list" class="w-8 h-8 text-gray-400"></i>
                                                </div>
                                                <p class="text-sm font-medium">Belum ada data penilaian untuk mata lomba ini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="bg-white shadow overflow-hidden sm:rounded-lg p-12 text-center border border-dashed border-gray-300">
                    <i data-lucide="folder-search" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900">Tidak ada data ditemukan.</h3>
                    <p class="text-sm text-gray-500 mt-1">Belum ada mata lomba atau penilaian yang tersedia.</p>
                </div>
            @endforelse


        </div>
    </div>
@endsection