<?php

namespace App\Http\Livewire;

use Illuminate\View\View;
use Livewire\Component;

class Map extends Component
{
    /** @var Iterable */
    public $locations;

    /** @var bool */
    public $showBorders = false;

    public function mount(Iterable $locations): void
    {
        $this->locations = $locations;
    }

    public function render(): View
    {
        return view('livewire.map');
    }

    public function toggleBorders(): void
    {
        $this->showBorders = !$this->showBorders;
    }
}
