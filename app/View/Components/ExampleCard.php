<?php

namespace App\View\Components;

use App\Models\Example;
use Illuminate\View\Component;

class ExampleCard extends Component
{
    /** @var Example */
    public $example;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Example $example)
    {
        $this->example = $example;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.example-card');
    }
}
