<?php

namespace App\View\Components;

use App\Models\Phoneme;
use Illuminate\View\Component;

class ReflexGraph extends Component
{
    public Phoneme $phoneme;
    public bool $showParents;
    public bool $showChildren;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Phoneme $phoneme, bool $showParents = true, bool $showChildren = true)
    {
        $this->phoneme = $phoneme;
        $this->showParents = $showParents;
        $this->showChildren = $showChildren;
    }

    public function isRoot(): bool
    {
        return $this->showParents && $this->showChildren;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.reflex-graph');
    }
}
