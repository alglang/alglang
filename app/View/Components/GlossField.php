<?php

namespace App\View\Components;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\View\Component;

class GlossField extends Component
{
    /** @var Arrayable|array */
    public $glosses;

    /**
     * Create a new component instance.
     *
     * @param Arrayable|array $glosses
     *
     * @return void
     */
    public function __construct($glosses)
    {
        $this->glosses = $glosses;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.gloss-field');
    }
}
