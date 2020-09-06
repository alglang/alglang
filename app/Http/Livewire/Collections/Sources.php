<?php

namespace App\Http\Livewire\Collections;

use App\Models\Source;
use App\Contracts\HasSources;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Component;

class Sources extends Component
{
    /** @var string */
    public $screenSize;

    /** @var int */
    public $page;

    /** @var string */
    public $filter = '';

    /** @var mixed */
    public $model;

    /** @var array */
    protected $listeners = ['resize'];

    public function mount(string $screenSize = 'xl', int $page = 0, HasSources $model = null): void
    {
        $this->screenSize = $screenSize;
        $this->page = $page;
        $this->model = $model;
    }

    /**
     * @return Builder|Relation
     */
    protected function query()
    {
        if ($this->model) {
            return $this->model->sources();
        }

        return Source::query();
    }

    public function getSourcesProperty(): Collection
    {
        return $this->query()
            ->where(DB::raw('CONCAT(author, " ", year)'), 'LIKE', "%{$this->filter}%")
            ->skip($this->page * $this->sourcesPerPage())
            ->take($this->sourcesPerPage())
            ->get();
    }

    public function sourcesPerPage(): int
    {
        switch ($this->screenSize) {
        case 'xs':
        case 'sm':
            return 20;
        case 'md':
            return 100;
        case 'lg':
            return 180;
        default:
            return 224;
        }
    }

    public function hasMoreItems(): bool
    {
        return $this->query()->count() > ($this->page + 1) * $this->sourcesPerPage();
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

    public function resize(string $size): void
    {
        $this->screenSize = $size;
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.collections.sources');
    }
}
