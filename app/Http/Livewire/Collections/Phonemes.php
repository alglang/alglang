<?php

namespace App\Http\Livewire\Collections;

use App\Contracts\HasPhonemes;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Phonemes extends Component
{
    /** @var HasPhonemes */
    public $model;

    /** @var Collection */
    public $vowels;

    /** @var Collection */
    public $consonants;

    /** @var Collection */
    public $phonemes;

    /** @var Collection */
    public $phonoids;

    /** @var array */
    protected $listeners = [
        'tabChanged'
    ];

    /**
     * @param HasPhonemes $model
     */
    public function mount($model): void
    {
        $this->model = $model;
        $this->vowels = collect();
        $this->consonants = collect();
        $this->phonemes = collect();
        $this->phonoids = collect();
    }

    public function tabChanged(string $tab): void
    {
        if ($tab === 'phonemes') {
            $this->vowels = $this->model->vowels;
            $this->consonants = $this->model->consonants;
            $this->phonemes = $this->model->phonemes;
            $this->phonoids = $this->model->phonoids;
        }
    }

    public function render(): View
    {
        return view('livewire.collections.phonemes');
    }
}
