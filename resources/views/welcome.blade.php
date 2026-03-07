@extends('layouts.main')

@section('title', 'LT-I Spencerone Camp 2026 - SMPN 1 Cerbon')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-scout-primary overflow-hidden border-b-4 border-scout-secondary">
        <div class="absolute inset-0">
            <img class="w-full h-full object-cover opacity-30 filter sepia"
                src="https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                alt="Scout Camping">
            <div class="absolute inset-0 bg-scout-primary mix-blend-multiply opacity-60"></div>
        </div>
        <div class="relative max-w-7xl mx-auto py-12 sm:py-24 px-4 sm:px-6 lg:px-8 flex flex-col items-center text-center">
            <div class="mb-6 sm:mb-8">
                <div
                    class="w-16 h-16 sm:w-20 sm:h-20 bg-scout-accent rounded-full flex items-center justify-center text-scout-primary shadow-lg border-4 border-scout-primary">
                    <i data-lucide="tent" class="w-8 h-8 sm:w-10 sm:h-10"></i>
                </div>
            </div>
            <h1
                class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight text-scout-light mb-3 sm:mb-4 drop-shadow-md">
                Spencerone Camp 2026
            </h1>
            <p class="mt-3 sm:mt-4 text-base sm:text-xl text-scout-accent max-w-3xl font-semibold drop-shadow-sm px-2">
                "Scout for Future: Cerdas, Tangkas, dan Berkarakter di Era Digital"
            </p>
            <p
                class="mt-4 sm:mt-6 text-sm sm:text-lg text-scout-secondary font-medium flex flex-col sm:flex-row items-center gap-1 sm:gap-0">
                <span class="flex items-center"><i data-lucide="map-pin" class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2"></i>
                    {{ $eventSettings['formatted_date'] ?? '24-25 April 2026' }}</span>
                <span class="hidden sm:inline mx-2">|</span>
                <span>{{ $eventSettings['event_location'] ?? 'Lingkungan SMP Negeri 1 Cerbon' }}</span>
            </p>

            <!-- Countdown Timer -->
            <div class="mt-10 grid grid-cols-4 gap-2 sm:gap-4 text-scout-light">
                <div
                    class="bg-scout-primary/40 backdrop-blur-md p-2 sm:p-4 rounded-lg sm:rounded-xl border-2 border-scout-secondary/50 shadow-sm">
                    <span class="block text-2xl sm:text-4xl font-extrabold">@{{ countdown.days }}</span>
                    <span class="text-xs sm:text-sm uppercase font-semibold tracking-wider text-scout-accent">Hari</span>
                </div>
                <div
                    class="bg-scout-primary/40 backdrop-blur-md p-2 sm:p-4 rounded-lg sm:rounded-xl border-2 border-scout-secondary/50 shadow-sm">
                    <span class="block text-2xl sm:text-4xl font-extrabold">@{{ countdown.hours }}</span>
                    <span class="text-xs sm:text-sm uppercase font-semibold tracking-wider text-scout-accent">Jam</span>
                </div>
                <div
                    class="bg-scout-primary/40 backdrop-blur-md p-2 sm:p-4 rounded-lg sm:rounded-xl border-2 border-scout-secondary/50 shadow-sm">
                    <span class="block text-2xl sm:text-4xl font-extrabold">@{{ countdown.minutes }}</span>
                    <span class="text-xs sm:text-sm uppercase font-semibold tracking-wider text-scout-accent">Menit</span>
                </div>
                <div
                    class="bg-scout-primary/40 backdrop-blur-md p-2 sm:p-4 rounded-lg sm:rounded-xl border-2 border-scout-secondary/50 shadow-sm">
                    <span class="block text-2xl sm:text-4xl font-extrabold">@{{ countdown.seconds }}</span>
                    <span class="text-xs sm:text-sm uppercase font-semibold tracking-wider text-scout-accent">Detik</span>
                </div>
            </div>
            <div class="mt-8 sm:mt-12 flex flex-col sm:flex-row gap-4 justify-center">
                @if($showFinancialReport == '1' && $activeReport)
                    <a href="{{ Storage::url($activeReport->file_path) }}" target="_blank"
                        class="btn-scout-accent px-6 sm:px-8 py-3 sm:py-4 text-base sm:text-lg font-bold rounded-full shadow-lg transform transition hover:scale-105 border-2 border-scout-primary flex items-center justify-center">
                        <i data-lucide="banknote" class="w-4 h-4 sm:w-5 sm:h-5 mr-2"></i> {{ $activeReport->title }}
                    </a>
                @else
                    <a href="{{ route('informasi-lomba') }}"
                        class="btn-scout-primary px-6 sm:px-8 py-3 sm:py-4 text-base sm:text-lg font-bold rounded-full shadow-lg transform transition hover:scale-105 border-2 border-scout-secondary flex items-center justify-center">
                        <i data-lucide="info" class="w-4 h-4 sm:w-5 sm:h-5 mr-2"></i> Panduan Lomba
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Score Dashboard Section -->
    <div class="py-12 sm:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12">
                <h2
                    class="text-sm sm:text-base text-scout-primary font-bold tracking-wider uppercase mb-2 flex items-center justify-center">
                    <span class="w-8 sm:w-12 h-0.5 bg-scout-secondary mr-3 sm:mr-4"></span>
                    Dashboard Rekap Nilai
                    <span class="w-8 sm:w-12 h-0.5 bg-scout-secondary ml-3 sm:ml-4"></span>
                </h2>
                <p class="mt-2 text-2xl sm:text-3xl lg:text-4xl leading-8 font-extrabold tracking-tight px-4">
                    Peringkat Tim Terbaik
                </p>
            </div>

            <!-- Score Table/Cards -->
            <div class="mb-12">
                <div class="text-center mb-6">
                    <h3 class="text-xl sm:text-2xl font-bold text-scout-primary uppercase tracking-wide">
                        🏆 Klasemen Juara Umum 🏆
                    </h3>
                    <p class="text-gray-500 text-sm mt-1">Regu 3 Besar Disensor untuk Kejutan!</p>
                </div>
                <div class="card-scout rounded-2xl overflow-hidden shadow-lg border-2 border-orange-200">
                    <!-- Desktop Table JU-->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-orange-400 to-yellow-500">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider w-24">
                                        Peringkat
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                        Nama Regu
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider w-32">
                                        Total Poin
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="ju in juaraUmum" :key="ju.reguProfile.id"
                                    class="hover:bg-orange-50 transition-colors"
                                    :class="ju.peringkat <= 3 ? 'bg-orange-50/30' : ''">
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center">
                                            <span v-if="ju.peringkat === 1" class="text-3xl drop-shadow-md">🏆</span>
                                            <span v-else-if="ju.peringkat === 2" class="text-2xl drop-shadow-md">🥈</span>
                                            <span v-else-if="ju.peringkat === 3" class="text-2xl drop-shadow-md">🥉</span>
                                            <span v-else class="text-lg font-bold text-gray-700">@{{ ju.peringkat }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-base font-bold text-gray-900">
                                            @{{ (!revealLeaderboard && ju.peringkat <= 3) ?
                                                maskName(ju.reguProfile.nama_regu) : ju.reguProfile.nama_regu }} </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center bg-orange-50/50">
                                        <div class="text-2xl font-black text-orange-600">
                                            @{{ (!revealLeaderboard && ju.peringkat <= 3) ? '***' : ju.poin }} </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards JU -->
                    <div class="md:hidden space-y-4 p-4">
                        <div v-for="ju in juaraUmum" :key="ju.reguProfile.id"
                            class="bg-scout-surface rounded-xl p-4 border-2 border-orange-200/50 hover:border-orange-400 transition-all shadow-sm">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span v-if="ju.peringkat === 1" class="text-3xl mr-3 drop-shadow-md">🏆</span>
                                    <span v-else-if="ju.peringkat === 2" class="text-2xl mr-3 drop-shadow-md">🥈</span>
                                    <span v-else-if="ju.peringkat === 3" class="text-2xl mr-3 drop-shadow-md">🥉</span>
                                    <span v-else class="text-xl font-bold text-gray-700 mr-3">#@{{ ju.peringkat }}</span>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">
                                            @{{ (!revealLeaderboard && ju.peringkat <= 3) ?
                                                maskName(ju.reguProfile.nama_regu) : ju.reguProfile.nama_regu }} </h3>
                                    </div>
                                </div>
                                <div class="text-right pl-4">
                                    <div class="text-[10px] text-gray-500 uppercase tracking-wider font-semibold">Poin</div>
                                    <div class="text-2xl font-black text-orange-600">
                                        @{{ (!revealLeaderboard && ju.peringkat <= 3) ? '***' : ju.poin }} </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <!-- Sponsorship Section -->
        <div class="py-12 sm:py-16 bg-scout-surface">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-8 sm:mb-12">
                    <h2
                        class="text-sm sm:text-base text-scout-primary font-bold tracking-wider uppercase mb-2 flex items-center justify-center">
                        <span class="w-8 sm:w-12 h-0.5 bg-scout-secondary mr-3 sm:mr-4"></span>
                        Sponsor & Pendukung
                        <span class="w-8 sm:w-12 h-0.5 bg-scout-secondary ml-3 sm:ml-4"></span>
                    </h2>
                    <p class="mt-2 text-2xl sm:text-3xl lg:text-4xl leading-8 font-extrabold tracking-tight px-4">
                        Terima Kasih Kepada
                    </p>
                </div>

                <!-- Platinum Sponsor -->
                @if(isset($sponsorships['platinum']))
                    <div class="mb-10 sm:mb-12">
                        <h3
                            class="text-center text-lg sm:text-xl font-bold text-scout-primary mb-4 sm:mb-6 flex items-center justify-center">
                            <i data-lucide="award" class="w-5 h-5 sm:w-6 sm:h-6 mr-2 text-yellow-500"></i>
                            Platinum Sponsor
                        </h3>
                        <div class="flex flex-wrap justify-center gap-6">
                            @foreach($sponsorships['platinum'] as $sponsor)
                                <div class="card-scout rounded-2xl p-6 sm:p-8 w-full max-w-md hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer"
                                    {{ $sponsor->website_url ? 'onclick=window.open("' . $sponsor->website_url . '")' : '' }}>
                                    <div class="bg-white rounded-xl p-6 sm:p-8 flex items-center justify-center h-32 sm:h-40">
                                        @if($sponsor->logo)
                                            <img src="{{ Storage::url($sponsor->logo) }}" alt="{{ $sponsor->name }}"
                                                class="max-h-full max-w-full object-contain">
                                        @else
                                            <div class="text-center">
                                                <div class="text-3xl sm:text-4xl font-extrabold text-scout-primary mb-2">
                                                    {{ $sponsor->name }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Gold Sponsors -->
                @if(isset($sponsorships['gold']))
                    <div class="mb-8 sm:mb-10">
                        <h3
                            class="text-center text-base sm:text-lg font-bold text-scout-primary mb-4 sm:mb-6 flex items-center justify-center">
                            <i data-lucide="medal" class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-yellow-600"></i>
                            Gold Sponsors
                        </h3>
                        <div class="flex flex-wrap justify-center gap-4 sm:gap-6">
                            @foreach($sponsorships['gold'] as $sponsor)
                                <div class="card-scout rounded-xl p-4 sm:p-6 w-full max-w-[320px] hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer"
                                    {{ $sponsor->website_url ? 'onclick=window.open("' . $sponsor->website_url . '")' : '' }}>
                                    <div class="bg-white rounded-lg p-4 sm:p-6 flex items-center justify-center h-24 sm:h-28">
                                        @if($sponsor->logo)
                                            <img src="{{ Storage::url($sponsor->logo) }}" alt="{{ $sponsor->name }}"
                                                class="max-h-full max-w-full object-contain">
                                        @else
                                            <div class="text-center">
                                                <div class="text-xl sm:text-2xl font-bold text-gray-700">{{ $sponsor->name }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Silver Sponsors -->
                @if(isset($sponsorships['silver']))
                    <div>
                        <h3
                            class="text-center text-sm sm:text-base font-bold text-scout-primary mb-4 sm:mb-6 flex items-center justify-center">
                            <i data-lucide="star" class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-gray-400"></i>
                            Silver Sponsors
                        </h3>
                        <div class="flex flex-wrap justify-center gap-3 sm:gap-4">
                            @foreach($sponsorships['silver'] as $sponsor)
                                <div class="card-scout rounded-lg p-3 sm:p-4 w-[45%] sm:w-[30%] lg:w-[22%] max-w-[200px] min-w-[140px] hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 cursor-pointer"
                                    {{ $sponsor->website_url ? 'onclick=window.open("' . $sponsor->website_url . '")' : '' }}>
                                    <div class="bg-white rounded p-3 flex items-center justify-center h-16 sm:h-20">
                                        @if($sponsor->logo)
                                            <img src="{{ Storage::url($sponsor->logo) }}" alt="{{ $sponsor->name }}"
                                                class="max-h-full max-w-full object-contain">
                                        @else
                                            <div class="text-sm sm:text-base font-semibold text-gray-600 text-center">
                                                {{ $sponsor->name }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
@endsection

    @push('scripts')
        <script>         window.pageData = function () {             const countdown = Vue.reactive({                 days: 0,                 hours: 0,                 minutes: 0,                 seconds: 0             });             const targetDate = new Date('{{ $countdownTarget }}').getTime();
                 const updateCountdown = () => {                 const now = new Date().getTime();                 const distance = targetDate - now;
                     if (distance > 0) {                     countdown.days = Math.floor(distance / (1000 * 60 * 60 * 24));                     countdown.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));                     countdown.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));                     countdown.seconds = Math.floor((distance % (1000 * 60)) / 1000);                 }             };
                 const maskName = (name) => {                 return '***';             };
                 return {                 allRegu: @json($allRegu ?? []),                 juaraUmum: @json($juaraUmum ?? []),                 revealLeaderboard: @json($revealLeaderboard == '1'),                 countdown,                 maskName,                 onMounted: () => {                     setInterval(updateCountdown, 1000);                     updateCountdown();                 }             }         }
        </script>
    @endpush