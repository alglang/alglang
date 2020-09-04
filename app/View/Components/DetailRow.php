<?php

namespace App\View\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class DetailRow extends Component
{
    /** @var string */
    public $label;

    /** @var string */
    public $ariaId;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $label)
    {
        $this->label = $label;
        $this->ariaId = Str::of($this->label)->lower()->replace(' ', '-') . '-detail-row';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.detail-row');
    }
}
