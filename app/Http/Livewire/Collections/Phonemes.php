<?php

namespace App\Http\Livewire\Collections;

use App\Models\ConsonantFeatureSet;
use App\Models\ConsonantManner;
use App\Models\ConsonantPlace;
use App\Models\Language;
use App\Models\Phoneme;
use App\Models\VowelBackness;
use App\Models\VowelFeatureSet;
use App\Models\VowelHeight;
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

    public function hydrate(): void
    {
        $this->vowels = $this->vowels->map(function ($data) {
            $vowel = new Phoneme($data);
            $vowel['language'] = new Language($data['language']);
            $vowel['features'] = new VowelFeatureSet($data['features']);
            $vowel['features']['backness'] = new VowelBackness($data['features']['backness']);
            $vowel['features']['height'] = new VowelHeight($data['features']['height']);
            return $vowel;
        });

        $this->consonants = $this->consonants->map(function ($data) {
            $consonant = new Phoneme($data);
            $consonant['language'] = new Language($data['language']);
            $consonant['features'] = new ConsonantFeatureSet($data['features']);
            $consonant['features']['manner'] = new ConsonantManner($data['features']['manner']);
            $consonant['features']['place'] = new ConsonantPlace($data['features']['place']);
            return $consonant;
        });

        $this->archiphonemes = $this->archiphonemes->map(function ($data) {
            $archiphoneme = new Phoneme($data);
            $archiphoneme['language'] = new Language($data['language']);

            if ($archiphoneme->is_consonant) {
                $archiphoneme['features'] = new ConsonantFeatureSet($data['features']);
            } elseif ($archiphoneme->is_vowel) {
                $archiphoneme['features'] = new VowelFeatureSet($data['features']);
            }

            return $archiphoneme;
        });
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
