<?php

namespace App\Http\Livewire\Collections;

use App\Contracts\HasVerbForms;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class VerbForms extends CollectionComponent
{
    use HasSlug;

    /** @var HasVerbForms */
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

    protected string $tabName = 'verb_forms';

    /**
     * @return Builder|Relation|Collection
     */
    protected function query()
    {
        $forms = $this->model->verbForms()->where('shape', 'LIKE', "%{$this->filter}%")->get();

        if ($this->filter) {
            $gaps = collect();
        } else {
            $gaps = $this->model->verbGaps;
        }

        return $forms->merge($gaps);
    }

    public function updatedFilter(): void
    {
        $this->page = 0;
    }

    public function renderTab(): View
    {
        return view('livewire.collections.verb-forms');
    }
}
