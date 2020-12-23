<?php

namespace App\Http\Livewire\Collections;

use App\Models\Source;
use App\Contracts\HasSources;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class Sources extends CollectionComponent
{
    use HasSlug;

    /** @var string */
    public $filter = '';

    /** @var HasSources */
    public $model;

    /** @var array */
    protected $listeners = ['tabChanged', 'resize'];

    protected string $tabName = 'sources';

    protected static $sizes = [
        'xs' => 20,
        'sm' => 20,
        'md' => 100,
        'lg' => 180,
        'xl' => 224
    ];

    /**
     * @return Builder|Relation
     */
    protected function query()
    {
        $query = isset($this->model) ? $this->model->sources() : Source::query();
        return $query->where(DB::raw('CONCAT(author, " ", year)'), 'LIKE', "%{$this->filter}%");
    }

    public function renderTab(): \Illuminate\View\View
    {
        return view('livewire.collections.sources');
    }
}
