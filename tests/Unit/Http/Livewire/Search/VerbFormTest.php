<?php

namespace Tests\Unit\Http\Livewire\Search;

use App\Models\Feature;
use App\Models\Language;
use App\Models\VerbClass;
use App\Models\VerbMode;
use App\Models\VerbOrder;
use App\Models\VerbStructure;
use App\Http\Livewire\Search\VerbForm as Component;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerbFormTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Feature::factory()->create([
            'name' => '1',
            'person' => null,
            'number' => 1
        ]);
    }

    /** @test */
    public function it_has_all_languages_available_as_options(): void
    {
        Language::factory()->create(['name' => 'Test Language', 'code' => 'TL']);

        $view = $this->livewire(Component::class);

        $view->assertSet('languages', Language::all());
        $view->assertSee('Test Language');
    }

    /** @test */
    public function it_has_all_classes_as_options(): void
    {
        VerbClass::factory()->create(['abv' => 'TC']);

        $view = $this->livewire(Component::class);

        $view->assertSet('classes', VerbClass::all());
        $view->assertSeeHtmlInOrder(['<option', 'TC', '</option>']);
    }

    /** @test */
    public function it_has_all_orders_as_options(): void
    {
        VerbOrder::factory()->create(['name' => 'Test Order']);

        $view = $this->livewire(Component::class);

        $view->assertSet('orders', VerbOrder::all());
        $view->assertSeeHtmlInOrder(['<option', 'Test Order', '</option>']);
    }

    /** @test */
    public function it_has_all_modes_as_options(): void
    {
        VerbMode::factory()->create(['name' => 'Test Mode']);

        $view = $this->livewire(Component::class);

        $view->assertSet('modes', VerbMode::all());
        $view->assertSeeHtmlInOrder(['<option', 'Test Mode', '</option>']);
    }

    /** @test */
    public function it_has_all_features_as_options(): void
    {
        Feature::factory()->create(['name' => '3s']);

        $view = $this->livewire(Component::class);

        $view->assertSet('features', Feature::all());
        $view->assertSeeHtmlInOrder(['<option', '3s', '</option>']);
    }

    /** @test */
    public function it_shows_one_structure_query_by_default(): void
    {
        $view = $this->livewire(Component::class);
        $view->assertSee('Structure');
        $this->assertCount(1, $view->structureQueries);
    }

    /** @test */
    public function structure_queries_can_be_added(): void
    {
        $view = $this->livewire(Component::class);

        $view->call('addStructureQuery');
        $this->assertCount(2, $view->structureQueries);
    }

    /** @test */
    public function structure_queries_can_be_removed(): void
    {
        $view = $this->livewire(Component::class);
        $view->call('addStructureQuery');

        $view->call('removeStructureQuery');

        $this->assertCount(1, $view->structureQueries);
    }

    /** @test */
    public function structure_queries_cannot_be_removed_if_there_are_fewer_than_2_queries(): void
    {
        $view = $this->livewire(Component::class);
        $this->assertCount(1, $view->structureQueries);

        $view->call('removeStructureQuery');

        $this->assertCount(1, $view->structureQueries);
    }

    /** @test */
    public function initial_structure_query_has_correct_defaults(): void
    {
        $view = $this->livewire(Component::class);
        $structureQuery = $view->structureQueries[0];

        $this->assertEquals('AI', $structureQuery->class_abv);
        $this->assertEquals('1', $structureQuery->subject_name);
        $this->assertNull($structureQuery->primary_object_name);
        $this->assertNull($structureQuery->secondary_object_name);
        $this->assertEquals('Conjunct', $structureQuery->order_name);
        $this->assertEquals('Indicative', $structureQuery->mode_name);
        $this->assertFalse($structureQuery->is_negative);
        $this->assertFalse($structureQuery->is_diminutive);
    }

    /** @test */
    public function new_queries_have_correct_defaults(): void
    {
        $view = $this->livewire(Component::class);
        $view->call('addStructureQuery');
        $structureQuery = $view->structureQueries[1];

        $this->assertEquals('AI', $structureQuery->class_abv);

        $this->assertEquals('1', $structureQuery->subject_name);
        $this->assertEquals(1, $structureQuery->subject->number);
        $this->assertEquals(null, $structureQuery->subject->person);
        $this->assertNull($structureQuery->primary_object_name);
        $this->assertNull($structureQuery->primaryObject);
        $this->assertNull($structureQuery->secondary_object_name);
        $this->assertNull($structureQuery->secondaryObject);
        $this->assertEquals('Conjunct', $structureQuery->order_name);
        $this->assertEquals('Indicative', $structureQuery->mode_name);
        $this->assertFalse($structureQuery->is_negative);
        $this->assertFalse($structureQuery->is_diminutive);
    }

    /** @test */
    public function subject_data_updates_on_change(): void
    {
        Feature::factory()->create([
            'name' => 'foo',
            'person' => 'p',
            'number' => 2
        ]);

        $view = $this->livewire(Component::class);
        $view->set('structureQueries.0.subject_name', 'foo');

        $this->assertNoQueries(function () use ($view) {
            $this->assertEquals('p', $view->structureQueries[0]->subject->person);
            $this->assertEquals(2, $view->structureQueries[0]->subject->number);
        });
    }

    /** @test */
    public function primary_object_data_updates_on_change(): void
    {
        Feature::factory()->create([
            'name' => 'foo',
            'person' => 's',
            'number' => 1
        ]);

        $view = $this->livewire(Component::class);
        $view->set('structureQueries.0.primary_object_name', 'foo');

        $this->assertNoQueries(function () use ($view) {
            $this->assertNotNull($view->structureQueries[0]->primaryObject);
            $this->assertEquals('s', $view->structureQueries[0]->primaryObject->person);
            $this->assertEquals(1, $view->structureQueries[0]->primaryObject->number);
        });
    }

    /** @test */
    public function secondary_object_data_updates_on_change(): void
    {
        Feature::factory()->create([
            'name' => 'foo',
            'person' => 's',
            'number' => 1
        ]);

        $view = $this->livewire(Component::class);
        $view->set('structureQueries.0.secondary_object_name', 'foo');

        $this->assertNoQueries(function () use ($view) {
            $this->assertNotNull($view->structureQueries[0]->secondaryObject);
            $this->assertEquals('s', $view->structureQueries[0]->secondaryObject->person);
            $this->assertEquals(1, $view->structureQueries[0]->secondaryObject->number);
        });
    }

    /** @test */
    public function subject_data_does_not_change_from_side_effect(): void
    {
        Feature::factory()->create([
            'name' => 'foo',
            'person' => 'p',
            'number' => 2
        ]);

        $view = $this->livewire(Component::class);
        $view->assertSet('structureQueries.0.subject.name', '1');
        $view->set('structureQueries.0.primary_object_name', 'foo');
        $view->assertSet('structureQueries.0.subject.name', '1');
    }
}
