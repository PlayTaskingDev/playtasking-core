<?php

namespace App\Http\Controllers\PanelV2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PanelControllerV2 extends Controller
{
    public function index()
    {
        
        return view('panel.v2.index');
    }
}
