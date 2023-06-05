<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Storage;

trait Image
{
    public function generateUrl($path, $isFilePublic = false)
    {
        try {
            if (is_null($path) || empty($path)) {
                return '';
            }

            if ($isFilePublic) {
                return Storage::url($path);
            }

            return Storage::temporaryUrl(
                $path,
                now()->addMinutes(15)
            );
        } catch (\Throwable $th) {
            return '';
        }
    }
}
