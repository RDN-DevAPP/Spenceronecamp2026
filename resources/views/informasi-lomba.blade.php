@extends('layouts.main')

@section('title', 'Informasi Lomba - LT-I Spencerone Camp 2026')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-12 sm:py-16">
        <!-- Header -->
        <div class="text-center mb-10 sm:mb-14">
            <div class="inline-flex items-center justify-center p-3 bg-scout-primary/10 rounded-2xl mb-4">
                <i data-lucide="book-open" class="w-10 h-10 sm:w-12 sm:h-12 text-scout-primary"></i>
            </div>
            <h1
                class="text-4xl sm:text-5xl font-extrabold mb-4 bg-gradient-to-r from-scout-primary to-scout-accent bg-clip-text text-transparent">
                Informasi Lomba
            </h1>
            <p class="text-lg sm:text-xl text-gray-600 font-medium max-w-2xl mx-auto px-4">
                Lomba Tingkat I (LT-I) Tahun 2026
            </p>
        </div>

        <!-- Mata Lomba Cards -->
        <div class="card-scout rounded-3xl overflow-hidden shadow-xl p-6 sm:p-10 border-2 border-scout-secondary/20">
            <div class="flex items-center gap-3 mb-8">
                <div class="p-2 bg-scout-primary/10 rounded-lg">
                    <i data-lucide="trophy" class="w-6 h-6 sm:w-7 sm:h-7 text-scout-primary"></i>
                </div>
                <h3 class="text-2xl sm:text-3xl font-bold text-scout-primary">
                    Daftar Mata Lomba
                </h3>
            </div>

            <div class="grid gap-4 sm:gap-5">
                <div v-for="lomba in mataLomba" :key="lomba.id"
                    class="flex items-center p-5 sm:p-6 bg-gradient-to-r from-scout-surface to-white rounded-2xl border-2 border-scout-secondary/30 hover:border-scout-primary/50 transition-all duration-300 hover:shadow-lg group">
                    <div
                        class="p-3 sm:p-4 bg-scout-primary/10 group-hover:bg-scout-primary rounded-xl mr-4 sm:mr-5 transition-colors duration-300">
                        <i :data-lucide="getLombaIcon(lomba.slug)"
                            class="w-6 h-6 sm:w-7 sm:h-7 text-scout-primary group-hover:text-white transition-colors duration-300"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-lg sm:text-xl text-gray-900 mb-1">@{{ lomba.nama }}</h4>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <i data-lucide="hash" class="w-4 h-4"></i>
                            <span>Urutan: @{{ lomba.urutan }}</span>
                        </div>
                    </div>
                    <div class="hidden sm:block">
                        <div
                            class="w-10 h-10 rounded-full bg-scout-accent/20 flex items-center justify-center group-hover:bg-scout-accent transition-colors duration-300">
                            <i data-lucide="chevron-right" class="w-5 h-5 text-scout-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.pageData = function () {
            return {
                mataLomba: [
                    {
                        nama: 'Tapak Kemah',
                        slug: 'tapak-kemah',
                        urutan: 1,
                        deskripsi: 'Penilaian tata letak dan kerapihan kemah'
                    },
                    {
                        nama: 'Masak Konvensional',
                        slug: 'masak-konvensional',
                        urutan: 2,
                        deskripsi: 'Memasak dengan peralatan tradisional'
                    },
                    {
                        nama: 'LKBB Tongkat',
                        slug: 'lkbb-tongkat',
                        urutan: 3,
                        deskripsi: 'Lomba Baris-Berbaris menggunakan tongkat'
                    },
                    {
                        nama: 'Cerdas Cermat',
                        slug: 'cerdas-cermat',
                        urutan: 4,
                        deskripsi: 'Kompetisi pengetahuan kepramukaan'
                    },
                    {
                        nama: 'Upcycle Art',
                        slug: 'upcycle-art',
                        urutan: 5,
                        deskripsi: 'Kreativitas daur ulang barang bekas'
                    },
                    {
                        nama: 'Desain Poster Digital',
                        slug: 'poster-digital',
                        urutan: 6,
                        deskripsi: 'Kompetisi desain poster digital'
                    }
                ],
                getLombaIcon: (slug) => {
                    const icons = {
                        'tapak-kemah': 'tent',
                        'masak-konvensional': 'chef-hat',
                        'lkbb-tongkat': 'users',
                        'cerdas-cermat': 'brain',
                        'upcycle-art': 'recycle',
                        'poster-digital': 'image',
                    };
                    return icons[slug] || 'award';
                }
            }
        }
    </script>
@endpush