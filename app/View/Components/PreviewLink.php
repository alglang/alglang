<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PreviewLink extends Component
{
    public $model;

    public $class;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($model, $class = '')
    {
        $this->model = $model;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.preview-link');
    }
}
