<?php

namespace App\Http\Livewire\Collections;

use App\Contracts\HasMorphemes;
use App\Models\Language;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class Morphemes extends CollectionComponent
{
    /** @var HasMorphemes */
    public $model;

    /** @var array */
    protected $listeners = ['resize'];

    protected static $sizes = [
        'xs' => 10,
        'sm' => 10,
        'md' => 14,
        'lg' => 27,
        'xl' => 56
    ];

    protected function query(): Relation
    {
        return $this->model->morphemes();
    }

    public function render(): View
    {
        return view('livewire.collections.morphemes');
    }
}
