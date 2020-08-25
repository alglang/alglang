<?php

namespace Tests\Feature;

use App\Language;
use App\VerbClass;
use App\VerbOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewVerbParadigmSearchFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_loads_the_right_view()
    {
        $response = $this->get('/search/verbs/paradigms');
        $response->assertOk();
        $response->assertViewIs('search.verbs.paradigms');
    }

    /** @test */
    public function it_includes_languages()
    {
        $languages = factory(Language::class, 2)->create();

        $response = $this->get('/search/verbs/paradigms');
        $response->assertOk();
        $response->assertViewHas('languages');
        $this->assertEquals($languages->pluck('code'), $response['languages']->pluck('code'));
    }

    /** @test */
    public function it_includes_classes()
    {
        $classes = factory(VerbClass::class, 2)->create();

        $response = $this->get('/search/verbs/paradigms');
        $response->assertOk();
        $response->assertViewHas('classes');
        $this->assertEquals($classes->pluck('abv'), $response['classes']->pluck('abv'));
    }

    /** @test */
    public function it_includes_orders()
    {
        $orders = factory(VerbOrder::class, 2)->create();

        $response = $this->get('/search/verbs/paradigms');
        $response->assertOk();
        $response->assertViewHas('orders');
        $this->assertEquals($orders->pluck('name'), $response['orders']->pluck('name'));
    }
}