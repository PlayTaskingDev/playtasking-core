<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\SaveMediaElementRequest;
use App\Models\MediaElement;
use App\Services\Admin\MediaUploadService;

class ResourcesController extends Controller
{
    public function index()
    {
        $mediaElements = MediaElement::query()
            ->latest()
            ->get();

        return view('admin.resources', [
            'title' => 'Panel | ' . trans('Media elements'),
            'description' => 'Admin Panel',
            'media_elements' => $mediaElements,
        ]);
    }

    public function create()
    {
        return view('admin.resources.edit', [
            'media_element' => new MediaElement(),
        ]);
    }

    public function store(
        SaveMediaElementRequest $request,
        MediaUploadService $mediaUploadService
    ) {
        foreach ($request->file('asset', []) as $file) {
            $mediaUploadService->upload(
                $file,
                'media_elements'
            );
        }

        return response()->json([
            'message' => 'File(s) uploaded successfully!',
        ]);
    }
}