@php $isTemplate = $isTemplate ?? false; @endphp
<div class="rubrik-group bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
    <div class="px-4 sm:px-6 py-3 bg-gradient-to-r from-emerald-950 to-brand-forest flex flex-col sm:flex-row sm:items-center gap-3">
        <input type="text" value="{{ $kelompok }}" placeholder="Nama bagian, mis. I. MATERI PENILAIAN PBB"
            class="group-title flex-1 px-3 py-2 rounded-lg bg-white/10 text-white placeholder-white/50 border border-white/20 text-sm font-black uppercase tracking-wide focus:bg-white/20 focus:outline-none">
        <span class="text-xs text-emerald-100">Subtotal maks: <strong class="group-sum text-amber-300 text-base">0</strong></span>
        <button type="button" onclick="removeGroup(this)" class="text-rose-200 hover:text-white text-xs font-bold cursor-pointer">Hapus Bagian</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead class="bg-gray-50 text-gray-600 uppercase tracking-wide">
                <tr>
                    <th class="px-2 py-2 text-center w-10">No</th>
                    <th class="px-2 py-2 text-left min-w-[14rem]">Materi Penilaian</th>
                    @foreach($labels as $label)
                        <th class="px-1 py-2 text-center min-w-[4.5rem]">{{ $label }}</th>
                    @endforeach
                    <th class="px-2 py-2 text-center w-14">Maks</th>
                    <th class="px-2 py-2 w-10"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($items as $item)
                    @include('admin.pbb-rubrik._row', ['i' => $item->id, 'row' => $item, 'kelompok' => $kelompok, 'labels' => $labels])
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-4 sm:px-6 py-3 border-t border-gray-100 bg-gray-50/50">
        <button type="button" onclick="addRow(this)"
            class="btn-add-row inline-flex items-center gap-1.5 px-3 py-1.5 bg-white hover:bg-emerald-50 text-brand-forest border border-emerald-300 rounded-lg text-xs font-bold transition cursor-pointer">
            <i data-lucide="plus" class="w-3.5 h-3.5"></i> Tambah Poin
        </button>
    </div>
</div>
