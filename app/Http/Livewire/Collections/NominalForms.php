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

    protected string $tabName = 'nominal_forms';

    /** @var array */
    protected $listeners = ['tabChanged', 'resize'];

    protected function query(): Collection
    {
        $forms = $this->model->nominalForms()->where('shape', 'LIKE', "%{$this->filter}%")->get();

        if ($this->filter) {
            $gaps = collect();
        } else {
            $gaps = $this->model->nominalGaps;
        }

        return $forms->merge($gaps);
    }

    public function updatedFilter(): void
    {
        $this->page = 0;
    }

    public function renderTab(): View
    {
        return view('livewire.collections.nominal-forms');
    }
}
