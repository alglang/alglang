<?php

namespace App\View\Components;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class PhonemeTable extends Component
{
    public Collection $items;

    public string $colKey;

    public string $rowKey;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Collection $items, string $colKey, string $rowKey)
    {
        $this->items = $items;
        $this->colKey = $colKey;
        $this->rowKey = $rowKey;
    }

    public function colHeaders(): Collection
    {
        return $this->items->pluck($this->colKey)->unique();
    }

    public function rowHeaders(): Collection
    {
        return $this->items->pluck($this->rowKey)->unique();
    }

    public function filterItems(object $row, object $col): Collection
    {
        return $this->items->where($this->rowKey, $row)->where($this->colKey, $col);
    }

    public function colName(): string
    {
        return Arr::last(explode('.', $this->colKey));
    }

    public function rowName(): string
    {
        return Arr::last(explode('.', $this->rowKey));
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.phoneme-table');
    }
}
