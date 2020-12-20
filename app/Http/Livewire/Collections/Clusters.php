<?php

namespace App\Http\Livewire\Collections;

use App\Models\Language;
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

    public function tabChanged(string $tab): void
    {
        if ($tab === 'clusters' && !$this->loaded) {
            $paFound = false;

            foreach ($this->model->phonoids as $phonoid) {
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
