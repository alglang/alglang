<?php

namespace App\Http\Livewire\Search;

use App\Models\Feature;
use App\Models\Language;
use App\Models\VerbClass;
use App\Models\VerbMode;
use App\Models\VerbOrder;
use App\Models\VerbStructure;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class VerbForm extends Component
{
    /** @var Collection */
    public $languages;

    /** @var Collection */
    public $classes;

    /** @var Collection */
    public $orders;

    /** @var Collection */
    public $modes;

    /** @var Collection */
    public $features;

    /** @var Collection */
    public $languageQueries;

    /** @var Collection */
    public $structureQueries;

    public function mount(): void
    {
        $this->languages = Language::all();
        $this->classes = VerbClass::all();
        $this->orders = VerbOrder::all();
        $this->modes = VerbMode::all();
        $this->features = Feature::all();

        $this->languageQueries = collect();
        $this->structureQueries = collect([$this->generateStructureQuery()]);
    }

    public function hydrateStructureQueries(Collection $value): void
    {
        $this->structureQueries = $value->map(function (array $params) {
            $subject = null;
            $primaryObject = null;
            $secondaryObject = null;

            if (array_key_exists('subject', $params) && is_array($params['subject'])) {
                $subject = new Feature(is_array($params['subject']) ? $params['subject'] : $params['subject']->toArray());
            }

            if (array_key_exists('primaryObject', $params) && is_array($params['primaryObject'])) {
                $primaryObject = new Feature($params['primaryObject']);
            }

            if (array_key_exists('secondaryObject', $params) && is_array($params['secondaryObject'])) {
                $secondaryObject = new Feature($params['secondaryObject']);
            }

            return new VerbStructure(array_merge($params, compact('subject', 'primaryObject', 'secondaryObject')));
        });
    }

    protected function generateStructureQuery(): VerbStructure
    {
        $query = new VerbStructure([
            'class_abv' => 'AI',
            'subject_name' => '1',
            'order_name' => 'Conjunct',
            'mode_name' => 'Indicative',
            'is_negative' => false,
            'is_diminutive' => false
        ]);

        $query->setRelation('subject', $this->features->first(fn ($el) => $el->name === '1'));

        return $query;
    }

    public function updated(string $name, string $value): void
    {
        if (!preg_match('/structureQueries\\.(\\d+)\\.(.+)/', $name, $matches)) {
            return;
        }

        $query = $this->structureQueries[intval($matches[1])];

        switch ($matches[2]) {
        case 'subject_name':
            $query->subject = $this->features->first(fn ($el) => $el->name === $value);
            break;
        case 'primary_object_name':
            $query->primaryObject = $this->features->first(fn ($el) => $el->name === $value);
            break;
        case 'secondary_object_name':
            $query->secondaryObject = $this->features->first(fn ($el) => $el->name === $value);
            break;
        default:
            break;
        };
    }

    public function addStructureQuery(): void
    {
        $this->structureQueries->push($this->generateStructureQuery());
    }

    public function removeStructureQuery(): void
    {
        if ($this->structureQueries->count() > 1) {
            $this->structureQueries->pop();
        }
    }

    public function render(): View
    {
        return view('livewire.search.verb-form');
    }
}
