<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileDownloadController extends Controller
{
    public function index(){
         return response()->download(
            storage_path('private/entraalmasalla-users.zip')
        );
    }
}
