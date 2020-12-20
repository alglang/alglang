<?php

namespace App\Http\Livewire\Collections;

use App\Contracts\HasPhonemes;
use App\Models\Language;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Phonemes extends Component
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

    public bool $loaded = false;

    /** @var array */
    protected $listeners = [
        'tabChanged'
    ];

    /**
     * @param Language $model
     */
    public function mount($model): void
    {
        $this->model = $model;
        $this->vowels = collect();
        $this->consonants = collect();
        $this->archiphonemes = collect();
    }

    public function tabChanged(string $tab): void
    {
        if ($tab === 'phonemes' && !$this->loaded) {
            $phonoids = $this->model->phonoids;

            foreach ($phonoids as $phonoid) {
                if ($phonoid->is_archiphoneme) {
                    $this->archiphonemes->push($phonoid);
                } elseif ($phonoid->is_vowel) {
                    $this->vowels->push($phonoid);
                } elseif ($phonoid->is_consonant) {
                    $this->consonants->push($phonoid);
                }
            }

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


            $this->loaded = true;
        }
    }

    public function render(): View
    {
        return view('livewire.collections.phonemes');
    }
}
