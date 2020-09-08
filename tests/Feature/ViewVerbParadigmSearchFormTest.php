<?php

namespace Tests\Feature;

use App\Models\Language;
use App\Models\VerbClass;
use App\Models\VerbOrder;
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
        $languages = Language::factory()->count(2)->create();

        $response = $this->get('/search/verbs/paradigms');
        $response->assertOk();
        $response->assertViewHas('languages');
        $this->assertEquals($languages->pluck('code'), $response['languages']->pluck('code'));
    }

    /** @test */
    public function it_includes_classes()
    {
        $classes = VerbClass::factory()->count(2)->create();

        $response = $this->get('/search/verbs/paradigms');
        $response->assertOk();
        $response->assertViewHas('classes');
        $this->assertEquals($classes->pluck('abv'), $response['classes']->pluck('abv'));
    }

    /** @test */
    public function it_includes_orders()
    {
        $orders = VerbOrder::factory()->count(2)->create();

        $response = $this->get('/search/verbs/paradigms');
        $response->assertOk();
        $response->assertViewHas('orders');
        $this->assertEquals($orders->pluck('name'), $response['orders']->pluck('name'));
    }
}
