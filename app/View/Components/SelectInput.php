<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectInput extends Component
{
    public $name;
    public $label;
    public $options; 
    public $selected;
    public $attributes; 

    public function __construct($name, $label, $options = [], $selected = null, $attributes = [])
    {
        $this->name = $name;
        $this->label = $label;
        $this->options = $options;
        $this->selected = $selected;
        $this->attributes = $attributes;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-input');
    }
}
