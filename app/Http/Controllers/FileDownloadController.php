<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileDownloadController extends Controller
{
    public function index(){
         return response()->download(
            global_storage_path('private/entraalmasalla-users.zip')
        );
    }
}
