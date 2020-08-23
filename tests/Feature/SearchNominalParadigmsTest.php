<?php

namespace Tests\Feature;

use App\Language;
use App\NominalForm;
use App\NominalParadigm;
use App\NominalParadigmType;
use App\NominalStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchNominalParadigmsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_the_correct_view()
    {
        $this->withoutExceptionHandling();
        $response = $this->get(route('search.nominals.paradigm-results', [
            'languages' => [factory(Language::class)->create()->name]
		]));

        $response->assertOk();
        $response->assertViewIs('search.nominals.paradigm-results');
    }

    /** @test */
    public function languages_or_paradigm_types_must_be_included_in_requst()
    {
        $response = $this->get(route('search.nominals.paradigm-results'));
        $response->assertStatus(302);
    }

    /** @test */
    public function it_filters_search_results_by_language()
    {
        $language = factory(Language::class)->create();
        factory(NominalForm::class)->create([
            'language_code' => $language,
            'shape' => 'N-foo',
        ]);
        factory(NominalForm::class)->create([
            'language_code' => factory(Language::class)->create(),
            'shape' => 'N-bar',
        ]);

        $response = $this->get(route('search.nominals.paradigm-results', [
            'languages' => [$language->code]
		]));

        $response->assertOk();
        $response->assertSee('N-foo');
        $response->assertDontSee('N-bar');
    }

    /** @test */
    public function it_filters_search_results_by_paradigm_type()
    {
        $paradigm = factory(NominalParadigm::class)->create([
            'paradigm_type_name' => factory(NominalParadigmType::class)->create()
        ]);

        factory(NominalForm::class)->create([
            'shape' => 'N-foo',
            'structure_id' => factory(NominalStructure::class)->create([
                'paradigm_id' => $paradigm
            ])
        ]);
        factory(NominalForm::class)->create([
            'shape' => 'N-bar',
        ]);

        $response = $this->get(route('search.nominals.paradigm-results', [
            'paradigm_types' => [$paradigm->type->name]
		]));

        $response->assertOk();
        $response->assertSee('N-foo');
        $response->assertDontSee('N-bar');
    }
}
