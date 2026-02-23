@extends('layouts.main')

@section('title', 'Informasi Lomba - LT-I Spencerone Camp 2026')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-12 sm:py-16">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center p-3 bg-scout-primary/10 rounded-2xl mb-4">
                <i data-lucide="book-open" class="w-10 h-10 text-scout-primary"></i>
            </div>
            <h1
                class="text-4xl sm:text-5xl font-extrabold mb-4 bg-gradient-to-r from-scout-primary to-scout-accent bg-clip-text text-transparent">
                Informasi Lomba
            </h1>
            <p class="text-lg text-gray-600 font-medium italic">
                Lomba Tingkat I (LT-I) Tahun 2026
            </p>
        </div>

        <!-- Accordion List -->
        <div class="space-y-4 mb-16">
            @foreach($mataLombas as $lomba)
                <div class="card-scout rounded-3xl border-2 transition-all duration-300 overflow-hidden"
                    :class="activeLomba === '{{ $lomba->slug }}' ? 'border-scout-primary shadow-lg' : 'border-scout-secondary/20 hover:border-scout-primary/30'">
                    
                    <!-- Accordion Trigger -->
                    <button @click="toggleLomba('{{ $lomba->slug }}')"
                        class="w-full text-left p-6 sm:p-8 flex items-center justify-between gap-4 group">
                        <div class="flex items-center gap-4 sm:gap-6">
                            <div class="p-3 bg-scout-primary/10 rounded-2xl group-hover:bg-scout-primary group-hover:text-white transition-colors duration-300"
                                :class="{ 'bg-scout-primary text-white': activeLomba === '{{ $lomba->slug }}' }">
                                <i data-lucide="{{ $lomba->slug === 'cerdas-cermat' ? 'brain' : ($lomba->slug === 'tapak-kemah' ? 'tent' : ($lomba->slug === 'masak-konvensional' ? 'chef-hat' : ($lomba->slug === 'upcycle-art' ? 'recycle' : ($lomba->slug === 'poster-digital' ? 'image' : 'users')))) }}"
                                    class="w-6 h-6 sm:w-8 sm:h-8"></i>
                            </div>
                            <div>
                                <h3 class="text-xl sm:text-2xl font-black text-scout-primary leading-tight">{{ $lomba->nama }}</h3>
                                <p class="text-xs font-bold text-scout-primary/40 uppercase tracking-widest mt-1">Lomba #{{ $lomba->urutan }}</p>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <i data-lucide="chevron-down" 
                                class="w-6 h-6 text-scout-primary/40 transition-transform duration-300"
                                :class="{ 'rotate-180 text-scout-primary': activeLomba === '{{ $lomba->slug }}' }"></i>
                        </div>
                    </button>

                    <!-- Accordion Content -->
                    <div v-show="activeLomba === '{{ $lomba->slug }}'" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        class="px-6 pb-8 sm:px-10 space-y-8 border-t border-scout-secondary/10 bg-scout-surface/5">
                        
                        <!-- Description -->
                        <div class="pt-6">
                            <p class="text-gray-600 leading-relaxed italic">
                                "{{ $lomba->deskripsi ?? 'Informasi tentang lomba ini akan segera diperbarui.' }}"
                            </p>
                        </div>

                        <!-- Juknis -->
                        <section>
                            <div class="flex items-center gap-3 mb-4 text-scout-primary">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                                <h4 class="text-lg font-bold uppercase tracking-tight">Petunjuk Teknis</h4>
                            </div>
                            <div class="bg-white/50 p-6 rounded-2xl border border-scout-secondary/20 shadow-sm">
                                <ul class="space-y-3">
                                    @php
                                        $juknisLines = array_filter(explode("\n", str_replace(['\n', "\r"], ["\n", ""], $lomba->petunjuk_teknis ?? '')));
                                    @endphp
                                    @forelse($juknisLines as $line)
                                        <li class="flex items-start gap-3">
                                            <i data-lucide="chevron-right" class="w-4 h-4 text-scout-accent flex-shrink-0 mt-1"></i>
                                            <span class="text-sm font-medium text-gray-700">{{ trim($line) }}</span>
                                        </li>
                                    @empty
                                        <li class="italic text-gray-400 text-sm">Belum ada petunjuk teknis yang diunggah.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </section>

                        <!-- Ketentuan -->
                        <section>
                            <div class="flex items-center gap-3 mb-4 text-scout-primary">
                                <i data-lucide="shield-check" class="w-5 h-5"></i>
                                <h4 class="text-lg font-bold uppercase tracking-tight">Ketentuan Pelaksanaan</h4>
                            </div>
                            <div class="bg-white/50 p-6 rounded-2xl border border-scout-secondary/20 shadow-sm">
                                <ul class="space-y-3">
                                    @php
                                        $ketentuanLines = array_filter(explode("\n", str_replace(['\n', "\r"], ["\n", ""], $lomba->ketentuan_pelaksanaan ?? '')));
                                    @endphp
                                    @forelse($ketentuanLines as $line)
                                        <li class="flex items-start gap-3">
                                            <i data-lucide="shield-check" class="w-4 h-4 text-scout-accent flex-shrink-0 mt-1"></i>
                                            <span class="text-sm font-medium text-gray-700">{{ trim($line) }}</span>
                                        </li>
                                    @empty
                                        <li class="italic text-gray-400 text-sm">Belum ada ketentuan pelaksanaan yang diunggah.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </section>

                        <!-- Kriteria -->
                        <section>
                            <div class="flex items-center gap-3 mb-4 text-scout-primary">
                                <i data-lucide="award" class="w-5 h-5"></i>
                                <h4 class="text-lg font-bold uppercase tracking-tight">Kriteria Penilaian</h4>
                            </div>
                            <div class="bg-white/50 p-6 rounded-2xl border border-scout-secondary/20 shadow-sm">
                                <ul class="space-y-3">
                                    @php
                                        $kriteriaLines = array_filter(explode("\n", str_replace(['\n', "\r"], ["\n", ""], $lomba->kriteria_penilaian ?? '')));
                                    @endphp
                                    @forelse($kriteriaLines as $line)
                                        <li class="flex items-start gap-3">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-scout-accent flex-shrink-0 mt-1"></i>
                                            <span class="text-sm font-medium text-gray-700">{{ trim($line) }}</span>
                                        </li>
                                    @empty
                                        <li class="italic text-gray-400 text-sm">Belum ada kriteria penilaian yang diunggah.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </section>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.pageData = () => {
            const { ref, nextTick } = Vue;
            const activeLomba = ref(null);

            const toggleLomba = (slug) => {
                activeLomba.value = activeLomba.value === slug ? null : slug;
                nextTick(() => {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                });
            };

            return {
                activeLomba,
                toggleLomba
            };
        };
    </script>
@endpush
