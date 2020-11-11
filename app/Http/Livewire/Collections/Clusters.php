<?php

namespace App\Http\Livewire\Collections;

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;
use Livewire\Component;

class Clusters extends Component
{
    public Model $model;

    public function render(): View
    {
        return view('livewire.collections.clusters');
    }
}
