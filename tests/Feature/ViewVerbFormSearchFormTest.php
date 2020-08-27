<?php

namespace Tests\Feature;

use App\Feature;
use App\Language;
use App\VerbClass;
use App\VerbMode;
use App\VerbOrder;
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
        $language = factory(Language::class)->create();

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('languages');
        $this->assertEquals($language->code, $response['languages']->first()->code);
    }

    /** @test */
    public function languages_are_ordered_by_name()
    {
        factory(Language::class)->create(['name' => 'Foo', 'order_key' => 0]);
        factory(Language::class)->create(['name' => 'Bar', 'order_key' => 1]);

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('languages');
        $this->assertEquals(['Bar', 'Foo'], $response['languages']->pluck('name')->toArray());
    }

    /** @test */
    public function it_includes_classes()
    {
        $class = factory(VerbClass::class)->create();

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('classes');
        $this->assertEquals($class->abv, $response['classes']->first()->abv);
    }

    /** @test */
    public function classes_are_ordered_by_abv()
    {
        factory(VerbClass::class)->create(['abv' => 'Foo']);
        factory(VerbClass::class)->create(['abv' => 'Bar']);

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('classes');
        $this->assertEquals(['Bar', 'Foo'], $response['classes']->pluck('abv')->toArray());
    }

    /** @test */
    public function it_includes_modes()
    {
        $mode = factory(VerbMode::class)->create();

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('modes');
        $this->assertEquals($mode->name, $response['modes']->first()->name);
    }

    /** @test */
    public function modes_are_ordered_by_name()
    {
        factory(VerbMode::class)->create(['name' => 'Foo']);
        factory(VerbMode::class)->create(['name' => 'Bar']);

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('modes');
        $this->assertEquals(['Bar', 'Foo'], $response['modes']->pluck('name')->toArray());
    }

    /** @test */
    public function it_includes_orders()
    {
        $order = factory(VerbOrder::class)->create();

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('orders');
        $this->assertEquals($order->name, $response['orders']->first()->name);
    }

    /** @test */
    public function orders_are_ordered_by_name()
    {
        factory(VerbOrder::class)->create(['name' => 'Foo']);
        factory(VerbOrder::class)->create(['name' => 'Bar']);

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('orders');
        $this->assertEquals(['Bar', 'Foo'], $response['orders']->pluck('name')->toArray());
    }

    /** @test */
    public function it_includes_features()
    {
        $feature = factory(Feature::class)->create();

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('features');
        $this->assertEquals($feature->name, $response['features']->first()->name);
    }

    /** @test */
    public function features_are_ordered_by_name()
    {
        factory(Feature::class)->create(['name' => 'Foo']);
        factory(Feature::class)->create(['name' => 'Bar']);

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('features');
        $this->assertEquals(['Bar', 'Foo'], $response['features']->pluck('name')->toArray());
    }
}
