<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileDownloadController extends Controller
{
    public function index(){
        return view('filedownload');
        //  return response()->download(
        //     storage_path('app/private/entraalmasalla-users.zip')
        // );
    }
}
