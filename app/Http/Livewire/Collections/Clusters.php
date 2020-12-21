<?php

namespace App\Http\Livewire\Collections;

use App\Models\Language;
use App\Http\Livewire\TabComponent;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class Clusters extends TabComponent
{
    public Language $model;

    /** @var Collection */
    public $clusters;

    /** @var Collection */
    public $paClusters;

    /** @var array */
    protected $listeners = [
        'tabChanged'
    ];

    protected string $tabName = 'clusters';

    public function mount(): void
    {
        $this->clusters = collect();
    }

    public function loadData(): void
    {
        $this->clusters = $this->model->clusters;

        foreach ($this->model->phonoids as $phonoid) {
            if (($phonoid->is_cluster || $phonoid->is_consonant) && $phonoid->parentsFromLanguage('PA')->some(fn ($parent) => $parent->is_cluster)) {
                $this->paClusters = Language::find('PA')->clusters;
                break;
            }
        }
    }

    public function renderTab(): View
    {
        return view('livewire.collections.clusters');
    }
}
