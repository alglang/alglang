<?php

namespace App\Http\Livewire\Search;

use App\Models\Language;
use App\Models\NominalParadigmType;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class NominalParadigm extends Component
{
    /** @var Collection */
    public $languageQueries;

    /** @var Collection */
    public $paradigmQueries;

    /** @var Collection */
    public $languages;

    /** @var Collection */
    public $paradigmTypes;

    public function mount(): void
    {
        $this->languages = Language::all();
        $this->paradigmTypes = NominalParadigmType::all();
    }

    public function render(): View
    {
        return view('livewire.search.nominal-paradigm');
    }
}
