<?php

namespace Tests\Feature;

use App\Models\Feature;
use App\Models\Language;
use App\Models\NominalForm;
use App\Models\NominalParadigm;
use App\Models\NominalParadigmType;
use App\Models\NominalStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchNominalParadigmsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_the_correct_view()
    {
        $response = $this->get(route('search.nominals.paradigm-results', [
            'languages' => [Language::factory()->create()->name]
		]));

        $response->assertOk();
        $response->assertViewIs('search.nominals.paradigm-results');
    }

    /** @test */
    public function languages_or_paradigm_types_must_be_included_in_requst()
    {
        $response = $this->get(route('search.nominals.paradigm-results'));
        $response->assertStatus(302);
        $response->assertSessionHasErrors('languages', 'paradigms');
    }

    /** @test */
    public function an_error_message_is_shown_if_no_language_or_paradigm_type_is_included()
    {
        $response = $this->followingRedirects()->get(route('search.nominals.paradigm-results'));
        $response->assertStatus(200);
        $response->assertSee('Please select at least one language or paradigm.');
    }

    /** @test */
    public function it_filters_search_results_by_language()
    {
        $language = Language::factory()->create();
        NominalForm::factory()->create([
            'language_code' => $language,
            'shape' => 'N-foo',
        ]);
        NominalForm::factory()->create([
            'language_code' => Language::factory()->create(),
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
        $paradigm = NominalParadigm::factory()->create([
            'paradigm_type_name' => NominalParadigmType::factory()->create()
        ]);

        NominalForm::factory()->create([
            'shape' => 'N-foo',
            'structure_id' => NominalStructure::factory()->create([
                'paradigm_id' => $paradigm
            ])
        ]);
        NominalForm::factory()->create([
            'shape' => 'N-bar',
        ]);

        $response = $this->get(route('search.nominals.paradigm-results', [
            'paradigm_types' => [$paradigm->type->name]
		]));

        $response->assertOk();
        $response->assertSee('N-foo');
        $response->assertDontSee('N-bar');
    }

    /** @test */
    public function it_orders_by_paradigm_type()
    {
        $this->withoutExceptionHandling();
        $language = Language::factory()->create();

        NominalForm::factory()->create([
            'shape' => 'N-foo',
            'language_code' => $language,
            'structure_id' => NominalStructure::factory()->create([
                'paradigm_id' => NominalParadigm::factory()->create([
                    'paradigm_type_name' => NominalParadigmType::factory()->create([
                        'has_pronominal_feature' => false,
                        'has_nominal_feature' => true
                    ])
                ])
            ])
        ]);

        NominalForm::factory()->create([
            'shape' => 'N-bar',
            'language_code' => $language,
            'structure_id' => NominalStructure::factory()->create([
                'paradigm_id' => NominalParadigm::factory()->create([
                    'paradigm_type_name' => NominalParadigmType::factory()->create([
                        'has_pronominal_feature' => true,
                        'has_nominal_feature' => true
                    ])
                ])
            ])
        ]);

        NominalForm::factory()->create([
            'shape' => 'N-baz',
            'language_code' => $language,
            'structure_id' => NominalStructure::factory()->create([
                'paradigm_id' => NominalParadigm::factory()->create([
                    'paradigm_type_name' => NominalParadigmType::factory()->create([
                        'has_pronominal_feature' => true,
                        'has_nominal_feature' => false
                    ])
                ])
            ])
        ]);

        $response = $this->get(route('search.nominals.paradigm-results', [
            'languages' => [$language->code]
        ]));

        $response->assertOk();
        $response->assertSeeInOrder(['N-baz', 'N-foo', 'N-bar']);
    }

    /** @test */
    public function it_orders_by_paradigm_name()
    {
        $language = Language::factory()->create();

        NominalForm::factory()->create([
            'shape' => 'N-foo',
            'language_code' => $language,
            'structure_id' => NominalStructure::factory()->create([
                'paradigm_id' => NominalParadigm::factory()->create([
                    'name' => 'B'
                ])
            ])
        ]);

        NominalForm::factory()->create([
            'shape' => 'N-bar',
            'language_code' => $language,
            'structure_id' => NominalStructure::factory()->create([
                'paradigm_id' => NominalParadigm::factory()->create([
                    'name' => 'A'
                ])
            ])
        ]);

        $response = $this->get(route('search.nominals.paradigm-results', [
            'languages' => [$language->code]
        ]));

        $response->assertOk();
        $response->assertSeeInOrder(['N-bar', 'N-foo']);
    }

    /** @test */
    public function it_orders_by_features()
    {
        $language = Language::factory()->create();
        $paradigm = NominalParadigm::factory()->create();

        NominalForm::factory()->create([
            'shape' => 'N-foo',
            'language_code' => $language,
            'structure_id' => NominalStructure::factory()->create([
                'paradigm_id' => $paradigm,
                'pronominal_feature_name' => Feature::factory()->create([
                    'person' => '3',
                    'number' => 3
                ]),
                'nominal_feature_name' => null
            ])
        ]);

        NominalForm::factory()->create([
            'shape' => 'N-bar',
            'language_code' => $language,
            'structure_id' => NominalStructure::factory()->create([
                'paradigm_id' => $paradigm,
                'pronominal_feature_name' => Feature::factory()->create([
                    'person' => '3',
                    'number' => 1
                ]),
                'nominal_feature_name' => null
            ])
        ]);

        $response = $this->get(route('search.nominals.paradigm-results', [
            'languages' => [$language->code]
        ]));

        $response->assertOk();
        $response->assertSeeInOrder(['N-bar', 'N-foo']);
    }
}
