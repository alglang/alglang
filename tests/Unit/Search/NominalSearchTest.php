<?php

namespace Tests\Unit\Search;

use App\Models\Language;
use App\Models\NominalForm;
use App\Models\NominalParadigm;
use App\Models\NominalParadigmType;
use App\Models\NominalStructure;
use App\Search\NominalSearch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NominalSearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_filters_search_results_by_language()
    {
        $language = factory(Language::class)->create();
        factory(NominalForm::class)->create([
            'language_code' => $language,
            'shape' => 'N-foo'
        ]);
        factory(NominalForm::class)->create([
            'language_code' => factory(Language::class)->create(),
            'shape' => 'N-bar'
        ]);

        $forms = NominalSearch::search(['languages' => [$language->code]]);

        $this->assertCount(1, $forms);
        $this->assertEquals('N-foo', $forms[0]->shape);
    }

    /** @test */
    public function it_filters_search_results_by_multiple_languages()
    {
        $language1 = factory(Language::class)->create();
        $language2 = factory(Language::class)->create();
        factory(NominalForm::class)->create([
            'language_code' => $language1,
            'shape' => 'N-foo'
        ]);
        factory(NominalForm::class)->create([
            'language_code' => $language2,
            'shape' => 'N-baz'
        ]);
        factory(NominalForm::class)->create([
            'language_code' => factory(Language::class)->create(),
            'shape' => 'N-bar'
        ]);

        $forms = NominalSearch::search([
            'languages' => [$language1->code, $language2->code]
        ]);

        $this->assertCount(2, $forms);
        $this->assertEquals(['N-foo', 'N-baz'], $forms->pluck('shape')->toArray());
    }

    /** @test */
    public function it_filters_search_results_by_paradigm_type()
    {
        $paradigmType1 = factory(NominalParadigmType::class)->create();
        $paradigmType2 = factory(NominalParadigmType::class)->create();

        factory(NominalForm::class)->create([
            'shape' => 'N-foo',
            'structure_id' => factory(NominalStructure::class)->create([
                'paradigm_id' => factory(NominalParadigm::class)->create([
                    'paradigm_type_name' => $paradigmType1
                ])
            ])
        ]);
        factory(NominalForm::class)->create([
            'shape' => 'N-baz',
            'structure_id' => factory(NominalStructure::class)->create([
                'paradigm_id' => factory(NominalParadigm::class)->create([
                    'paradigm_type_name' => $paradigmType2
                ])
            ])
        ]);
        factory(NominalForm::class)->create([
            'shape' => 'N-bar',
            'structure_id' => factory(NominalStructure::class)->create([
                'paradigm_id' => factory(NominalParadigm::class)->create([
                    'paradigm_type_name' => factory(NominalParadigmType::class)->create()
                ])
            ])
        ]);

        $forms = NominalSearch::search([
            'paradigm_types' => [$paradigmType1->name, $paradigmType2->name]
        ]);

        $this->assertCount(2, $forms);
        $this->assertEquals(['N-foo', 'N-baz'], $forms->pluck('shape')->toArray());
    }
}
