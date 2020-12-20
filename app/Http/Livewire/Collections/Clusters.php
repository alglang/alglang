<?php

namespace App\Http\Livewire\Collections;

use App\Models\ClusterFeatureSet;
use App\Models\Language;
use App\Models\Phoneme;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Clusters extends Component
{
    public Language $model;

    /** @var Collection */
    public $clusters;

    /** @var Collection */
    public $paClusters;

    public bool $loaded = false;

    /** @var array */
    protected $listeners = [
        'tabChanged'
    ];

    public function mount(): void
    {
        $this->clusters = collect();
    }

    public function hydrateClusters(Collection $data): void
    {
        $this->clusters = $data->map(function ($data) {
            $cluster = new Phoneme($data);
            $cluster['language'] = new Language($data['language']);
            $cluster['features'] = new ClusterFeatureSet($data['features']);
            $cluster['features']['firstSegment'] = new Phoneme($data['features']['first_segment']);
            $cluster['features']['secondSegment'] = new Phoneme($data['features']['second_segment']);
            return $cluster;
        });
    }

    public function tabChanged(string $tab): void
    {
        if ($tab === 'clusters' && !$this->loaded) {
            $paFound = false;

            foreach ($this->model->phonoids()->with('features')->get() as $phonoid) {
                if ($phonoid->is_cluster) {
                    $this->clusters->push($phonoid);
                }

                if (!$paFound && ($phonoid->is_cluster || $phonoid->is_consonant) && $phonoid->parentsFromLanguage('PA')->some(fn ($parent) => $parent->is_cluster)) {
                    $this->paClusters = Language::find('PA')->clusters->where('is_archiphoneme', false);
                    $paFound = true;
                }
            }

            $this->loaded = true;
        }
    }

    public function render(): View
    {
        return view('livewire.collections.clusters');
    }
}
