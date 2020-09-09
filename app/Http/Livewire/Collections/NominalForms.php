<?php

namespace App\Http\Livewire\Collections;

use App\Contracts\HasNominalForms;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class NominalForms extends Component
{
    use HasSlug;

    /** @var HasNominalForms */
    public $model;

    /** @var string */
    public $screenSize = 'xl';

    /** @var int */
    public $page = 0;

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

    /**
     * @return Builder|Relation
     */
    protected function filteredFormQuery()
    {
        return $this->model->nominalForms()->where('shape', 'LIKE', "%{$this->filter}%");
    }

    public function getFormsProperty(): Collection
    {
        return $this->filteredFormQuery()
                    ->skip($this->perPage() * $this->page)
                    ->take($this->perPage())
                    ->get();
    }

    public function hasMoreItems(): bool
    {
        return $this->filteredFormQuery()->count() > ($this->page + 1) * $this->perPage();
    }

    public function resize(string $size): void
    {
        $this->screenSize = $size;
    }

    public function perPage(): int
    {
        return static::maxSizeFor($this->screenSize);
    }

    public function prevPage(): void
    {
        if ($this->page > 0) {
            $this->page--;
        }
    }

    public function nextPage(): void
    {
        if ($this->hasMoreItems()) {
            $this->page++;
        }
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
