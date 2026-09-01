<?php

namespace App\Services\Admin;

use App\Models\MediaElement;
use App\Traits\UploadImageTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class MediaUploadService
{
    use UploadImageTrait;

    public function upload(
        UploadedFile $file,
        string $folder = 'media_elements'
    ): MediaElement {

        $url = $this->uploadImage(
            'gcs',
            $folder,
            $file
        );

        return MediaElement::create([
            'asset' => $url,

            'mime_type' =>
                $file->getClientMimeType(),

            'description' =>
                $this->sanitizeFileName($file),
        ]);
    }

    private function sanitizeFileName(
        UploadedFile $file
    ): string {

        $name = Str::slug(
            pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_FILENAME
            )
        );

        return $name . '.'
            . $file->getClientOriginalExtension();
    }
}