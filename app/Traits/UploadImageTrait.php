<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Codesmiths\LaravelOcrSpace\OcrSpaceOptions;
use Codesmiths\LaravelOcrSpace\Facades\OcrSpace;
use Codesmiths\LaravelOcrSpace\Enums\Language;
use Codesmiths\LaravelOcrSpace\Enums\OcrSpaceEngine;

trait UploadImageTrait {

    public function uploadImage($disk, $folder, $file)
    {
        $disk = Storage::disk($disk);
        $path = $disk->put($folder, $file);
        return $disk->url($path);
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