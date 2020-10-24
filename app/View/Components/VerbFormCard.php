<?php

namespace App\View\Components;

use App\Models\FormGap;
use App\Models\VerbForm;
use Illuminate\View\Component;

class VerbFormCard extends Component
{
    /** @var VerbForm|FormGap */
    public $form;

    /**
     * Create a new component instance.
     *
     * @param VerbForm|FormGap $form
     * @return void
     */
    public function __construct($form)
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
