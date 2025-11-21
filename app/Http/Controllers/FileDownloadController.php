<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileDownloadController extends Controller
{
    public function index(){
        $path = storage_path('../storage/app/private/entraalmasalla-users.zip');
        abort_unless(file_exists($path), 404);
        return response()->download($path);

    }
}
