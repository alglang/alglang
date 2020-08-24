<?php

namespace Tests\Feature;

use App\Language;
use App\Morpheme;
use App\Feature;
use App\NominalForm;
use App\NominalParadigm;
use App\NominalStructure;
use App\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchNominalFormsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_language_nominal_forms()
    {
        $language = factory(Language::class)->create(['code' => 'TL']);

        $nominalForm = factory(NominalForm::class)->create([
            'shape' => 'N-a',
            'language_code' => $language->code,
            'structure_id' => factory(NominalStructure::class)->create([
                'pronominal_feature_name' => factory(Feature::class)->create(['name' => 'Pronom Feat']),
                'nominal_feature_name' => factory(Feature::class)->create(['name' => 'Nom Feat']),
                'paradigm_id' => factory(NominalParadigm::class)->create([
                    'name' => 'Test Paradigm',
                    'language_code' => $language->code
                ])
            ])
        ]);

        $response = $this->get("/api/nominal-forms?language=$language->code");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                [
                    'shape' => 'N-a',
                    'url' => $nominalForm->url,
                    'language' => ['code' => 'TL'],
                    'structure' => [
                        'pronominal_feature' => ['name' => 'Pronom Feat'],
                        'nominal_feature' => ['name' => 'Nom Feat'],
                        'paradigm' => ['name' => 'Test Paradigm']
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function it_fetches_nominal_forms_by_morpheme()
    {
        $morpheme = factory(Morpheme::class)->create();
        $nominalForm = factory(NominalForm::class)->create(['language_code' => $morpheme->language_code]);
        $nominalForm->assignMorphemes([$morpheme]);
        factory(NominalForm::class)->create(['language_code' => $morpheme->language_code])->assignMorphemes(['foo', 'bar']);

        $response = $this->get("/api/nominal-forms?with_morphemes[]=$morpheme->id");

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJson([
            'data' => [
                ['id' => $nominalForm->id],
            ]
        ]);
    }

    /** @test */
    public function it_fetches_source_verb_forms()
    {
        $language = factory(Language::class)->create(['code' => 'TL']);
        $source = factory(Source::class)->create();
        $nominalForm = factory(NominalForm::class)->create([
            'shape' => 'N-a',
            'language_code' => $language->code,
            'structure_id' => factory(NominalStructure::class)->create([
                'pronominal_feature_name' => factory(Feature::class)->create(['name' => 'Pronom Feat']),
                'nominal_feature_name' => factory(Feature::class)->create(['name' => 'Nom Feat']),
                'paradigm_id' => factory(NominalParadigm::class)->create([
                    'name' => 'Test Paradigm',
                    'language_code' => $language->code
                ])
            ])
        ]);
        $nominalForm->addSource($source);

        $response = $this->get("/api/nominal-forms?source_id=$source->id");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                [
                    'shape' => 'N-a',
                    'url' => $nominalForm->url,
                    'language' => ['code' => 'TL'],
                    'structure' => [
                        'pronominal_feature' => ['name' => 'Pronom Feat'],
                        'nominal_feature' => ['name' => 'Nom Feat'],
                        'paradigm' => ['name' => 'Test Paradigm']
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function it_responds_with_a_400_if_a_suitable_key_is_not_provided()
    {
        $response = $this->get('/api/nominal-forms');
        $response->assertStatus(400);
    }

    /** @test */
    public function nominal_forms_are_filtered_by_language()
    {
        $language1 = factory(Language::class)->create(['code' => 'TL']);
        $language2 = factory(Language::class)->create();
        $nominalForm1 = factory(NominalForm::class)->create([
            'shape' => 'N-a',
            'language_code' => $language1->code
        ]);
        $nominalForm2 = factory(NominalForm::class)->create([
            'shape' => 'N-b',
            'language_code' => $language2->code
        ]);

        $response = $this->get("/api/nominal-forms?language=$language1->code");

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJson([
            'data' => [
                ['shape' => 'N-a']
            ]
        ]);
    }

    /** @test */
    public function nominal_forms_are_filtered_by_source()
    {
        $source1 = factory(Source::class)->create();
        $source2 = factory(Source::class)->create();
        $nominalForm1 = factory(NominalForm::class)->create(['shape' => 'N-a']);
        $nominalForm2 = factory(NominalForm::class)->create(['shape' => 'N-b',]);
        $nominalForm1->addSource($source1);
        $nominalForm2->addSource($source2);

        $response = $this->get("/api/nominal-forms?source_id=$source1->id");

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJson([
            'data' => [
                ['shape' => 'N-a']
            ]
        ]);
    }

    /** @test */
    public function it_paginates_nominal_forms()
    {
        $language = factory(Language::class)->create();
        factory(NominalForm::class, 3)->create(['language_code' => $language->code]);

        $response = $this->get("/api/nominal-forms?language=$language->code&per_page=2");

        $response->assertOk();
        $response->assertJsonCount(2, 'data');

        $nextResponse = $this->get($response->decodeResponseJson()['links']['next']);
        $nextResponse->assertOk();
        $nextResponse->assertJsonCount(1, 'data');
    }
}
