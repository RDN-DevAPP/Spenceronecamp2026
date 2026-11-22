<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KaryaStorageService
{
    /**
     * Store an uploaded photo into season-2/karya/{regu_id}/{lomba_slug}/
     */
    public function storeKaryaPhoto(UploadedFile $file, int $reguId, string $lombaSlug, string $type = 'utama'): string
    {
        $folder = "season-2/karya/{$reguId}/{$lombaSlug}";
        $filename = "{$type}_" . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        
        return $file->storeAs($folder, $filename, 'public');
    }

    /**
     * Delete an existing photo from public disk if exists.
     */
    public function deletePhoto(?string $path): bool
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }
}
