<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileDownloadController extends Controller
{
    public function index(){
        return '<h2>Descargar</h2> <a href='.response()->download(storage_path('app/private/entraalmasalla-users.zip')).'>aquí</a>';
    }
}
