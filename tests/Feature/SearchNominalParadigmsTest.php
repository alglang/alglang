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

    /** @test */
    public function it_orders_by_paradigm_type()
    {
        $this->withoutExceptionHandling();
        $language = factory(Language::class)->create();

        factory(NominalForm::class)->create([
            'shape' => 'N-foo',
            'language_code' => $language,
            'structure_id' => factory(NominalStructure::class)->create([
                'paradigm_id' => factory(NominalParadigm::class)->create([
                    'paradigm_type_name' => factory(NominalParadigmType::class)->create([
                        'has_pronominal_feature' => false,
                        'has_nominal_feature' => true
                    ])
                ])
            ])
        ]);

        factory(NominalForm::class)->create([
            'shape' => 'N-bar',
            'language_code' => $language,
            'structure_id' => factory(NominalStructure::class)->create([
                'paradigm_id' => factory(NominalParadigm::class)->create([
                    'paradigm_type_name' => factory(NominalParadigmType::class)->create([
                        'has_pronominal_feature' => true,
                        'has_nominal_feature' => true
                    ])
                ])
            ])
        ]);

        factory(NominalForm::class)->create([
            'shape' => 'N-baz',
            'language_code' => $language,
            'structure_id' => factory(NominalStructure::class)->create([
                'paradigm_id' => factory(NominalParadigm::class)->create([
                    'paradigm_type_name' => factory(NominalParadigmType::class)->create([
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
        $language = factory(Language::class)->create();

        factory(NominalForm::class)->create([
            'shape' => 'N-foo',
            'language_code' => $language,
            'structure_id' => factory(NominalStructure::class)->create([
                'paradigm_id' => factory(NominalParadigm::class)->create([
                    'name' => 'B'
                ])
            ])
        ]);

        factory(NominalForm::class)->create([
            'shape' => 'N-bar',
            'language_code' => $language,
            'structure_id' => factory(NominalStructure::class)->create([
                'paradigm_id' => factory(NominalParadigm::class)->create([
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
        $language = factory(Language::class)->create();
        $paradigm = factory(NominalParadigm::class)->create();

        factory(NominalForm::class)->create([
            'shape' => 'N-foo',
            'language_code' => $language,
            'structure_id' => factory(NominalStructure::class)->create([
                'paradigm_id' => $paradigm,
                'pronominal_feature_name' => factory(Feature::class)->create([
                    'person' => '3',
                    'number' => 3
                ]),
                'nominal_feature_name' => null
            ])
        ]);

        factory(NominalForm::class)->create([
            'shape' => 'N-bar',
            'language_code' => $language,
            'structure_id' => factory(NominalStructure::class)->create([
                'paradigm_id' => $paradigm,
                'pronominal_feature_name' => factory(Feature::class)->create([
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
