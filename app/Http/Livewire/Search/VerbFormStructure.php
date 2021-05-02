<?php

namespace App\Http\Livewire\Search;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class VerbFormStructure extends Component
{
    /** @var Collection */
    public $classes;

    /** @var Collection */
    public $orders;

    /** @var Collection */
    public $modes;

    /** @var Collection */
    public $features;

    /**
     * @param ?Collection $classes
     * @param ?Collection $orders
     * @param ?Collection $modes
     * @param ?Collection $features
     */
    public function mount($classes = null, $orders = null, $modes = null, $features = null): void
    {
        $this->classes = $classes ?? collect();
        $this->orders = $orders ?? collect();
        $this->modes = $modes ?? collect();
        $this->features = $features ?? collect();
    }

    public function render(): View
    {
        return view('livewire.search.verb-form-structure');
    }
}
