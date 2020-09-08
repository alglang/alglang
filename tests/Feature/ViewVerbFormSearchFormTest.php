<?php

namespace Tests\Feature;

use App\Models\Feature;
use App\Models\Language;
use App\Models\VerbClass;
use App\Models\VerbMode;
use App\Models\VerbOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewVerbFormSearchFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_loads_the_right_view()
    {
        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewIs('search.verbs.forms');
    }

    /** @test */
    public function it_includes_languages()
    {
        $language = Language::factory()->create();

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('languages');
        $this->assertEquals($language->code, $response['languages']->first()->code);
    }

    /** @test */
    public function languages_are_ordered_by_name()
    {
        Language::factory()->create(['name' => 'Foo', 'order_key' => 0]);
        Language::factory()->create(['name' => 'Bar', 'order_key' => 1]);

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('languages');
        $this->assertEquals(['Bar', 'Foo'], $response['languages']->pluck('name')->toArray());
    }

    /** @test */
    public function it_includes_classes()
    {
        $class = VerbClass::factory()->create();

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('classes');
        $this->assertEquals($class->abv, $response['classes']->first()->abv);
    }

    /** @test */
    public function classes_are_ordered_by_abv()
    {
        VerbClass::factory()->create(['abv' => 'Foo']);
        VerbClass::factory()->create(['abv' => 'Bar']);

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('classes');
        $this->assertEquals(['Bar', 'Foo'], $response['classes']->pluck('abv')->toArray());
    }

    /** @test */
    public function it_includes_modes()
    {
        $mode = VerbMode::factory()->create();

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('modes');
        $this->assertEquals($mode->name, $response['modes']->first()->name);
    }

    /** @test */
    public function modes_are_ordered_by_name()
    {
        VerbMode::factory()->create(['name' => 'Foo']);
        VerbMode::factory()->create(['name' => 'Bar']);

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('modes');
        $this->assertEquals(['Bar', 'Foo'], $response['modes']->pluck('name')->toArray());
    }

    /** @test */
    public function it_includes_orders()
    {
        $order = VerbOrder::factory()->create();

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('orders');
        $this->assertEquals($order->name, $response['orders']->first()->name);
    }

    /** @test */
    public function orders_are_ordered_by_name()
    {
        VerbOrder::factory()->create(['name' => 'Foo']);
        VerbOrder::factory()->create(['name' => 'Bar']);

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('orders');
        $this->assertEquals(['Bar', 'Foo'], $response['orders']->pluck('name')->toArray());
    }

    /** @test */
    public function it_includes_features()
    {
        $feature = Feature::factory()->create();

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('features');
        $this->assertEquals($feature->name, $response['features']->first()->name);
    }

    /** @test */
    public function features_are_ordered_by_name()
    {
        Feature::factory()->create(['name' => 'Foo']);
        Feature::factory()->create(['name' => 'Bar']);

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('features');
        $this->assertEquals(['Bar', 'Foo'], $response['features']->pluck('name')->toArray());
    }
}
