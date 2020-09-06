<?php

namespace App\Http\Livewire\Collections;

use App\Contracts\HasMorphemes;
use App\Models\Language;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class Morphemes extends Component
{
    /** @var mixed */
    public $model;

    /** @var string */
    public $size;

    /** @var int */
    public $page;

    /** @var array */
    protected $listeners = ['resize'];

    public function mount(HasMorphemes $model, string $screenSize = 'xl', int $page = 0): void
    {
        $this->model = $model;
        $this->size = $screenSize;
        $this->page = $page;
    }

    public function getMorphemesProperty(): Collection
    {
        return $this->model->morphemes->slice(
            $this->perPage() * $this->page,
            $this->perPage()
        )->values();
    }

    public function perPage(): int
    {
        switch ($this->size) {
        case 'xs':
        case 'sm':
            return 10;
        case 'md':
            return 14;
        case 'lg':
            return 27;
        default:
            return 56;
        }
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
        
        return $this->model->morphemes()->count() > ($this->page + 1) * $this->perPage();
    }

    public function resize(string $size): void
    {
        $this->size = $size;
    }

    public function render(): View
    {
        return view('livewire.collections.morphemes');
    }
}
