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
        $language = Language::factory()->create();
        NominalForm::factory()->create([
            'language_code' => $language,
            'shape' => 'N-foo'
        ]);
        NominalForm::factory()->create([
            'language_code' => Language::factory()->create(),
            'shape' => 'N-bar'
        ]);

        $forms = NominalSearch::search(['languages' => [$language->code]]);

        $this->assertCount(1, $forms);
        $this->assertEquals('N-foo', $forms[0]->shape);
    }

    /** @test */
    public function it_filters_search_results_by_multiple_languages()
    {
        $language1 = Language::factory()->create();
        $language2 = Language::factory()->create();
        NominalForm::factory()->create([
            'language_code' => $language1,
            'shape' => 'N-foo'
        ]);
        NominalForm::factory()->create([
            'language_code' => $language2,
            'shape' => 'N-baz'
        ]);
        NominalForm::factory()->create([
            'language_code' => Language::factory()->create(),
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
        $paradigmType1 = NominalParadigmType::factory()->create();
        $paradigmType2 = NominalParadigmType::factory()->create();

        NominalForm::factory()->create([
            'shape' => 'N-foo',
            'structure_id' => NominalStructure::factory()->create([
                'paradigm_id' => NominalParadigm::factory()->create([
                    'paradigm_type_name' => $paradigmType1
                ])
            ])
        ]);
        NominalForm::factory()->create([
            'shape' => 'N-baz',
            'structure_id' => NominalStructure::factory()->create([
                'paradigm_id' => NominalParadigm::factory()->create([
                    'paradigm_type_name' => $paradigmType2
                ])
            ])
        ]);
        NominalForm::factory()->create([
            'shape' => 'N-bar',
            'structure_id' => NominalStructure::factory()->create([
                'paradigm_id' => NominalParadigm::factory()->create([
                    'paradigm_type_name' => NominalParadigmType::factory()->create()
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
