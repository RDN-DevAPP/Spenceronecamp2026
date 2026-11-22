<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKaryaLombaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isRegu();
    }

    public function rules(): array
    {
        return [
            'mata_lomba_slug' => ['required', 'string', 'in:hasta-karya,tapak-kemah,masak-rimba'],
            'judul_karya' => ['required', 'string', 'max:255'],
            'kategori_bahan' => ['nullable', 'string', 'max:100'],
            'rincian_bahan_alat' => ['nullable', 'string'],
            'fungsi_guna' => ['nullable', 'string'],
            'deskripsi_filosofi' => ['nullable', 'string'],
            'resep_gizi' => ['nullable', 'string'],
            'creator_id' => ['nullable', 'exists:anggota_regu,id'],
            'foto_utama' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
            'foto_detail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
            'foto_proses' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
            'foto_extra' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
        ];
    }
}
