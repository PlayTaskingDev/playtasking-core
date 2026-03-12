<?php

namespace App\View\Components\v2\ecommerce;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MonthlyTarget extends Component
{
    public function __construct()
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('components.v2.ecommerce.monthly-target');
    }
}




