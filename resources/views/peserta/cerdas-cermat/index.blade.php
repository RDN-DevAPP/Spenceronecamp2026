@extends('layouts.peserta')

@section('title', 'Daftar Cerdas Cermat - LT-I 2026')

@section('content')
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden border border-scout-secondary">
            <div class="bg-scout-primary px-6 py-4 border-b border-scout-secondary flex items-center">
                <div class="bg-scout-accent p-2 rounded-full mr-3 text-scout-primary">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <h2 class="text-xl font-bold text-white">Pendaftaran Lomba Cerdas Cermat</h2>
            </div>

            <div class="p-8">
                <div class="mb-8 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i data-lucide="info" class="h-5 w-5 text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-yellow-800">Ketentuan Lomba</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Setiap regu wajib mengirimkan 3 orang perwakilan.</li>
                                    <li>Lomba terdiri dari 3 Babak: Penyisihan (Pilihan Ganda), Semifinal (Isian Singkat),
                                        dan Final (Uraian).</li>
                                    <li>Pastikan nama yang didaftarkan sesuai dengan data anggota regu.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('peserta.cerdas-cermat.register') }}" method="POST" id="registrationForm">
                    @csrf
                    <div class="space-y-6">
                        @if($anggota->isEmpty())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <strong class="font-bold">Perhatian!</strong>
                                <span class="block sm:inline">Anda belum memiliki anggota regu yang terdaftar. Silakan tambahkan
                                    anggota regu terlebih dahulu di Dashboard.</span>
                            </div>
                        @else
                            @for ($i = 1; $i <= 3; $i++)
                                <div>
                                    <label for="name_{{ $i }}" class="block text-sm font-medium text-gray-700">Nama Peserta
                                        {{ $i }}</label>
                                    <select name="name_{{ $i }}" id="name_{{ $i }}" required onchange="updateDropdowns()"
                                        class="member-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-scout-primary focus:ring focus:ring-scout-primary focus:ring-opacity-50">
                                        <option value="">-- Pilih Anggota --</option>
                                        @foreach ($anggota as $member)
                                            <option value="{{ $member->nama }}">{{ $member->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endfor
                        @endif
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                            class="bg-scout-primary text-white px-6 py-3 rounded-lg font-bold hover:bg-scout-primary/90 transition shadow-lg flex items-center">
                            <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                            Daftarkan Tim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function updateDropdowns() {
                const selects = document.querySelectorAll('.member-select');
                const selectedValues = Array.from(selects).map(select => select.value).filter(value => value !== "");

                selects.forEach(select => {
                    const currentVal = select.value;
                    const options = select.querySelectorAll('option');

                    options.forEach(option => {
                        if (option.value === "") return; // Skip placeholder

                        if (selectedValues.includes(option.value) && option.value !== currentVal) {
                            option.style.display = 'none';
                            // Also disable it to be sure, though display none usually hides it from view
                            option.disabled = true;
                        } else {
                            option.style.display = 'block';
                            option.disabled = false;
                        }
                    });
                });
            }

            // Initial call to set state if old values exist (e.g. after validation error)
            document.addEventListener('DOMContentLoaded', updateDropdowns);
        </script>
    @endpush
@endsection