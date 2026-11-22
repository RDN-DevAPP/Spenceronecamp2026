@extends('layouts.main')

@section('title', 'Kelola Sponsorship - Admin Spencerone Camp 2026')

@section('content')
    <div class="py-10 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200/60 text-xs font-semibold uppercase tracking-wider mb-2">
                        <i data-lucide="handshake" class="w-3.5 h-3.5 text-emerald-600"></i>
                        Kemitraan & Sponsor
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight">
                        Kelola Sponsorship
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Verifikasi permohonan kemitraan, validasi bukti transfer, dan atur tier publikasi.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('admin.sponsorships.settings') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-xs sm:text-sm text-emerald-950 bg-amber-400 hover:bg-amber-500 border border-amber-500/80 shadow-sm transition">
                        <i data-lucide="settings-2" class="w-4 h-4 text-emerald-950"></i>
                        <span>Atur Rekening & Keuntungan</span>
                    </a>
                    <a href="{{ route('admin.sponsorships.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-xs sm:text-sm text-white bg-brand-forest hover:bg-emerald-900 border border-emerald-800 shadow-sm transition">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Tambah Sponsor</span>
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 font-bold rounded-2xl shadow-2xs flex items-center gap-3 text-sm">
                    <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 text-emerald-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/70">
                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                        <i data-lucide="building" class="w-4 h-4 text-emerald-700"></i>
                        <span>Daftar Mitra Sponsorship</span>
                    </h3>
                    <span class="text-xs font-bold text-brand-forest bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200/60">
                        {{ count($sponsorships) }} Mitra
                    </span>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden divide-y divide-gray-100">
                    @forelse($sponsorships as $sponsor)
                        <div class="p-4 bg-white hover:bg-gray-50/60 transition">
                            <div class="flex items-center gap-3.5 mb-3">
                                <div class="w-12 h-12 flex items-center justify-center bg-gray-50 border border-gray-200/70 rounded-xl shrink-0 p-1">
                                    @if($sponsor->logo)
                                        <img src="{{ Storage::url($sponsor->logo) }}" alt="{{ $sponsor->name }}" class="max-h-10 max-w-10 object-contain">
                                    @else
                                        <i data-lucide="image-off" class="w-5 h-5 text-gray-300"></i>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-bold text-gray-900 truncate">{{ $sponsor->name }}</h3>
                                    <div class="flex items-center gap-1.5 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider
                                            {{ $sponsor->tier == 'platinum' ? 'bg-emerald-950 text-white' : '' }}
                                            {{ $sponsor->tier == 'gold' ? 'bg-amber-100 text-amber-900 border border-amber-300' : '' }}
                                            {{ $sponsor->tier == 'silver' ? 'bg-slate-100 text-slate-700 border border-slate-300' : '' }}
                                            {{ !$sponsor->tier ? 'bg-gray-100 text-gray-600' : '' }}">
                                            {{ $sponsor->tier ? ucfirst($sponsor->tier) : 'Menunggu' }}
                                        </span>
                                        @if(!$sponsor->is_approved)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">
                                                Pending
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 bg-gray-50/80 p-2.5 rounded-xl border border-gray-100 text-xs text-gray-600 space-y-0.5">
                                <p><span class="font-bold text-gray-700">PIC:</span> {{ $sponsor->pic_name ?? '-' }}</p>
                                <p><span class="font-bold text-gray-700">Email:</span> {{ $sponsor->email ?? '-' }}</p>
                                <p><span class="font-bold text-gray-700">Telp:</span> {{ $sponsor->phone ?? '-' }}</p>
                            </div>

                            @if($sponsor->receipt)
                                <div class="mb-3">
                                    <a href="{{ Storage::url($sponsor->receipt) }}" target="_blank"
                                        class="inline-flex items-center gap-1 text-xs font-bold text-emerald-800 hover:text-emerald-950 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i> Bukti Transfer
                                    </a>
                                </div>
                            @endif

                            @if(!$sponsor->is_approved)
                                <div class="mb-3 border border-amber-200/80 bg-amber-50/60 p-3 rounded-xl">
                                    <form action="{{ route('admin.sponsorships.approve', $sponsor->id) }}" method="POST" class="flex flex-col gap-2">
                                        @csrf
                                        <label class="text-[10px] font-bold text-amber-900 uppercase tracking-wider">Tetapkan Tier Sponsor:</label>
                                        <select name="tier" class="text-xs border-amber-300 rounded-xl py-1.5 px-2.5 bg-white" required>
                                            <option value="">Pilih Tier...</option>
                                            <option value="platinum">Platinum</option>
                                            <option value="gold">Gold</option>
                                            <option value="silver">Silver</option>
                                        </select>
                                        <button type="submit"
                                            class="bg-brand-forest text-white text-xs px-3 py-1.5 rounded-xl font-bold hover:bg-emerald-900 transition">
                                            Setujui & Publikasikan
                                        </button>
                                    </form>
                                </div>
                            @endif

                            <div class="flex justify-end items-center gap-3 pt-2 border-t border-gray-100 text-xs">
                                <a href="{{ route('admin.sponsorships.edit', $sponsor->id) }}"
                                    class="text-emerald-700 hover:text-emerald-900 font-bold flex items-center gap-1">
                                    <i data-lucide="edit" class="w-3.5 h-3.5"></i> Edit
                                </a>
                                <form action="{{ route('admin.sponsorships.destroy', $sponsor->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus sponsor ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-bold flex items-center gap-1">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-400">Belum ada data sponsorship.</div>
                    @endforelse
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/80 text-gray-600">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider w-16">Logo</th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Nama Sponsor</th>
                                <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold uppercase tracking-wider">Tier</th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Kontak PIC</th>
                                <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold uppercase tracking-wider">Bukti TF</th>
                                <th scope="col" class="px-6 py-3.5 text-right text-xs font-bold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($sponsorships as $sponsor)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="w-10 h-10 flex items-center justify-center bg-gray-50 border border-gray-200/60 rounded-xl p-1">
                                            @if($sponsor->logo)
                                                <img src="{{ Storage::url($sponsor->logo) }}" alt="{{ $sponsor->name }}" class="max-h-8 max-w-8 object-contain">
                                            @else
                                                <span class="text-[10px] text-gray-400">No Logo</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $sponsor->name }}</div>
                                        @if($sponsor->website_url)
                                            <a href="{{ $sponsor->website_url }}" target="_blank" class="text-xs text-emerald-700 hover:underline flex items-center gap-1 mt-0.5">
                                                <span>{{ Str::limit($sponsor->website_url, 25) }}</span>
                                                <i data-lucide="external-link" class="w-3 h-3"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2.5 py-0.5 inline-flex text-[10px] font-black uppercase tracking-wider rounded-md
                                            {{ $sponsor->tier == 'platinum' ? 'bg-emerald-950 text-white' : '' }}
                                            {{ $sponsor->tier == 'gold' ? 'bg-amber-100 text-amber-900 border border-amber-300' : '' }}
                                            {{ $sponsor->tier == 'silver' ? 'bg-slate-100 text-slate-700 border border-slate-300' : '' }}
                                            {{ !$sponsor->tier ? 'bg-gray-100 text-gray-600' : '' }}">
                                            {{ $sponsor->tier ? ucfirst($sponsor->tier) : 'Menunggu' }}
                                        </span>
                                        @if(!$sponsor->is_approved)
                                            <div class="mt-1">
                                                <span class="px-2 py-0.5 text-[9px] font-bold rounded bg-red-50 text-red-700 border border-red-200 inline-block">
                                                    Pending
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                        <div class="font-bold text-gray-900">{{ $sponsor->pic_name ?? '-' }}</div>
                                        <div class="text-gray-400">{{ $sponsor->email ?? '-' }}</div>
                                        <div class="text-gray-400">{{ $sponsor->phone ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-xs">
                                        @if($sponsor->receipt)
                                            <a href="{{ Storage::url($sponsor->receipt) }}" target="_blank"
                                                class="text-emerald-800 hover:text-emerald-950 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200 inline-flex items-center gap-1 font-bold">
                                                <i data-lucide="file-text" class="w-3.5 h-3.5"></i> Buka File
                                            </a>
                                        @else
                                            <span class="text-gray-300 font-light">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                        @if(!$sponsor->is_approved)
                                            <div class="mb-2">
                                                <button onclick="document.getElementById('approve-form-{{ $sponsor->id }}').classList.toggle('hidden')"
                                                    class="bg-emerald-50 text-brand-forest border border-emerald-200/80 px-2.5 py-1 rounded-lg text-xs font-bold hover:bg-emerald-100 transition">
                                                    Validasi & Setujui...
                                                </button>
                                            </div>
                                            <form id="approve-form-{{ $sponsor->id }}"
                                                action="{{ route('admin.sponsorships.approve', $sponsor->id) }}" method="POST"
                                                class="hidden mb-2 bg-emerald-50/60 p-2.5 rounded-xl border border-emerald-200/60 text-left">
                                                @csrf
                                                <label class="block text-[10px] font-bold text-gray-700 mb-1 uppercase tracking-wider">Tier:</label>
                                                <div class="flex items-center gap-1.5">
                                                    <select name="tier" class="text-xs border-gray-300 rounded-lg py-1 px-2 flex-1" required>
                                                        <option value="">Pilih Tier...</option>
                                                        <option value="platinum">Platinum</option>
                                                        <option value="gold">Gold</option>
                                                        <option value="silver">Silver</option>
                                                    </select>
                                                    <button type="submit" class="bg-brand-forest hover:bg-emerald-900 text-white p-1.5 rounded-lg transition" title="Publikasikan">
                                                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        @endif
                                        <div class="flex justify-end gap-2 items-center">
                                            <a href="{{ route('admin.sponsorships.edit', $sponsor->id) }}"
                                                class="text-emerald-700 hover:text-emerald-900 p-1.5 hover:bg-emerald-50 rounded-lg transition" title="Edit">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </a>
                                            <form action="{{ route('admin.sponsorships.destroy', $sponsor->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus sponsor ini?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 p-1.5 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                        <i data-lucide="handshake" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                                        <p class="text-sm font-semibold text-gray-700">Belum ada data sponsorship</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-brand-forest transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
@endsection