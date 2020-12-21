<?php

namespace App\Http\Livewire\Collections;

use App\Http\Livewire\TabComponent;
use App\Models\Language;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class Phonemes extends TabComponent
{
    /** @var Language */
    public $model;

    /** @var Collection */
    public $vowels;

    /** @var Collection */
    public $consonants;

    /** @var Collection */
    public $archiphonemes;

    /** @var Collection */
    public $paVowels;

    /** @var Collection */
    public $paConsonants;

    /** @var Collection */
    public $paArchiphonemes;

    protected string $tabName = 'phonemes';

    public function mount(): void
    {
        $this->vowels = collect();
        $this->consonants = collect();
        $this->archiphonemes = collect();
    }

    public function loadData(): void
    {
        $phonoids = $this->model->phonoids;

        $this->archiphonemes = $phonoids->where('is_archiphoneme', true);
        $this->consonants = $this->model->consonants->where('is_archiphoneme', false);
        $this->vowels = $this->model->vowels->where('is_archiphoneme', false);

        if ($this->model->code !== 'PA') {
            $pa = Language::find('PA');

            if ($this->vowels->some(fn ($phoneme) => !$phoneme->parentsFromLanguage('PA')->isEmpty())) {
                $this->paVowels = $pa->vowels->where('is_archiphoneme', false);
            }

            if ($phonoids->some(fn ($phoneme) => $phoneme->parentsFromLanguage('PA')->some(fn ($parent) => $parent->is_consonant && !$parent->is_archiphoneme))) {
                $this->paConsonants = $pa->consonants->where('is_archiphoneme', false);
            }

            if ($phonoids->some(fn ($phoneme) => $phoneme->parentsFromLanguage('PA')->some(fn ($parent) => $parent->is_archiphoneme))) {
                $this->paArchiphonemes = $pa->phonemes->where('is_archiphoneme', true);
            }
        }
    }

    public function renderTab(): View
    {
        return view('livewire.collections.phonemes');
    }
}
