<?php

namespace App\View\Components;

use App\Models\VerbForm;
use Illuminate\View\Component;

class VerbFormCard extends Component
{
    /** @var VerbForm */
    public $form;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(VerbForm $form)
    {
        $this->form = $form;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.verb-form-card');
    }
}
