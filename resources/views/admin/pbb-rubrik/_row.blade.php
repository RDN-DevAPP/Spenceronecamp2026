<tr class="hover:bg-gray-50/60">
    <td class="px-2 py-1.5 text-center font-bold text-gray-500 row-no"></td>
    <td class="px-2 py-1.5">
        <input type="hidden" name="rows[{{ $i }}][id]" value="{{ $row?->id }}">
        <input type="hidden" name="rows[{{ $i }}][kelompok]" class="row-kelompok" value="{{ $kelompok }}">
        <input type="text" name="rows[{{ $i }}][nama_kriteria]" value="{{ $row?->nama_kriteria }}" required maxlength="255"
            class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-xs font-semibold focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500/30">
    </td>
    @foreach($labels as $idx => $label)
        <td class="px-1 py-1.5">
            <input type="number" step="0.01" min="0" max="1000" required
                name="rows[{{ $i }}][rubrik][{{ $idx }}]"
                value="{{ $row ? (float) ($row->rubrik[$idx] ?? 0) : 0 }}"
                class="lvl w-full px-1 py-1.5 border border-gray-200 rounded-lg text-xs text-center font-bold focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500/30">
        </td>
    @endforeach
    <td class="px-2 py-1.5 text-center font-black text-brand-forest row-max">0</td>
    <td class="px-2 py-1.5 text-center">
        <button type="button" onclick="removeRow(this)" title="Hapus poin" class="text-rose-500 hover:text-rose-700 cursor-pointer">
            <i data-lucide="trash-2" class="w-4 h-4"></i>
        </button>
    </td>
</tr>
