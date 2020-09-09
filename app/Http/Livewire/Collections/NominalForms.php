<?php

namespace App\Http\Livewire\Collections;

use App\Contracts\HasNominalForms;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class NominalForms extends CollectionComponent
{
    use HasSlug;

    /** @var HasNominalForms */
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
    protected $listeners = ['resize'];

    /**
     * @return Builder|Relation
     */
    protected function query()
    {
        return $this->model->nominalForms()->where('shape', 'LIKE', "%{$this->filter}%");
    }

    public function updatedFilter(): void
    {
        $this->page = 0;
    }

    public function render(): View
    {
        return view('livewire.collections.nominal-forms');
    }
}
