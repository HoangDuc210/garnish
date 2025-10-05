<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TopPage extends Component
{
    /**
     * Name of page
     *
     * @var string
     */
    public $name;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('components.top-page');
    }

    /**
     * Introduction of page
     *
     * @return string
     */
    public function description()
    {
        return '';
    }
}
