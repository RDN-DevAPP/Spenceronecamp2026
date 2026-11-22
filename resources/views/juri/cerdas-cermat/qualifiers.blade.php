@extends('layouts.main')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-gray-900">Leaderboard Kualifikasi & Finalis</h1>
                    <p class="text-sm text-gray-500 mt-1">Akumulasi Nilai Babak 1 + Babak 2 dengan pemecah seri waktu tercepat (Tie-Breaker).</p>
                </div>
                <div class="flex items-center gap-3">
                    <form action="{{ route('juri.cerdas-cermat.select-finalists') }}" method="POST" onsubmit="return confirm('Pilih otomatis Top 3 Finalis Babak Rebutan berdasarkan total skor tertinggi dan akumulasi waktu tercepat?');">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-emerald-950 font-black rounded-xl text-xs uppercase tracking-wider shadow transition flex items-center gap-2">
                            <i data-lucide="crown" class="w-4 h-4"></i>
                            Tetapkan Top 3 Finalis
                        </button>
                    </form>
                    <a href="{{ route('juri.cerdas-cermat.index') }}" class="text-gray-600 hover:text-gray-900 border border-gray-300 bg-white px-4 py-2 rounded-xl font-bold text-xs shadow-sm">
                        &larr; Kembali
                    </a>
                </div>
            </div>

            {{-- Qualifiers Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p class="mb-4 text-gray-600">Berikut adalah 5 besar regu dari Babak 1. Semua regu di bawah telah otomatis terkualifikasi untuk Babak 2 — Juri cukup memulai Babak 2 dari panel kontrol.</p>

                    <div class="overflow-x-auto">
                        {{-- Mobile View --}}
                        <div class="md:hidden divide-y divide-gray-200">
                            @forelse($qualifiers as $index => $session)
                                <div class="p-4 bg-white hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-scout-primary text-white font-black">
                                                {{ $index + 1 }}
                                            </span>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $session->reguProfile->nama_regu }}</div>
                                                <div class="text-xs text-gray-500">Regu {{ $session->reguProfile->nomor_regu }}</div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-0.5">Skor B.1</div>
                                            <div class="text-lg font-black text-scout-primary leading-none w-full text-right">{{ $session->score_round_1 }}</div>
                                            <div class="text-[10px] text-gray-500 font-bold mt-1 tracking-wide"><i data-lucide="timer" class="w-3 h-3 inline mb-0.5"></i> {{ floor($session->time_used_seconds / 60) }}m {{ $session->time_used_seconds % 60 }}s</div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <span class="px-2 py-1 inline-flex text-xs font-bold rounded bg-green-100 text-green-800">
                                            <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i> Lolos Babak 2
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-gray-500">Belum ada data skor Babak 1.</div>
                            @endforelse
                        </div>

                        {{-- Desktop View --}}
                        <table class="hidden md:table min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Peringkat</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Regu</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Skor B.1</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Skor B.2</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Total Skor</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Akumulasi Waktu (Tie-Breaker)</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($qualifiers as $index => $session)
                                    <tr class="{{ $index < 3 ? 'bg-amber-50/40 font-semibold' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($index < 3)
                                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-500 text-white font-black text-xs shadow-sm">
                                                    {{ $index + 1 }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gray-100 text-gray-700 font-bold text-xs">
                                                    {{ $index + 1 }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $session->reguProfile->nama_regu }}</div>
                                            <div class="text-xs text-gray-500">Regu {{ $session->reguProfile->nomor_regu }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-700">
                                            {{ $session->score_round_1 ?? 0 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-700">
                                            {{ $session->score_round_2 ?? 0 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="text-base font-black text-emerald-800">{{ $session->total_score ?? (($session->score_round_1 ?? 0) + ($session->score_round_2 ?? 0)) }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-xs text-gray-500 font-mono">
                                            <i data-lucide="timer" class="w-3.5 h-3.5 inline mb-0.5 text-gray-400"></i>
                                            {{ floor($session->time_used_seconds / 60) }}m {{ $session->time_used_seconds % 60 }}s
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($session->is_verified_round_3 || $index < 3)
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-black rounded-full bg-amber-100 text-amber-900 border border-amber-300 shadow-sm">
                                                    <i data-lucide="crown" class="w-3.5 h-3.5 mr-1 text-amber-600"></i> Top 3 Finalis
                                                </span>
                                            @else
                                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600">
                                                    Peserta
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada data skor Babak 1.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Real-Time Violations Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></div>
                        <h2 class="text-lg font-bold text-gray-900">Laporan Pelanggaran Peserta</h2>
                        <span id="violation-badge" class="hidden px-2 py-0.5 text-xs font-bold rounded-full bg-red-100 text-red-700">0</span>
                    </div>
                    <div class="flex items-center space-x-2 text-xs text-gray-400">
                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5" id="refresh-icon"></i>
                        <span id="last-updated">Memuat...</span>
                    </div>
                </div>

                <div class="p-6">
                    {{-- Filter Bar --}}
                    <div class="flex flex-wrap gap-2 mb-4" id="violation-filters">
                        <button onclick="filterViolations('all')" data-filter="all" class="filter-btn active-filter px-3 py-1 text-xs font-bold rounded-full bg-gray-800 text-white transition">Semua</button>
                        <button onclick="filterViolations('tab_switch')" data-filter="tab_switch" class="filter-btn px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition">Keluar Tab</button>
                        <button onclick="filterViolations('copy')" data-filter="copy" class="filter-btn px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition">Copy</button>
                        <button onclick="filterViolations('paste')" data-filter="paste" class="filter-btn px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition">Paste</button>
                        <button onclick="filterViolations('screenshot')" data-filter="screenshot" class="filter-btn px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition">Screenshot</button>
                        <button onclick="filterViolations('devtools')" data-filter="devtools" class="filter-btn px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition">Dev Tools</button>
                    </div>

                    {{-- Desktop Violations Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm" id="violations-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Regu</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Babak</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Pelanggaran</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="violations-tbody" class="bg-white divide-y divide-gray-100">
                                <tr id="violations-empty-row">
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                                        <i data-lucide="shield-check" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                        <p class="font-medium">Belum ada pelanggaran tercatat</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

@push('scripts')
<style>
    .violation-row {
        transition: background-color 0.3s ease;
    }
    .violation-row.new-entry {
        background-color: #fef3c7;
    }
    .filter-btn.active-filter {
        background-color: #1f2937;
        color: white;
    }
    #refresh-icon.spinning {
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
<script>
const violationsUrl = '{{ route("juri.cerdas-cermat.violations") }}';
let allViolations = [];
let currentFilter = 'all';
let latestId = 0;

const badgeColors = {
    tab_switch:   'bg-orange-100 text-orange-800',
    copy:         'bg-yellow-100 text-yellow-800',
    paste:        'bg-yellow-100 text-yellow-800',
    cut:          'bg-yellow-100 text-yellow-800',
    context_menu: 'bg-gray-100 text-gray-700',
    screenshot:   'bg-red-100 text-red-800',
    devtools:     'bg-purple-100 text-purple-800',
    other:        'bg-blue-100 text-blue-700',
};

function renderViolations(data) {
    const tbody = document.getElementById('violations-tbody');
    const emptyRow = document.getElementById('violations-empty-row');
    const badge = document.getElementById('violation-badge');

    const filtered = currentFilter === 'all' ? data : data.filter(v => v.violation_type === currentFilter);

    // Update badge
    if (data.length > 0) {
        badge.classList.remove('hidden');
        badge.textContent = data.length;
    }

    if (filtered.length === 0) {
        emptyRow.classList.remove('hidden');
        // Remove all violation rows (keep only empty row)
        tbody.querySelectorAll('.violation-row').forEach(r => r.remove());
        return;
    }

    emptyRow.classList.add('hidden');
    // Remove old rows
    tbody.querySelectorAll('.violation-row').forEach(r => r.remove());

    filtered.forEach(v => {
        const colorClass = badgeColors[v.violation_type] || 'bg-blue-100 text-blue-700';
        const isNew = v.id > latestId;
        const tr = document.createElement('tr');
        tr.className = 'violation-row' + (isNew ? ' new-entry' : '');
        tr.dataset.type = v.violation_type;
        tr.innerHTML = `
            <td class="px-4 py-3 whitespace-nowrap text-gray-500 font-mono text-xs">${v.time}</td>
            <td class="px-4 py-3 whitespace-nowrap">
                <div class="font-bold text-gray-900">${v.regu}</div>
                <div class="text-xs text-gray-400">No. ${v.nomor_regu}</div>
            </td>
            <td class="px-4 py-3 whitespace-nowrap text-center">
                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-scout-primary text-white text-xs font-bold">${v.round}</span>
            </td>
            <td class="px-4 py-3 whitespace-nowrap">
                <span class="px-2 py-0.5 text-xs font-bold rounded-full ${colorClass}">${v.label}</span>
            </td>
            <td class="px-4 py-3 text-xs text-gray-500">${v.description || '—'}</td>
        `;
        tbody.appendChild(tr);

        // Fade out the new-entry highlight after 3s
        if (isNew) {
            setTimeout(() => tr.classList.remove('new-entry'), 3000);
        }
    });

    // Update latestId
    if (data.length > 0) {
        latestId = Math.max(...data.map(v => v.id));
    }
}

function filterViolations(type) {
    currentFilter = type;
    // Update active button
    document.querySelectorAll('.filter-btn').forEach(btn => {
        if (btn.dataset.filter === type) {
            btn.classList.add('active-filter');
            btn.classList.remove('bg-gray-100', 'text-gray-600');
            btn.classList.add('bg-gray-800', 'text-white');
        } else {
            btn.classList.remove('active-filter', 'bg-gray-800', 'text-white');
            btn.classList.add('bg-gray-100', 'text-gray-600');
        }
    });
    renderViolations(allViolations);
}

async function fetchViolations() {
    const icon = document.getElementById('refresh-icon');
    const lastUpdated = document.getElementById('last-updated');
    icon.classList.add('spinning');

    try {
        const response = await fetch(violationsUrl, {
            headers: { 'Accept': 'application/json' }
        });
        const data = await response.json();
        if (data.success) {
            allViolations = data.violations;
            renderViolations(allViolations);
        }
    } catch (e) {
        console.error('Failed to fetch violations', e);
    } finally {
        icon.classList.remove('spinning');
        const now = new Date();
        lastUpdated.textContent = 'Update: ' + now.toLocaleTimeString('id-ID');
    }

    // Re-initialize lucide icons for new rows
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

// Initial load + poll every 5 seconds
fetchViolations();
setInterval(fetchViolations, 5000);
</script>
@endpush

@endsection
