<?php

namespace App\View\Components;

use Illuminate\View\Component;

class toast extends Component
{   
    public $class;
    public $body;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($class, $body)
    {
        $this->class = $class;
        $this->body = $body;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.toast');
    }
}
