@extends('layouts.main')

@section('title', 'Edit Sponsor - Admin Spencerone Camp 2026')

@section('content')
    <div class="py-10 min-h-screen">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1.5 text-xs text-gray-500">
                        <a href="{{ route('admin.sponsorships.index') }}" class="hover:text-brand-forest transition font-medium">Sponsorship</a>
                        <span>/</span>
                        <span class="text-brand-forest font-bold">Edit Sponsor</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black text-brand-forest uppercase tracking-tight">
                        Edit: {{ $sponsorship->name }}
                    </h1>
                </div>
                <a href="{{ route('admin.sponsorships.index') }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
                </a>
            </div>

            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-200/80 shadow-sm">
                @if (isset($errors) && $errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-xs sm:text-sm">
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.sponsorships.update', $sponsorship->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Nama Perusahaan / Sponsor <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-medium transition"
                            required value="{{ old('name', $sponsorship->name) }}">
                    </div>

                    <div>
                        <label for="tier" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Tier Sponsorship <span class="text-red-500">*</span></label>
                        <select name="tier" id="tier"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-medium transition"
                            required>
                            <option value="platinum" {{ old('tier', $sponsorship->tier) == 'platinum' ? 'selected' : '' }}>Platinum</option>
                            <option value="gold" {{ old('tier', $sponsorship->tier) == 'gold' ? 'selected' : '' }}>Gold</option>
                            <option value="silver" {{ old('tier', $sponsorship->tier) == 'silver' ? 'selected' : '' }}>Silver</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="pic_name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Nama PIC / Kontak</label>
                            <input type="text" name="pic_name" id="pic_name"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-medium transition"
                                value="{{ old('pic_name', $sponsorship->pic_name) }}">
                        </div>
                        <div>
                            <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Nomor WhatsApp / Telp</label>
                            <input type="text" name="phone" id="phone"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-medium transition"
                                value="{{ old('phone', $sponsorship->phone) }}">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Email PIC</label>
                        <input type="email" name="email" id="email"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-medium transition"
                            value="{{ old('email', $sponsorship->email) }}">
                    </div>

                    <div>
                        <label for="logo" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Logo Perusahaan</label>
                        @if($sponsorship->logo)
                            <div class="mb-2 p-2 bg-gray-50 border border-gray-200 rounded-xl inline-block">
                                <img src="{{ Storage::url($sponsorship->logo) }}" alt="Logo Saat Ini" class="h-12 object-contain">
                            </div>
                        @endif
                        <input type="file" name="logo" id="logo" class="block w-full text-xs text-gray-600 border border-gray-300 rounded-xl cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-brand-forest hover:file:bg-emerald-200 transition" accept="image/*">
                        <p class="mt-1 text-[11px] text-gray-400">Kosongkan jika tidak ingin mengganti logo.</p>
                    </div>

                    <div>
                        <label for="website_url" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Website URL (Opsional)</label>
                        <input type="url" name="website_url" id="website_url"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-medium transition"
                            value="{{ old('website_url', $sponsorship->website_url) }}" placeholder="https://perusahaan.com">
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row gap-2.5">
                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl font-bold text-xs bg-brand-forest hover:bg-emerald-900 text-white shadow-sm flex items-center justify-center gap-1.5 transition">
                            <i data-lucide="save" class="w-4 h-4 text-amber-400"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.sponsorships.index') }}"
                            class="px-5 py-2.5 rounded-xl font-semibold text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 text-center transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection