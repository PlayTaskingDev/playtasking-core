<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait UploadImageTrait {

    public function uploadImage($disk, $folder, $file)
    {
        $disk = Storage::disk($disk);
        $path = $disk->put($folder, $file);
        return $disk->url($path);
    }

}