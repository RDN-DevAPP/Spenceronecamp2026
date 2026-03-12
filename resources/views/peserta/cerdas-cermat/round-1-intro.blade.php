@extends('layouts.peserta')

@section('title', 'Babak 1 - Cerdas Cermat')

@section('content')
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden border border-scout-secondary">
            <div class="bg-scout-primary px-6 py-4 border-b border-scout-secondary flex items-center justify-between">
                <div class="flex items-center">
                    <h2 class="text-xl font-bold text-white">Babak 1: Pilihan Ganda</h2>
                </div>
                <span id="status-badge" class="bg-white/20 text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">
                    Status: {{ \App\Models\CerdasCermatSetting::getValue('round_1_started', false) ? 'Berjalan' : 'Menunggu' }}
                </span>
            </div>

            <div class="p-8 text-center" id="intro-container">
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

                <div class="flex justify-center space-x-4" id="action-container">
                    @if(\App\Models\CerdasCermatSetting::getValue('round_1_started', false))
                        <a href="{{ route('peserta.cerdas-cermat.round-1') }}"
                            class="bg-scout-accent text-scout-primary px-8 py-3 rounded-lg font-bold hover:bg-yellow-500 transition shadow-lg flex items-center transform hover:scale-105 duration-200">
                            <i data-lucide="play-circle" class="w-5 h-5 mr-2"></i>
                            Mulai Babak 1
                        </a>
                    @else
                        <div class="flex flex-col items-center">
                            <button disabled
                                class="bg-gray-300 text-gray-500 px-8 py-3 rounded-lg font-bold cursor-not-allowed shadow flex items-center">
                                <i data-lucide="clock" class="w-5 h-5 mr-2"></i>
                                Menunggu Juri Memulai Babak
                            </button>
                            <p class="text-sm text-gray-500 mt-3 italic">Juri belum memulai babak ini. Harap bersabar.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let roundStarted = {{ \App\Models\CerdasCermatSetting::getValue('round_1_started', false) ? 'true' : 'false' }};
            
            if (!roundStarted) {
                const interval = setInterval(async () => {
                    try {
                        const response = await fetch('{{ route("peserta.cerdas-cermat.check-status") }}');
                        const data = await response.json();
                        
                        if (data.round_1_started) {
                            clearInterval(interval);
                            
                            // Update UI
                            const badge = document.getElementById('status-badge');
                            badge.innerHTML = 'Status: Berjalan';
                            badge.classList.remove('bg-white/20');
                            badge.classList.add('bg-green-500');
                            
                            const container = document.getElementById('action-container');
                            container.innerHTML = `
                                <a href="{{ route('peserta.cerdas-cermat.round-1') }}"
                                    class="bg-scout-accent text-scout-primary px-8 py-3 rounded-lg font-bold hover:bg-yellow-500 transition shadow-lg flex items-center transform hover:scale-105 duration-200">
                                    <i data-lucide="play-circle" class="w-5 h-5 mr-2"></i>
                                    Mulai Babak 1
                                </a>
                            `;
                            
                            // Re-initialize Lucide icons
                            if (window.lucide) {
                                window.lucide.createIcons();
                            }
                            
                            // Optional: Auto redirect after 2 seconds
                            setTimeout(() => {
                                window.location.href = '{{ route("peserta.cerdas-cermat.round-1") }}';
                            }, 2000);
                        }
                    } catch (error) {
                        console.error('Error checking status:', error);
                    }
                }, 3000);
            }
        });
    </script>
    @endpush
@endsection