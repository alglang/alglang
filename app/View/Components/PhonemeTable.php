<?php

namespace App\View\Components;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

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
        return $this->headers($this->colKey, $this->colOrderKey);
    }

    public function rowHeaders(): Collection
    {
        return $this->headers($this->rowKey, $this->rowOrderKey);
    }

    public function filterItems(object $row, object $col): Collection
    {
        return $this->items->where($this->rowKey, $row)->where($this->colKey, $col);
    }

    public function colName(): string
    {
        return $this->headerName($this->colKey);
    }

    protected function headers(string $key, string $orderKey): Collection
    {
        return $this->items->pluck($key)->unique()->sortBy($orderKey);
    }

    public function rowName(): string
    {
        return $this->headerName($this->rowKey);
    }

    protected function headerName(string $key): string
    {
        return Str::kebab(Arr::last(explode('.', $key)));
    }

    public function render(): View
    {
        return view('components.phoneme-table');
    }
}
