<?php

namespace App\Http\Livewire\Search;

use App\Models\Language;
use App\Models\VerbClass;
use App\Models\VerbMode;
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

    /** @var VerbMode */
    public $modeQuery;

    /** @var Language */
    public $languageQuery;

    /** @var Collection */
    public $classes;

    /** @var Collection */
    public $orders;

    /** @var Collection */
    public $modes;

    /** @var Collection */
    public $languages;

    /** @var bool */
    public $affirmative;

    /** @var bool */
    public $negative;

    /** @var bool */
    public $diminutive;

    /** @var bool */
    public $advancedMode = false;

    /** @var array */
    protected $listeners = ['toggleMode'];

    public function mount(): void
    {
        $this->classes = VerbClass::all();
        $this->orders = VerbOrder::all();
        $this->modes = VerbMode::all();
        $this->languages = Language::all();
    }

    public function render(): View
    {
        return view('livewire.search.verb-paradigm');
    }

    public function toggleMode(): void
    {
        $this->advancedMode = !$this->advancedMode;
    }
}
