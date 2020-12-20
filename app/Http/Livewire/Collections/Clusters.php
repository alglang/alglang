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
    public $consonants;

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
        $this->consonants = collect();
    }

    public function tabChanged(string $tab): void
    {
        if ($tab === 'clusters' && !$this->loaded) {
            $this->clusters = $this->model->clusters;
            $this->consonants = $this->model->consonants;
            $this->paClusters = Language::find('PA')->clusters->where('is_archiphoneme', false);

            $this->loaded = true;
        }
    }

    public function render(): View
    {
        return view('livewire.collections.clusters');
    }
}
