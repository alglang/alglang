<?php

namespace App\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class MorphemeTable extends Component
{
    /** @var Collection */
    public $morphemes;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Collection $morphemes)
    {
        $this->morphemes = $morphemes;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.morpheme-table');
    }
}
