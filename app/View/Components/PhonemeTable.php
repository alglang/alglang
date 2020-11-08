<?php

namespace App\View\Components;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class PhonemeTable extends Component
{
    public Collection $items;

    public string $colKey;

    public string $rowKey;

    public string $colAccessor;

    public string $rowAccessor;

    private string $colOrderKey;

    private string $rowOrderKey;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        Collection $items,
        string $colKey,
        string $rowKey,
        string $colAccessor = 'name',
        string $rowAccessor = 'name',
        string $colOrderKey = 'order_key',
        string $rowOrderKey = 'order_key'
    ) {
        $this->items = $items;
        $this->colKey = $colKey;
        $this->rowKey = $rowKey;
        $this->colAccessor = $colAccessor;
        $this->rowAccessor = $rowAccessor;
        $this->colOrderKey = $colOrderKey;
        $this->rowOrderKey = $rowOrderKey;
    }

    public function colHeaders(): Collection
    {
        return $this->items->pluck($this->colKey)->unique()->sortBy($this->colOrderKey);
    }

    public function rowHeaders(): Collection
    {
        return $this->items->pluck($this->rowKey)->unique()->sortBy($this->rowOrderKey);
    }

    public function filterItems(object $row, object $col): Collection
    {
        return $this->items->where($this->rowKey, $row)->where($this->colKey, $col);
    }

    public function colName(): string
    {
        return Str::kebab(Arr::last(explode('.', $this->colKey)));
    }

    public function rowName(): string
    {
        return Str::kebab(Arr::last(explode('.', $this->rowKey)));
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
