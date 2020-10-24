<?php

namespace App\View\Components;

use App\Models\FormGap;
use App\Models\NominalForm;
use Illuminate\View\Component;

class NominalFormCard extends Component
{
    /** @var NominalForm|FormGap */
    public $form;

    /**
     * Create a new component instance.
     *
     * @param NominalForm|FormGap $form
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
        return view('components.nominal-form-card');
    }
}
