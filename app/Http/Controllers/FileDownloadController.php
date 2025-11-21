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

       public function download(Request $request)
    {
        // CONTRASEÑA FIJA
        $password = "Promo2024";

        if ($request->password !== $password) {
            return back()->withErrors(['password' => 'La contraseña es incorrecta']);
        }

        $file = storage_path('app/private/entraalmasalla-users.zip');

        if (!file_exists($file)) {
            abort(404);
        }

        return response()->download($file);
    }
}
