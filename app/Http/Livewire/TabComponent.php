<?php

namespace App\Http\Livewire;

use Livewire\Component;

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

    protected function getTabName(): string
    {
        if (!isset($this->tabName)) {
            throw new \Exception("\$tabName must be defined in children of TabComponent.");
        }

        return $this->tabName;
    }

    abstract public function loadData(): void;
}
