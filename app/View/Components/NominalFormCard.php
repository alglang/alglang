<?php

namespace App\View\Components;

use App\Models\NominalForm;
use Illuminate\View\Component;

class NominalFormCard extends Component
{
    /** @var NominalForm */
    public $form;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(NominalForm $form)
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
