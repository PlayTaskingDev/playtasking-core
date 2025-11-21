<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileDownloadController extends Controller
{
    public function index(){
         return view('filedownload',[
            'title'         => 'File Download',
        ]);
    }
}
