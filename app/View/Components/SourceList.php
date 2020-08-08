<?php

namespace App\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class SourceList extends Component
{
    /** @var Collection */
    public $sources;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Collection $sources)
    {
        $this->sources = $sources;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.source-list');
    }
}
