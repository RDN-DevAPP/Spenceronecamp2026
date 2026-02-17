@extends('layouts.main')

@section('title', 'Edit Sponsor - Admin')

@section('content')
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Edit Sponsor</h1>

            <div class="bg-white p-6 rounded-lg shadow-md">
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.sponsorships.update', $sponsorship->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Sponsor</label>
                        <input type="text" name="name" id="name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-scout-accent focus:ring focus:ring-scout-accent focus:ring-opacity-50"
                            required value="{{ old('name', $sponsorship->name) }}">
                    </div>

                    <div class="mb-4">
                        <label for="tier" class="block text-sm font-medium text-gray-700">Tier Sponsorship</label>
                        <select name="tier" id="tier"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-scout-accent focus:ring focus:ring-scout-accent focus:ring-opacity-50"
                            required>
                            <option value="platinum" {{ old('tier', $sponsorship->tier) == 'platinum' ? 'selected' : '' }}>
                                Platinum</option>
                            <option value="gold" {{ old('tier', $sponsorship->tier) == 'gold' ? 'selected' : '' }}>Gold
                            </option>
                            <option value="silver" {{ old('tier', $sponsorship->tier) == 'silver' ? 'selected' : '' }}>Silver
                            </option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="logo" class="block text-sm font-medium text-gray-700">Logo (Biarkan kosong jika tidak
                            ingin mengubah)</label>
                        @if($sponsorship->logo)
                            <div class="mt-2 mb-2">
                                <img src="{{ Storage::url($sponsorship->logo) }}" alt="Current Logo"
                                    class="h-20 object-contain">
                            </div>
                        @endif
                        <input type="file" name="logo" id="logo" class="mt-1 block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-scout-primary file:text-white
                            hover:file:bg-scout-primary/90" accept="image/*">
                    </div>

                    <div class="mb-6">
                        <label for="website_url" class="block text-sm font-medium text-gray-700">Website URL
                            (Opsional)</label>
                        <input type="url" name="website_url" id="website_url"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-scout-accent focus:ring focus:ring-scout-accent focus:ring-opacity-50"
                            value="{{ old('website_url', $sponsorship->website_url) }}" placeholder="https://example.com">
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.sponsorships.index') }}"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition">Batal</a>
                        <button type="submit"
                            class="bg-scout-primary text-white px-4 py-2 rounded-md hover:bg-scout-primary/90 transition shadow-md">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection