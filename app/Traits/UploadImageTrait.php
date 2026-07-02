<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Codesmiths\LaravelOcrSpace\OcrSpaceOptions;
use Codesmiths\LaravelOcrSpace\Facades\OcrSpace;
use Codesmiths\LaravelOcrSpace\Enums\Language;
use Codesmiths\LaravelOcrSpace\Enums\OcrSpaceEngine;
use Illuminate\Support\Str;

trait UploadImageTrait {

    public function uploadImage($disk, $folder, $file, $compress = false)
    {
        $diskInstance = Storage::disk($disk);

        if ($compress) {
            $manager = new ImageManager(new Driver());

            $image = $manager->read($file->getRealPath());
            $image->scale(width: 1200);

            $encoded = $image->toJpeg(quality: 85);

            $filename = Str::uuid() . '.jpg';
            $path = trim($folder, '/') . '/' . $filename;

            $diskInstance->put($path, $encoded->toString(), [
                'visibility' => 'public',
                'ContentType' => 'image/webp',
            ]);

            return $diskInstance->url($path);
        }

        $path = $diskInstance->put($folder, $file);

        return $diskInstance->url($path);
    }

    public function ocr_scan($binary_image,$mime_type)
    {
        $options = OcrSpaceOptions::make()
            ->fileType($mime_type)
            ->language(Language::Spanish)
            ->OCREngine(OcrSpaceEngine::Engine2);;

        $result = OcrSpace::parseBinaryImage(
            $binary_image,
            $options,
        );

        return $result;
    }

}