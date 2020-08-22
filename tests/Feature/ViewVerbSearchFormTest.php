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

class ViewVerbSearchFormTest extends TestCase
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
        $languages = factory(Language::class, 2)->create();

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('languages');
        $this->assertEquals($languages->pluck('id'), $response['languages']->pluck('id'));
    }

    /** @test */
    public function it_includes_classes()
    {
        $classes = factory(VerbClass::class, 2)->create();

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('classes');
        $this->assertEquals($classes->pluck('abv'), $response['classes']->pluck('abv'));
    }

    /** @test */
    public function it_includes_modes()
    {
        $modes = factory(VerbMode::class, 2)->create();

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('modes');
        $this->assertEquals($modes->pluck('name'), $response['modes']->pluck('name'));
    }

    /** @test */
    public function it_includes_orders()
    {
        $orders = factory(VerbOrder::class, 2)->create();

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('orders');
        $this->assertEquals($orders->pluck('name'), $response['orders']->pluck('name'));
    }

    /** @test */
    public function it_includes_features()
    {
        $features = factory(Feature::class, 2)->create();

        $response = $this->get('/search/verbs/forms');
        $response->assertOk();
        $response->assertViewHas('features');
        $this->assertEquals($features->pluck('name'), $response['features']->pluck('name'));
    }
}
