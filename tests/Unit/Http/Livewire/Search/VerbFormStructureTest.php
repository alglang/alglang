<?php

namespace Tests\Unit\Http\Livewire\Search;

use App\Models\Feature;
use App\Models\VerbClass;
use App\Models\VerbMode;
use App\Models\VerbOrder;
use App\Http\Livewire\Search\VerbFormStructure as Component;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerbFormStructureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_its_classes(): void
    {
        $class = VerbClass::factory()->create(['abv' => 'TC']);
        $view = $this->livewire(Component::class, ['classes' => [$class]]);
        $view->assertSeeHtmlInOrder(['<option', 'TC', '</option>']);
    }

    /** @test */
    public function it_shows_its_orders(): void
    {
        $order = VerbOrder::factory()->create(['name' => 'Test Order']);
        $view = $this->livewire(Component::class, ['orders' => [$order]]);
        $view->assertSeeHtmlInOrder(['<option', 'Test Order', '</option>']);
    }

    /** @test */
    public function it_shows_its_modes(): void
    {
        $mode = VerbMode::factory()->create(['name' => 'Test Mode']);
        $view = $this->livewire(Component::class, ['modes' => [$mode]]);
        $view->assertSeeHtmlInOrder(['<option', 'Test Mode', '</option>']);
    }

    /** @test */
    public function it_shows_its_features(): void
    {
        $feature = Feature::factory()->create(['name' => '3s']);
        $view = $this->livewire(Component::class, ['features' => [$feature]]);
        $view->assertSeeHtmlInOrder(['<option', '3s', '</option>']);
    }
}
