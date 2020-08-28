<?php

namespace Tests\Feature;

use App\Models\Language;
use App\Models\NominalParadigmType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewNominalParadigmSearchFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_loads_the_right_view()
    {
        $response = $this->get('/search/nominals/paradigms');
        $response->assertOk();
        $response->assertViewIs('search.nominals.paradigms');
    }

    /** @test */
    public function it_includes_languages()
    {
        $language = factory(Language::class)->create();

        $response = $this->get('/search/nominals/paradigms');
        $response->assertOk();
        $response->assertViewHas('languages');
        $this->assertEquals($language->code, $response['languages']->first()->code);
    }

    /** @test */
    public function languages_are_ordered_by_name()
    {
        factory(Language::class)->create(['name' => 'Foo', 'order_key' => 0]);
        factory(Language::class)->create(['name' => 'Bar', 'order_key' => 1]);

        $response = $this->get('/search/nominals/paradigms');
        $response->assertOk();
        $response->assertViewHas('languages');
        $this->assertEquals(['Bar', 'Foo'], $response['languages']->pluck('name')->toArray());
    }

    /** @test */
    public function it_includes_paradigm_types()
    {
        $paradigmType = factory(NominalParadigmType::class)->create();

        $response = $this->get('/search/nominals/paradigms');
        $response->assertOk();
        $response->assertViewHas('paradigmTypes');
        $this->assertEquals($paradigmType->id, $response['paradigmTypes']->first()->id);
    }

    /** @test */
    public function paradigm_types_are_ordered_by_name()
    {
        factory(NominalParadigmType::class)->create(['name' => 'Foo']);
        factory(NominalParadigmType::class)->create(['name' => 'Bar']);

        $response = $this->get('/search/nominals/paradigms');
        $response->assertOk();
        $response->assertViewHas('paradigmTypes');
        $this->assertEquals(['Bar', 'Foo'], $response['paradigmTypes']->pluck('name')->toArray());
    }
}
