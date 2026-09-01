<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\MediaUploadService;
use Illuminate\Http\Request;

class MediaUploadController extends Controller
{
    public function store(
        Request $request,
        MediaUploadService $mediaUploadService
    ) {
        $request->validate([
            'file' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp,gif',
                'max:4096',
            ],
        ]);

        $media = $mediaUploadService->upload(
            $request->file('file'),
            'media_elements'
        );

        return response()->json([
            'location' => $media->asset,
        ]);
    }
}