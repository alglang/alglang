<?php

namespace App\Http\Livewire\Collections;

use App\Contracts\HasExamples;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Examples extends Component
{
    /** @var HasExamples */
    public $model;

    /** @var int */
    public $page = 0;

    /** @var string */
    public $screenSize = 'xl';

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

    public static function maxSizeFor(string $size): int
    {
        return static::$sizes[$size];
    }

    public function nextPage(): void
    {
        if ($this->hasMoreItems()) {
            $this->page++;
        }
    }

    public function prevPage(): void
    {
        if ($this->page > 0) {
            $this->page--;
        }
    }

    public function hasMoreItems(): bool
    {
        return $this->query()->count() > ($this->page + 1) * $this->perPage();
    }

    public function getExamplesProperty(): Collection
    {
        return $this->query()->skip($this->page * $this->perPage())->take($this->perPage())->get();
    }

    protected function perPage(): int
    {
        return static::maxSizeFor($this->screenSize);
    }

    /**
     * @return Builder|Relation
     */
    protected function query()
    {
        return $this->model->examples()->where('shape', 'LIKE', "%{$this->filter}%");
    }

    public function updatedFilter(): void
    {
        $this->page = 0;
    }

    public function resize(string $size): void
    {
        $this->screenSize = $size;
    }

    public function render(): View
    {
        return view('livewire.collections.examples');
    }
}
