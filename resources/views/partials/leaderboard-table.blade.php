<div class="overflow-x-auto rounded-2xl border border-gray-200 shadow-sm">
    <table class="min-w-full divide-y divide-gray-200 text-left">
        <thead class="bg-brand-forest text-white text-xs uppercase tracking-wider font-extrabold">
            <tr>
                <th scope="col" class="px-4 sm:px-6 py-3.5 text-center w-20">Peringkat</th>
                <th scope="col" class="px-4 sm:px-6 py-3.5">Nama Regu</th>
                <th scope="col" class="px-4 sm:px-6 py-3.5 text-center">Kategori</th>
                <th scope="col" class="px-3 sm:px-4 py-3.5 text-center">🥇 Emas</th>
                <th scope="col" class="px-3 sm:px-4 py-3.5 text-center">🥈 Perak</th>
                <th scope="col" class="px-3 sm:px-4 py-3.5 text-center">🥉 Perunggu</th>
                <th scope="col" class="px-4 sm:px-6 py-3.5 text-center w-28 bg-brand-amber text-white">Total Poin</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 bg-white text-xs sm:text-sm">
            <template v-if="{{ $dataset }}.length > 0">
                <tr v-for="(item, index) in {{ $dataset }}" :key="item.reguProfile.id"
                    :class="item.peringkat <= 3 ? 'bg-amber-50/40 hover:bg-amber-50' : 'hover:bg-gray-50/80'"
                    class="transition-colors">
                    
                    <!-- Peringkat -->
                    <td class="px-4 sm:px-6 py-4 text-center whitespace-nowrap font-black">
                        <span v-if="item.peringkat === 1" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-tr from-amber-500 to-yellow-300 text-white shadow-md font-bold text-sm">
                            🥇
                        </span>
                        <span v-else-if="item.peringkat === 2" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-tr from-slate-400 to-slate-200 text-slate-800 shadow-md font-bold text-sm">
                            🥈
                        </span>
                        <span v-else-if="item.peringkat === 3" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-tr from-amber-700 to-amber-500 text-white shadow-md font-bold text-sm">
                            🥉
                        </span>
                        <span v-else class="text-gray-600 font-bold">
                            #@{{ item.peringkat }}
                        </span>
                    </td>

                    <!-- Nama Regu -->
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <template v-if="item.peringkat <= 3 && !revealLeaderboard">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-amber-200/80 text-amber-900 border border-amber-300 select-none shadow-xs">
                                    <i data-lucide="lock" class="w-3.5 h-3.5 text-amber-700"></i>
                                    <span>DIRAHASIAKAN (TOP 3)</span>
                                </span>
                                <span class="text-xs text-amber-700/70 italic font-medium hidden sm:inline">Disensor untuk kejutan</span>
                            </div>
                        </template>
                        <template v-else>
                            <div class="font-extrabold text-gray-900 flex items-center gap-2">
                                <span>@{{ item.reguProfile.nama_regu }}</span>
                                <span v-if="item.peringkat === 1" class="text-[10px] font-black px-2 py-0.5 rounded bg-amber-100 text-amber-800 border border-amber-300 uppercase">Juara 1</span>
                            </div>
                        </template>
                    </td>

                    <!-- Kategori (Putra/Putri) -->
                    <td class="px-4 sm:px-6 py-4 text-center whitespace-nowrap">
                        <span :class="item.reguProfile.jenis === 'putra' ? 'bg-blue-100 text-blue-800 border-blue-200' : 'bg-pink-100 text-pink-800 border-pink-200'"
                            class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold border uppercase tracking-wider">
                            @{{ item.reguProfile.jenis }}
                        </span>
                    </td>

                    <!-- Medali Emas -->
                    <td class="px-3 sm:px-4 py-4 text-center whitespace-nowrap font-bold">
                        <template v-if="item.peringkat <= 3 && !revealLeaderboard">
                            <span class="text-gray-400 font-mono">🔒</span>
                        </template>
                        <template v-else>
                            <span :class="item.emas > 0 ? 'text-amber-600 font-black' : 'text-gray-400'">
                                @{{ item.emas }}
                            </span>
                        </template>
                    </td>

                    <!-- Medali Perak -->
                    <td class="px-3 sm:px-4 py-4 text-center whitespace-nowrap font-bold">
                        <template v-if="item.peringkat <= 3 && !revealLeaderboard">
                            <span class="text-gray-400 font-mono">🔒</span>
                        </template>
                        <template v-else>
                            <span :class="item.perak > 0 ? 'text-slate-600 font-black' : 'text-gray-400'">
                                @{{ item.perak }}
                            </span>
                        </template>
                    </td>

                    <!-- Medali Perunggu -->
                    <td class="px-3 sm:px-4 py-4 text-center whitespace-nowrap font-bold">
                        <template v-if="item.peringkat <= 3 && !revealLeaderboard">
                            <span class="text-gray-400 font-mono">🔒</span>
                        </template>
                        <template v-else>
                            <span :class="item.perunggu > 0 ? 'text-amber-800 font-black' : 'text-gray-400'">
                                @{{ item.perunggu }}
                            </span>
                        </template>
                    </td>

                    <!-- Total Poin -->
                    <td class="px-4 sm:px-6 py-4 text-center whitespace-nowrap font-black bg-amber-50/60">
                        <template v-if="item.peringkat <= 3 && !revealLeaderboard">
                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-md bg-amber-200 text-amber-900 font-mono text-xs font-extrabold">
                                🔒 ? Poin
                            </span>
                        </template>
                        <template v-else>
                            <span class="text-base text-brand-forest font-black">
                                @{{ item.poin }} <span class="text-[11px] font-semibold text-gray-500">Poin</span>
                            </span>
                        </template>
                    </td>
                </tr>
            </template>
            <tr v-else>
                <td colspan="7" class="py-12 text-center text-gray-500 font-medium">
                    <i data-lucide="inbox" class="w-10 h-10 text-gray-300 mx-auto mb-2"></i>
                    Belum ada data nilai regu yang tercatat untuk kategori ini.
                </td>
            </tr>
        </tbody>
    </table>
</div>
