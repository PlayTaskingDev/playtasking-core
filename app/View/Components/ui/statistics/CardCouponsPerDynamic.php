<?php

namespace App\View\Components\ui\statistics;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CardCouponsPerDynamic extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ui.statistics.card-coupons-per-dynamic');
    }
}
