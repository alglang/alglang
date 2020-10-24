<?php

namespace App\Http\Livewire\Collections;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

/**
 * @property bool $hasMorePages
 */
abstract class CollectionComponent extends Component
{
    use HasSlug;

    /** @var string */
    public $screenSize = 'xl';

    /** @var int */
    public $page = 0;

    /** @var array */
    protected static $sizes;

    /**
     * @return Builder|Relation|Collection
     */
    abstract protected function query();

    abstract public function render(): View;

    public function getItemsProperty(): Collection
    {
        $items = $this->query()->skip($this->page * $this->perPage())->take($this->perPage());

        if ($items instanceof Collection) {
            return $items;
        }
        return $items->get();
    }

    public static function maxSizeFor(string $size): int
    {
        return static::$sizes[$size];
    }

    protected function perPage(): int
    {
        return static::maxSizeFor($this->screenSize);
    }

    public function getHasMorePagesProperty(): bool
    {
        return $this->query()->count() > ($this->page + 1) * $this->perPage();
    }

    public function nextPage(): void
    {
        if ($this->hasMorePages) {
            $this->page++;
        }
    }

    public function prevPage(): void
    {
        if ($this->page > 0) {
            $this->page--;
        }
    }

    public function resize(string $size): void
    {
        $this->screenSize = $size;
    }

    protected function resetPage(): void
    {
        $this->page = 0;
    }
}
