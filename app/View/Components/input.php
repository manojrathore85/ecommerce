<?php

namespace App\View\Components;

use Illuminate\View\Component;

class input extends Component
{
    public $name;
    public $id;
    public $type;
    public $label;
    public $errorname;
    public $errormessage;
    public $value;
    //public $class;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $id, $type, $label ='', $errorname= '', $errormessage= '', $value='')
    {
        $this->name = $name;
        $this->id = $id;
        $this->type = $type;
        $this->label = $label;
        $this->errorname = $errorname;
        $this->errormessage = $errormessage;
        $this->value = $value;
        //$this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.input');
    }
}
