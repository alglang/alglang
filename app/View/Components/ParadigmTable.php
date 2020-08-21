<?php

namespace App\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class ParadigmTable extends Component
{
    /** @var Collection */
    public $forms;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Collection $forms)
    {
        $this->forms = $forms;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.paradigm-table');
    }
}
