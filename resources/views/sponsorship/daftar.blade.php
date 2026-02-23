@extends('layouts.main')

@section('title', 'Pendaftaran Sponsorship - LT-I Spencerone Camp 2026')

@section('content')
    <div class="py-12 bg-scout-surface min-h-screen flex items-center justify-center">
        <div class="max-w-3xl w-full mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold text-scout-primary tracking-tight sm:text-4xl">
                    Pendaftaran Sponsorship
                </h1>
                <p class="mt-4 text-lg text-gray-500">
                    Dukung kesuksesan LT-I Spencerone Camp 2026. Lengkapi form di bawah ini untuk mengajukan pendaftaran
                    sponsorship.
                </p>
            </div>

            <div class="bg-white py-8 px-6 shadow-xl rounded-2xl sm:px-10 border border-gray-100">
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i data-lucide="alert-circle" class="h-5 w-5 text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pada isian form:</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('sponsorship.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                        <!-- Email -->
                        <div class="sm:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Utama (Aktif)</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="mail" class="h-5 w-5 text-gray-400"></i>
                                </div>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    class="focus:ring-scout-accent focus:border-scout-accent block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3"
                                    placeholder="contoh@perusahaan.com">
                            </div>
                        </div>

                        <!-- Nama PIC -->
                        <div>
                            <label for="pic_name" class="block text-sm font-medium text-gray-700">Nama Lengkap Anda
                                (PIC)</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="user" class="h-5 w-5 text-gray-400"></i>
                                </div>
                                <input type="text" name="pic_name" id="pic_name" value="{{ old('pic_name') }}" required
                                    class="focus:ring-scout-accent focus:border-scout-accent block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3"
                                    placeholder="Nama PIC">
                            </div>
                        </div>

                        <!-- No Telp/WA -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon / WhatsApp
                                <span class="text-gray-400 text-xs font-normal">(Opsional)</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="phone" class="h-5 w-5 text-gray-400"></i>
                                </div>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                    class="focus:ring-scout-accent focus:border-scout-accent block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3"
                                    placeholder="08123xxxxxxx">
                            </div>
                        </div>

                        <!-- Nama / Brand / Produk -->
                        <div class="sm:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Perusahaan / Brand /
                                Produk</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="briefcase" class="h-5 w-5 text-gray-400"></i>
                                </div>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="focus:ring-scout-accent focus:border-scout-accent block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3"
                                    placeholder="Nama akan ditampilkan di beranda utama">
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 mt-8 mb-4 pt-6"></div>

                    <div class="space-y-6">
                        <!-- Logo Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Upload Logo / Gambar Brand</label>
                            <div
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md bg-gray-50 hover:bg-gray-100 transition">
                                <div class="space-y-2 text-center">
                                    <i data-lucide="image" class="mx-auto h-12 w-12 text-gray-400"></i>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="logo"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-scout-primary hover:text-indigo-500 focus-within:outline-none px-2 py-1 shadow-sm border border-gray-200">
                                            <span>Pilih Gambar</span>
                                            <input id="logo" name="logo" type="file" class="sr-only" required
                                                accept="image/*"
                                                onchange="document.getElementById('logo-filename').textContent = this.files[0].name">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500" id="logo-filename">
                                        PNG, JPG, GIF up to 2MB
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Receipt Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Bukti Transfer (Sponsorship)</label>
                            <div
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md bg-gray-50 hover:bg-gray-100 transition">
                                <div class="space-y-2 text-center">
                                    <i data-lucide="receipt" class="mx-auto h-12 w-12 text-gray-400"></i>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="receipt"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-scout-primary hover:text-indigo-500 focus-within:outline-none px-2 py-1 shadow-sm border border-gray-200">
                                            <span>Upload Bukti TF</span>
                                            <input id="receipt" name="receipt" type="file" class="sr-only" required
                                                accept="image/*,.pdf"
                                                onchange="document.getElementById('receipt-filename').textContent = this.files[0].name">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500" id="receipt-filename">
                                        PNG, JPG, PDF up to 3MB
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-scout-primary hover:bg-scout-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-scout-accent transition-colors">
                            Kirim Form Sponsorship
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="font-medium text-scout-primary hover:text-indigo-500">
                    &larr; Kembali ke Beranda
                </a>
            </div>

        </div>
    </div>
@endsection