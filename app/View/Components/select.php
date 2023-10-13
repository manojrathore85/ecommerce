<?php

namespace App\View\Components;

use Illuminate\View\Component;

class select extends Component
{
    public $name;
    public $id;   
    public $label;
    public $errorname;
    public $errormessage;
    public $oldvalue;
    public $options;
    public $class;  
    public $selected;  
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $id, $label, $options, $errorname= '', $errormessage= '', $oldvalue= '', $class='form-control', $selected='' )
    {
        $this->name = $name;
        $this->id = $id;
        $this->label = $label;
        $this->errorname = $errorname;
        $this->errormessage = $errormessage;
        $this->oldvalue = $oldvalue;
        $this->options = $options;
        $this->class = $class;
        $this->selected = $selected;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.select');
    }
}
