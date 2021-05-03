<?php

namespace App\Http\Livewire\Search;

use App\Models\Language;
use App\Models\VerbClass;
use App\Models\VerbOrder;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class VerbParadigm extends Component
{
    /** @var VerbClass */
    public $classQuery;

    /** @var VerbOrder */
    public $orderQuery;

    /** @var Language */
    public $languageQuery;

    /** @var Collection */
    public $classes;

    /** @var Collection */
    public $orders;

    /** @var Collection */
    public $languages;

    public function mount(): void
    {
        $this->classes = VerbClass::all();
        $this->orders = VerbOrder::all();
        $this->languages = Language::all();
    }

    public function render(): View
    {
        return view('livewire.search.verb-paradigm');
    }
}
