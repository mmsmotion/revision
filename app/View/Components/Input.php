<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Input extends Component
{
    public $name,$inputLabel,$value;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name="name",$inputLabel="Input Label",$value=null)
    {
        $this->name = $name;
        $this->inputLabel = $inputLabel;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.input');
    }
}
