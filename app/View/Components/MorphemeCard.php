<?php

namespace App\View\Components;

use App\Models\Morpheme;
use Illuminate\View\Component;

class MorphemeCard extends Component
{
    /** @var Morpheme */
    public $morpheme;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Morpheme $morpheme)
    {
        $this->morpheme = $morpheme;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.morpheme-card');
    }
}
