<?php

namespace Tests;

use App\Http\Livewire\TabComponent;
use Illuminate\View\View;

class FakeTabComponent extends TabComponent
{
    public string $data = '';

    protected string $tabName = 'foo';

    public function loadData(): void {
        $this->data .= 'Hello, World!';
    }

    public function renderTab(): string
    {
        return <<<'blade'
<div>
    {{ $data }}
</div>
blade;
    }
}
