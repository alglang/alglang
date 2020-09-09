<?php

namespace App\Http\Livewire\Languages;

use App\Models\Language;
use Livewire\Component;
use Illuminate\View\View;

class BasicDetails extends Component
{
    /** @var Language */
    public $language;

    public function mount(Language $language): void
    {
        $this->language = $language;
    }

    public function render(): View
    {
        return view('livewire.languages.basic-details');
    }
}
