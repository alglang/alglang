<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\View\View;

abstract class TabComponent extends Component
{
    public bool $tabLoaded = false;

    protected string $tabName;

    /** @var array */
    protected $listeners = [
        'tabChanged'
    ];

    public function tabChanged(string $tab): void
    {
        if (!$this->tabLoaded && $this->getTabName() === $tab) {
            $this->loadData();
            $this->tabLoaded = true;
        }
    }

    /**
     * @return string|View
     */
    public function render()
    {
        if (!$this->tabLoaded) {
            return <<<'blade'
<div>
    @include('components.loading-spinner')
</div>
blade;
        }

        return $this->renderTab();
    }

    public function getTabName(): string
    {
        if (!isset($this->tabName)) {
            throw new \Exception('$tabName must be defined in children of TabComponent.');
        }

        return $this->tabName;
    }

    /**
     * @return string|View
     */
    abstract public function renderTab();
    abstract public function loadData(): void;
}
