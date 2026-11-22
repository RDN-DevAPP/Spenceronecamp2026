<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialReport extends Model
{
    protected $fillable = ['season_id', 'title', 'file_path', 'is_active'];

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }
}
