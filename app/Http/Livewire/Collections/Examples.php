<?php

namespace App\Http\Livewire\Collections;

use App\Contracts\HasExamples;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class Examples extends CollectionComponent
{
    use HasSlug;

    /** @var HasExamples */
    public $model;

    /** @var string */
    public $filter = '';

    /** @var array */
    protected static $sizes = [
        'xs' => 10,
        'sm' => 10,
        'md' => 16,
        'lg' => 24,
        'xl' => 48
    ];

    /** @var array */
    protected $listeners = ['tabChanged', 'resize'];

    protected string $tabName = 'examples';

    /**
     * @return Builder|Relation
     */
    protected function query()
    {
        return $this->model->examples()->where('shape', 'LIKE', "%{$this->filter}%");
    }

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function renderTab(): View
    {
        return view('livewire.collections.examples');
    }
}
