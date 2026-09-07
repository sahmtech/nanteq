<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ImageTrait
{
    public function storeImage($image, $path, $drive = 'public')
    {
        $extension = $image->getClientOriginalExtension();

        $filename = Str::uuid() . '.' . $extension;

        $path = Storage::disk($drive)->putFileAs($path, $image, $filename);

        return $path;
    }

    public function deleteImage($path, $drive = 'public')
    {
        if (Storage::disk($drive)->exists($path)) {
            Storage::disk($drive)->delete($path);
        }
    }
}
