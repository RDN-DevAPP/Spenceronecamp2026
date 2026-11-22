<?php

namespace App\Services;

use App\Models\Season;
use Illuminate\Support\Facades\Cache;

class ActiveSeasonService
{
    protected ?Season $activeSeason = null;

    /**
     * Dapatkan season yang sedang aktif saat ini.
     */
    public function getActiveSeason(): ?Season
    {
        if ($this->activeSeason !== null) {
            return $this->activeSeason;
        }

        $this->activeSeason = Season::where('is_active', true)->first();

        // Fallback ke season terbaru jika tidak ada yang ditandai aktif
        if (!$this->activeSeason) {
            $this->activeSeason = Season::latest()->first();
        }

        return $this->activeSeason;
    }

    /**
     * Dapatkan ID dari season aktif saat ini.
     */
    public function getActiveSeasonId(): ?int
    {
        return $this->getActiveSeason()?->id;
    }

    /**
     * Atur sebuah season menjadi aktif (menonaktifkan season lain).
     */
    public function setActiveSeason(Season $season): void
    {
        Season::query()->update(['is_active' => false]);
        $season->update(['is_active' => true]);
        $this->activeSeason = $season->fresh();
    }

    /**
     * Cek apakah ada season aktif.
     */
    public function hasActiveSeason(): bool
    {
        return $this->getActiveSeason() !== null;
    }
}
