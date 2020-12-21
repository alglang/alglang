<?php

namespace Tests\Unit\Http\Livewire;

use App\Http\Livewire\TabComponent;
use Tests\FakeTabComponent;
use Tests\TestCase;

class TabComponentTest extends TestCase
{
    /** @test */
    public function it_shows_loading_info_before_it_has_loaded(): void
    {
        $view = $this->livewire(FakeTabComponent::class);

        $view->assertSee('loading');
    }

    /** @test */
    public function it_loads_when_it_receives_a_tab_changed_event(): void
    {
        $view = $this->livewire(FakeTabComponent::class);

        $view->emit('tabChanged', 'foo');

        $view->assertDontSee('loading');
        $view->assertSee('Hello, World!');
    }

    /** @test */
    public function it_doesnt_load_when_it_receives_the_wrong_tab_name(): void
    {
        $view = $this->livewire(FakeTabComponent::class);

        $view->emit('tabChanged', 'bar');

        $view->assertSee('loading');
        $view->assertDontSee('Hello, World!');
    }

    /** @test */
    public function it_only_loads_once(): void
    {
        $view = $this->livewire(FakeTabComponent::class);

        $view->emit('tabChanged', 'foo');
        $view->emit('tabChanged', 'foo');

        $view->assertSee('Hello, World!');
        $view->assertDontSee('Hello, World!Hello, World!');
    }

    /** @test */
    public function it_raises_an_error_when_the_tab_name_has_not_been_defined_in_the_child(): void
    {
        $component = new class extends TabComponent {
            public function renderTab() { return ''; }
            public function loadData(): void {}
        };

        $this->expectExceptionMessage('$tabName must be defined in children of TabComponent');

        $component->getTabName();
    }
}
