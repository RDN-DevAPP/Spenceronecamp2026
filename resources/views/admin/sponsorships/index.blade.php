@extends('layouts.main')

@section('title', 'Kelola Sponsorship - Admin')

@section('content')
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Kelola Sponsorship</h1>
                <a href="{{ route('admin.sponsorships.create') }}"
                    class="bg-scout-primary text-white px-4 py-2 rounded-md hover:bg-scout-primary/90 transition shadow-md">
                    <i data-lucide="plus" class="inline-block w-4 h-4 mr-1"></i> Tambah Sponsor
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <!-- Mobile Card View -->
                <div class="md:hidden">
                    @forelse($sponsorships as $sponsor)
                        <div class="p-4 border-b border-gray-200">
                            <div class="flex items-center space-x-4 mb-3">
                                <div class="flex-shrink-0 h-12 w-12 flex items-center justify-center bg-gray-100 rounded-lg">
                                    @if($sponsor->logo)
                                        <img src="{{ Storage::url($sponsor->logo) }}" alt="{{ $sponsor->name }}"
                                            class="max-h-10 max-w-10 object-contain">
                                    @else
                                        <i data-lucide="image-off" class="w-6 h-6 text-gray-400"></i>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $sponsor->name }}</h3>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                        {{ $sponsor->tier == 'platinum' ? 'bg-gray-800 text-white' : '' }}
                                                        {{ $sponsor->tier == 'gold' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                        {{ $sponsor->tier == 'silver' ? 'bg-gray-100 text-gray-800' : '' }}
                                                        {{ $sponsor->is_approved && !$sponsor->tier ? 'bg-indigo-100 text-indigo-800' : '' }}">
                                        {{ $sponsor->tier ? ucfirst($sponsor->tier) : 'Menunggu' }}
                                    </span>
                                    @if(!$sponsor->is_approved)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                            Pending
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if($sponsor->website_url)
                                <a href="{{ $sponsor->website_url }}" target="_blank"
                                    class="text-sm text-blue-600 hover:text-blue-500 mb-3 block truncate">
                                    {{ $sponsor->website_url }}
                                </a>
                            @endif

                            <div class="mb-3 text-sm text-gray-600">
                                <p><span class="font-medium">PIC:</span> {{ $sponsor->pic_name ?? '-' }}</p>
                                <p><span class="font-medium">Email:</span> {{ $sponsor->email ?? '-' }}</p>
                                <p><span class="font-medium">Telp:</span> {{ $sponsor->phone ?? '-' }}</p>
                            </div>

                            @if($sponsor->receipt)
                                <div class="mb-3">
                                    <a href="{{ Storage::url($sponsor->receipt) }}" target="_blank"
                                        class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                        <i data-lucide="file-text" class="w-4 h-4 mr-1"></i> Lihat Bukti Transfer
                                    </a>
                                </div>
                            @endif

                            @if(!$sponsor->is_approved)
                                <div class="mb-3 border border-yellow-200 bg-yellow-50 p-3 rounded">
                                    <form action="{{ route('admin.sponsorships.approve', $sponsor->id) }}" method="POST"
                                        class="flex flex-col space-y-2">
                                        @csrf
                                        <label class="text-xs font-medium text-gray-700">Tetapkan Tier Sponsor:</label>
                                        <select name="tier" class="text-sm border-gray-300 rounded-md py-1" required>
                                            <option value="">Pilih Tier...</option>
                                            <option value="platinum">Platinum</option>
                                            <option value="gold">Gold</option>
                                            <option value="silver">Silver</option>
                                        </select>
                                        <button type="submit"
                                            class="bg-green-600 text-white text-xs px-3 py-1.5 rounded font-medium hover:bg-green-700 w-full">Setujui
                                            & Publikasikan</button>
                                    </form>
                                </div>
                            @endif

                            <div class="flex justify-end space-x-3 mt-2 border-t pt-2 border-gray-100">
                                <a href="{{ route('admin.sponsorships.edit', $sponsor->id) }}"
                                    class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                                <form action="{{ route('admin.sponsorships.destroy', $sponsor->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus sponsor ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-900 text-sm font-medium">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-gray-500">Belum ada data sponsorship.</div>
                    @endforelse
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Logo
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama
                                    Sponsor</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tier
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kontak PIC</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bukti TF</th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($sponsorships as $sponsor)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($sponsor->logo)
                                            <img src="{{ Storage::url($sponsor->logo) }}" alt="{{ $sponsor->name }}"
                                                class="h-10 w-auto object-contain">
                                        @else
                                            <span class="text-gray-400">No Logo</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $sponsor->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                {{ $sponsor->tier == 'platinum' ? 'bg-gray-800 text-white' : '' }}
                                                                {{ $sponsor->tier == 'gold' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                                {{ $sponsor->tier == 'silver' ? 'bg-gray-100 text-gray-800' : '' }}
                                                                {{ $sponsor->is_approved && !$sponsor->tier ? 'bg-indigo-100 text-indigo-800' : '' }}">
                                            {{ $sponsor->tier ? ucfirst($sponsor->tier) : 'Menunggu' }}
                                        </span>
                                        @if(!$sponsor->is_approved)
                                            <div class="mt-1">
                                                <span
                                                    class="px-2 inline-flex text-[10px] leading-4 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Pending Status
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="text-sm text-gray-900 font-medium">{{ $sponsor->pic_name ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $sponsor->email ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $sponsor->phone ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($sponsor->receipt)
                                            <a href="{{ Storage::url($sponsor->receipt) }}" target="_blank"
                                                class="text-indigo-600 hover:text-indigo-900 flex items-center">
                                                <i data-lucide="file-text" class="w-4 h-4 mr-1"></i> Buka File
                                            </a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if(!$sponsor->is_approved)
                                            <div class="flex justify-end mb-2">
                                                <button
                                                    onclick="document.getElementById('approve-form-{{ $sponsor->id }}').classList.toggle('hidden')"
                                                    class="bg-green-100 text-green-700 px-3 py-1 rounded text-xs font-bold hover:bg-green-200 uppercase tracking-wider">
                                                    Validasi & Setujui...
                                                </button>
                                            </div>
                                            <form id="approve-form-{{ $sponsor->id }}"
                                                action="{{ route('admin.sponsorships.approve', $sponsor->id) }}" method="POST"
                                                class="hidden mb-3 border border-green-200 bg-green-50 p-2 rounded text-left">
                                                @csrf
                                                <label
                                                    class="block text-[10px] font-bold text-gray-700 mb-1 uppercase tracking-wide">Pilih
                                                    Tier Sponsor:</label>
                                                <div class="flex items-center space-x-2">
                                                    <select name="tier" class="text-sm border-gray-300 rounded-md py-1 flex-1"
                                                        required>
                                                        <option value="">Pilih Tier / Tingkatan...</option>
                                                        <option value="platinum">Platinum</option>
                                                        <option value="gold">Gold</option>
                                                        <option value="silver">Silver</option>
                                                    </select>
                                                    <button type="submit"
                                                        class="bg-green-600 text-white p-1.5 rounded hover:bg-green-700 shadow-sm"
                                                        title="Publikasikan Sponsor"><i data-lucide="check"
                                                            class="w-4 h-4"></i></button>
                                                </div>
                                            </form>
                                            <div class="border-t border-gray-100 mb-2 mt-2"></div>
                                        @endif
                                        <div class="flex justify-end gap-3 items-center">
                                            @if($sponsor->website_url)
                                                <a href="{{ $sponsor->website_url }}" target="_blank" title="Buka Website"
                                                    class="text-gray-400 hover:text-gray-600" aria-label="Website Link"><i
                                                        data-lucide="external-link" class="w-4 h-4"></i></a>
                                            @endif
                                            <a href="{{ route('admin.sponsorships.edit', $sponsor->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <form action="{{ route('admin.sponsorships.destroy', $sponsor->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus sponsor ini?');"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data sponsorship.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900 flex items-center">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
@endsection