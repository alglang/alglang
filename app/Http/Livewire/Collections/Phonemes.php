<?php

namespace App\Http\Livewire\Collections;

use App\Contracts\HasPhonemes;
use Illuminate\View\View;
use Livewire\Component;

class Phonemes extends Component
{
    /** @var HasPhonemes */
    public $model;

    public function render(): View
    {
        return view('livewire.collections.phonemes');
    }
}
