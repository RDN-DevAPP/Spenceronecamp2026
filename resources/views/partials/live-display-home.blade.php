<!-- LIVE DISPLAY & SCOREBOARD CONTAINER -->
<div class="space-y-8">
    
    <!-- Section Header & Tab Controls -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-emerald-900/40 pb-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-300 text-xs font-black uppercase tracking-wider mb-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                <span>Panggung Utama & Live Scoreboard</span>
            </div>
            <h2 class="text-2xl sm:text-4xl font-black text-white tracking-tight">
                Klasemen Juara & Voting Terfavorit
            </h2>
            <p class="text-xs sm:text-sm text-emerald-100/80 mt-1 max-w-2xl leading-relaxed">
                Pantau perolehan akumulasi medali emas, perak, dan perunggu secara real-time, serta berikan apresiasi suara voting untuk regu kepanduan terfavorit pilihan Anda.
            </p>
        </div>

        <!-- Desktop & Mobile Tabs Switcher -->
        <div class="flex flex-wrap items-center gap-2 bg-[#06180F] p-1.5 rounded-2xl border border-emerald-800/40 self-start md:self-auto">
            <button type="button" onclick="switchHomeTab('tab-putra')" id="btn-tab-putra"
                class="home-tab-btn px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center gap-1.5 bg-amber-500 text-emerald-950 shadow-md">
                <i data-lucide="trophy" class="w-3.5 h-3.5"></i>
                <span>Putra</span>
            </button>
            <button type="button" onclick="switchHomeTab('tab-putri')" id="btn-tab-putri"
                class="home-tab-btn px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center gap-1.5 text-emerald-200 hover:text-white hover:bg-white/5">
                <i data-lucide="trophy" class="w-3.5 h-3.5"></i>
                <span>Putri</span>
            </button>
            @if($isCombinedGender ?? false)
                <button type="button" onclick="switchHomeTab('tab-gabungan')" id="btn-tab-gabungan"
                    class="home-tab-btn px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center gap-1.5 text-emerald-200 hover:text-white hover:bg-white/5">
                    <i data-lucide="crown" class="w-3.5 h-3.5 text-amber-400"></i>
                    <span>Umum</span>
                </button>
            @endif
            <button type="button" onclick="switchHomeTab('tab-voting')" id="btn-tab-voting"
                class="home-tab-btn px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center gap-1.5 text-emerald-200 hover:text-white hover:bg-white/5">
                <i data-lucide="heart" class="w-3.5 h-3.5 text-rose-400 fill-rose-400"></i>
                <span>Vote Favorit</span>
            </button>
            <button type="button" onclick="switchHomeTab('tab-quiz')" id="btn-tab-quiz"
                class="home-tab-btn px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center gap-1.5 text-emerald-200 hover:text-white hover:bg-white/5">
                <i data-lucide="zap" class="w-3.5 h-3.5 text-cyan-400"></i>
                <span>Cerdas Cermat</span>
            </button>
        </div>
    </div>

    <!-- TAB 1: JUARA UMUM PUTRA -->
    <div id="content-tab-putra" class="home-tab-content space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-black uppercase tracking-widest text-blue-300 flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-blue-500"></span> Klasemen Juara Umum Regu Putra (Gudep 03.059)
            </h3>
            <span class="text-[11px] font-bold text-emerald-300/70">Perhitungan Skor & Medali Resmi</span>
        </div>
        <div class="bg-gradient-to-b from-[#0e2c1d]/60 to-[#071910]/80 p-4 sm:p-6 rounded-3xl border border-emerald-500/20 backdrop-blur-sm">
            @include('partials.live-display-leaderboard', ['data' => $juaraUmumPutra ?? [], 'gender' => 'putra', 'isRevealed' => $isRevealed ?? true])
        </div>
    </div>

    <!-- TAB 2: JUARA UMUM PUTRI -->
    <div id="content-tab-putri" class="home-tab-content hidden space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-black uppercase tracking-widest text-pink-300 flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-pink-500"></span> Klasemen Juara Umum Regu Putri (Gudep 03.060)
            </h3>
            <span class="text-[11px] font-bold text-emerald-300/70">Perhitungan Skor & Medali Resmi</span>
        </div>
        <div class="bg-gradient-to-b from-[#0e2c1d]/60 to-[#071910]/80 p-4 sm:p-6 rounded-3xl border border-emerald-500/20 backdrop-blur-sm">
            @include('partials.live-display-leaderboard', ['data' => $juaraUmumPutri ?? [], 'gender' => 'putri', 'isRevealed' => $isRevealed ?? true])
        </div>
    </div>

    @if($isCombinedGender ?? false)
        <!-- TAB 3: JUARA GABUNGAN -->
        <div id="content-tab-gabungan" class="home-tab-content hidden space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-black uppercase tracking-widest text-amber-300 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-400"></span> Klasemen Juara Umum Gabungan Putra & Putri
                </h3>
                <span class="text-[11px] font-bold text-amber-300/80">Kombinasi Seluruh Regu</span>
            </div>
            <div class="bg-gradient-to-b from-[#0e2c1d]/60 to-[#071910]/80 p-4 sm:p-6 rounded-3xl border border-emerald-500/20 backdrop-blur-sm">
                @include('partials.live-display-leaderboard', ['data' => $juaraUmumAll ?? [], 'gender' => 'all', 'isRevealed' => $isRevealed ?? true])
            </div>
        </div>
    @endif

    <!-- TAB 4: VOTING REGU TERFAVORIT -->
    <div id="content-tab-voting" class="home-tab-content hidden space-y-6">
        
        <!-- Status Bar Voting -->
        <div class="bg-gradient-to-r from-slate-900/95 via-emerald-950/90 to-slate-900 p-4 sm:p-5 rounded-2xl border border-emerald-500/30 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-500/20 border border-rose-500/40 text-rose-400 flex items-center justify-center shrink-0">
                    <i data-lucide="heart" class="w-5 h-5 fill-rose-400"></i>
                </div>
                <div>
                    <h4 class="font-black text-sm text-white">Voting Regu Terfavorit Spencerone Camp II</h4>
                    <p class="text-xs text-emerald-200/80">Setiap pengunjung dapat memberikan 1 suara setiap 24 jam untuk regu kepanduan terbaik.</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-black/60 border border-amber-400/30 text-amber-300 font-mono text-xs font-black">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-amber-400"></i>
                    <span>Total Vote:</span>
                    <span id="total-vote-counter">{{ $totalVotesCount ?? 0 }}</span>
                </div>

                @if($is_vote_count_locked ?? false)
                    <span class="px-3 py-1.5 rounded-xl bg-amber-950/80 border border-amber-500/50 text-amber-300 text-xs font-bold flex items-center gap-1"
                          title="{{ $unlock_at_formatted ? 'Jumlah vote akan dibuka pada ' . $unlock_at_formatted : 'Jumlah vote dirahasiakan oleh panitia' }}">
                        <i data-lucide="lock" class="w-3.5 h-3.5 text-amber-400"></i>
                        <span>Jumlah Suara Dikunci Panitia</span>
                    </span>
                @endif

                @if($is_voting_closed ?? false)
                    <span class="px-3 py-1.5 rounded-xl bg-red-950/80 border border-red-500/50 text-red-300 text-xs font-bold flex items-center gap-1"
                          title="{{ $voting_end_at_formatted ? 'Telah berakhir pada ' . $voting_end_at_formatted : 'Voting telah ditutup panitia' }}">
                        <i data-lucide="ban" class="w-3.5 h-3.5 text-red-400"></i>
                        <span>Voting Telah Ditutup</span>
                    </span>
                @endif

                @if($hasVoted ?? false)
                    <span class="px-3 py-1.5 rounded-xl bg-emerald-900/80 border border-emerald-500/50 text-emerald-200 text-xs font-bold flex items-center gap-1">
                        <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-400"></i>
                        <span>Anda Sudah Memilih</span>
                    </span>
                @endif
            </div>
        </div>

        <!-- Grid Cards Regu Partisipan -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" id="voting-cards-grid">
            @forelse($votingRegus ?? [] as $vRegu)
                <div class="regu-vote-card bg-gradient-to-br from-[#0c2419] to-[#06170f] rounded-2xl p-4 border {{ ($vRegu['is_my_vote'] ?? false) ? 'border-amber-400 shadow-amber-500/20 shadow-lg ring-2 ring-amber-400/40' : 'border-emerald-600/30 shadow-md' }} flex flex-col justify-between transition hover:border-amber-400/60" data-regu-id="{{ $vRegu['id'] }}">
                    <div>
                        <!-- Badge Gender & Kavling -->
                        <div class="flex items-center justify-between gap-1 mb-2.5">
                            <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $vRegu['jenis'] === 'putra' ? 'bg-blue-900/80 text-blue-200 border border-blue-700/50' : 'bg-pink-900/80 text-pink-200 border border-pink-700/50' }}">
                                {{ $vRegu['jenis'] }} • {{ $vRegu['nomor_kavling'] }}
                            </span>
                            <div class="flex items-center gap-1 bg-black/60 px-2 py-0.5 rounded-full border border-amber-400/30">
                                <i data-lucide="heart" class="w-3 h-3 text-rose-400 fill-rose-400"></i>
                                <span class="text-xs font-mono font-black text-amber-300" id="vote-count-{{ $vRegu['id'] }}">
                                    {{ $vRegu['votes_count'] }}
                                </span>
                            </div>
                        </div>

                        <!-- Foto Regu -->
                        <div class="aspect-[4/3] rounded-xl overflow-hidden bg-black/40 border border-white/10 relative mb-3 group">
                            @if(!empty($vRegu['foto_regu_path']))
                                <img src="{{ asset('storage/' . $vRegu['foto_regu_path']) }}" alt="Foto {{ $vRegu['nama_regu'] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 text-xs p-2 text-center">
                                    <i data-lucide="image" class="w-8 h-8 opacity-40 mb-1"></i>
                                    <span class="text-[10px]">Belum unggah foto</span>
                                </div>
                            @endif

                            @if($vRegu['is_my_vote'] ?? false)
                                <div class="absolute top-2 left-2 bg-amber-500 text-emerald-950 font-black text-[9px] px-2 py-0.5 rounded-full flex items-center gap-1 shadow">
                                    <i data-lucide="check" class="w-2.5 h-2.5"></i> Pilihan Anda
                                </div>
                            @endif
                        </div>

                        <!-- Nama Regu & Keterangan -->
                        <h4 class="font-black text-base text-white tracking-tight line-clamp-1 uppercase" title="{{ $vRegu['nama_regu'] }}">
                            {{ $vRegu['nama_regu'] }}
                        </h4>
                        <p class="text-[11px] text-emerald-300 font-mono mt-0.5">Regu #{{ $vRegu['nomor_regu'] }}</p>

                        @if(!empty($vRegu['foto_regu_caption']))
                            <p class="text-xs text-gray-300 italic mt-2 line-clamp-2 bg-black/30 p-2 rounded-lg border border-white/5">
                                "{{ $vRegu['foto_regu_caption'] }}"
                            </p>
                        @endif
                    </div>

                    <!-- Tombol Vote -->
                    <div class="mt-4 pt-3 border-t border-white/10">
                        @if($vRegu['is_my_vote'] ?? false)
                            <button disabled class="w-full py-2.5 px-3 rounded-xl bg-amber-500 text-emerald-950 font-black text-xs uppercase tracking-wider cursor-default flex items-center justify-center gap-1.5 shadow-sm">
                                <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                                <span>Pilihan Anda</span>
                            </button>
                        @elseif($is_voting_closed ?? false)
                            <button disabled class="w-full py-2.5 px-3 rounded-xl bg-red-950/60 border border-red-800/40 text-red-400 font-bold text-xs uppercase tracking-wider cursor-not-allowed flex items-center justify-center gap-1">
                                <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                                <span>Voting Ditutup</span>
                            </button>
                        @elseif($hasVoted ?? false)
                            <button disabled class="w-full py-2.5 px-3 rounded-xl bg-gray-800/80 text-gray-400 font-bold text-xs uppercase tracking-wider cursor-not-allowed flex items-center justify-center gap-1">
                                <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                                <span>Sudah Memilih</span>
                            </button>
                        @else
                            <button type="button" onclick="openHomeVoteModal({{ $vRegu['id'] }}, '{{ addslashes($vRegu['nama_regu']) }}')"
                                class="vote-btn-action w-full py-2.5 px-3 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-emerald-950 font-black text-xs uppercase tracking-wider shadow-md transition active:scale-95 flex items-center justify-center gap-1.5 cursor-pointer">
                                <i data-lucide="heart" class="w-4 h-4 fill-emerald-950"></i>
                                <span>Vote Regu Ini</span>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-gray-400 bg-white/5 rounded-2xl border border-white/10">
                    <i data-lucide="users" class="w-10 h-10 mx-auto text-gray-500 mb-2"></i>
                    <p class="text-sm font-semibold">Belum ada regu terdaftar untuk voting terfavorit.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- TAB 5: CERDAS CERMAT LIVE -->
    <div id="content-tab-quiz" class="home-tab-content hidden space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-black uppercase tracking-widest text-cyan-300 flex items-center gap-2">
                <i data-lucide="zap" class="w-4 h-4 text-cyan-400"></i> Klasemen Live Arena Cerdas Cermat
            </h3>
            <span class="text-[11px] font-bold text-emerald-300/70">Akumulasi Babak 1, 2, & 3</span>
        </div>

        <div class="bg-gradient-to-b from-[#0e2c1d]/60 to-[#071910]/80 rounded-3xl border border-emerald-500/20 overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-emerald-800/40 text-xs">
                    <thead class="bg-black/40 text-emerald-300 uppercase tracking-wider font-mono">
                        <tr>
                            <th class="px-4 py-3.5 text-center font-bold w-14">Rank</th>
                            <th class="px-5 py-3.5 text-left font-bold">Nama Regu</th>
                            <th class="px-3 py-3.5 text-center font-bold">Kategori</th>
                            <th class="px-4 py-3.5 text-center font-bold">Babak 1</th>
                            <th class="px-4 py-3.5 text-center font-bold">Babak 2</th>
                            <th class="px-4 py-3.5 text-center font-bold">Babak 3</th>
                            <th class="px-5 py-3.5 text-center font-black text-amber-400">Total Skor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-emerald-900/30 text-white font-medium">
                        @forelse($quizLeaderboard ?? [] as $qRank => $qItem)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-4 py-3 text-center font-mono font-bold">
                                    @if($qRank === 0)
                                        <span class="text-base">👑</span>
                                    @elseif($qRank === 1)
                                        <span class="text-base">🥈</span>
                                    @elseif($qRank === 2)
                                        <span class="text-base">🥉</span>
                                    @else
                                        #{{ $qRank + 1 }}
                                    @endif
                                </td>
                                <td class="px-5 py-3 font-bold text-sm tracking-tight">
                                    {{ $qItem['regu_name'] }}
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $qItem['jenis'] === 'putra' ? 'bg-blue-900/80 text-blue-200' : 'bg-pink-900/80 text-pink-200' }}">
                                        {{ $qItem['jenis'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center font-mono text-emerald-200">{{ $qItem['b1'] }}</td>
                                <td class="px-4 py-3 text-center font-mono text-emerald-200">{{ $qItem['b2'] }}</td>
                                <td class="px-4 py-3 text-center font-mono text-emerald-200">{{ $qItem['b3'] }}</td>
                                <td class="px-5 py-3 text-center font-mono font-black text-amber-400 text-sm bg-black/20">
                                    {{ $qItem['total'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                                    Belum ada skor cerdas cermat yang tersimpan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- VOTE MODAL DIALOG -->
<div id="home-vote-modal" class="fixed inset-0 z-[100] bg-black/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-gradient-to-br from-[#0c2419] to-[#06170f] rounded-3xl border-2 border-amber-400/50 p-6 sm:p-8 max-w-md w-full shadow-2xl relative text-white">
        
        <button type="button" onclick="closeHomeVoteModal()" class="absolute top-4 right-4 text-gray-400 hover:text-white p-1 rounded-xl hover:bg-white/10 transition">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>

        <div class="text-center mb-6">
            <div class="w-14 h-14 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center mx-auto mb-3 border border-amber-400/40">
                <i data-lucide="heart" class="w-7 h-7 fill-amber-400"></i>
            </div>
            <h3 class="text-lg sm:text-xl font-black text-white">Konfirmasi Suara Favorit</h3>
            <p class="text-xs text-emerald-200/80 mt-1">
                Anda akan memberikan suara untuk <strong class="text-amber-300" id="modal-regu-name">-</strong>
            </p>
        </div>

        <form id="home-vote-form" onsubmit="submitHomeVote(event)">
            <input type="hidden" id="modal-regu-id" value="">
            
            <div class="space-y-4 mb-6">
                <div>
                    <label for="voter-name-input" class="block text-xs font-bold text-emerald-200/90 uppercase tracking-wider mb-2">
                        Nama Lengkap / Identitas Anda <span class="text-amber-400">*</span>
                    </label>
                    <input type="text" id="voter-name-input" required minlength="2" maxlength="100"
                        placeholder="Contoh: Kak Wahyu / Alumni Angkatan 2022"
                        class="w-full px-4 py-3 rounded-xl bg-black/60 border border-emerald-500/40 text-white placeholder-gray-500 text-sm focus:border-amber-400 focus:outline-none focus:ring-1 focus:ring-amber-400 transition">
                    <p class="text-[11px] text-gray-400 mt-1">Nama ini dicatat pada riwayat verifikasi voting panitia.</p>
                </div>

                <div id="modal-error-message" class="hidden p-3 rounded-xl bg-rose-950/80 border border-rose-500/50 text-rose-300 text-xs"></div>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" onclick="closeHomeVoteModal()"
                    class="flex-1 py-3 px-4 rounded-xl border border-white/20 text-gray-300 hover:bg-white/10 text-xs font-bold transition">
                    Batal
                </button>
                <button type="submit" id="btn-submit-home-vote"
                    class="flex-1 py-3 px-4 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-emerald-950 text-xs font-black uppercase tracking-wider shadow-lg transition active:scale-95 flex items-center justify-center gap-1.5 cursor-pointer">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Kirim Vote</span>
                </button>
            </div>
        </form>
    </div>
</div>
