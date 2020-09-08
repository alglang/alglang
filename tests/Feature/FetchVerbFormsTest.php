<?php

namespace Tests\Feature;

use App\Models\Feature;
use App\Models\Language;
use App\Models\NominalForm;
use App\Models\Morpheme;
use App\Models\Source;
use App\Models\VerbClass;
use App\Models\VerbForm;
use App\Models\VerbMode;
use App\Models\VerbOrder;
use App\Models\VerbStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchVerbFormsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_language_verb_forms()
    {
        $language = Language::factory()->create(['code' => 'TL']);

        $verbForm = VerbForm::factory()->create([
            'shape' => 'V-a',
            'language_code' => $language->code,
            'structure_id' => VerbStructure::factory()->create([
                'subject_name' => Feature::factory()->create(['name' => '1s']),
                'primary_object_name' => Feature::factory()->create(['name' => '2p']),
                'secondary_object_name' => Feature::factory()->create(['name' => '3d']),
                'mode_name' => VerbMode::factory()->create(['name' => 'Indicative']),
                'order_name' => VerbOrder::factory()->create(['name' => 'Conjunct']),
                'class_abv' => VerbClass::factory()->create(['abv' => 'TA'])
            ])
        ]);

        $response = $this->get("/api/verb-forms?language=$language->code");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                [
                    'shape' => 'V-a',
                    'url' => $verbForm->url,
                    'language' => ['code' => 'TL'],
                    'structure' => [
                        'feature_string' => '1s→2p+3d',
                        'subject' => ['name' => '1s'],
                        'primary_object' => ['name' => '2p'],
                        'secondary_object' => ['name' => '3d'],
                        'mode' => ['name' => 'Indicative'],
                        'order' => ['name' => 'Conjunct'],
                        'class' => ['abv' => 'TA']
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function it_fetches_verb_forms_by_morpheme()
    {
        $this->withoutExceptionHandling();
        $morpheme = Morpheme::factory()->create();
        $verbForm = VerbForm::factory()->create(['language_code' => $morpheme->language_code]);
        $verbForm->assignMorphemes([$morpheme]);

        VerbForm::factory()->create(['language_code' => $morpheme->language_code])->assignMorphemes(['foo', 'bar']);

        $response = $this->get("/api/verb-forms?with_morphemes[]=$morpheme->id");

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJson([
            'data' => [
                ['id' => $verbForm->id]
            ]
        ]);
    }

    /** @test */
    public function it_fetches_source_verb_forms()
    {
        $source = Source::factory()->create();
        $verbForm = VerbForm::factory()->create([
            'shape' => 'V-a',
            'language_code' => Language::factory()->create(['code' => 'TL']),
            'structure_id' => VerbStructure::factory()->create([
                'subject_name' => Feature::factory()->create(['name' => '1s']),
                'primary_object_name' => Feature::factory()->create(['name' => '2p']),
                'secondary_object_name' => Feature::factory()->create(['name' => '3d']),
                'mode_name' => VerbMode::factory()->create(['name' => 'Indicative']),
                'order_name' => VerbOrder::factory()->create(['name' => 'Conjunct']),
                'class_abv' => VerbClass::factory()->create(['abv' => 'TA'])
            ])
        ]);
        $verbForm->addSource($source);

        $response = $this->get("/api/verb-forms?source_id=$source->id");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                [
                    'shape' => 'V-a',
                    'url' => $verbForm->url,
                    'language' => ['code' => 'TL'],
                    'structure' => [
                        'feature_string' => '1s→2p+3d',
                        'subject' => ['name' => '1s'],
                        'primary_object' => ['name' => '2p'],
                        'secondary_object' => ['name' => '3d'],
                        'mode' => ['name' => 'Indicative'],
                        'order' =>['name' => 'Conjunct'],
                        'class' => ['abv' => 'TA']
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function it_does_not_include_other_kinds_of_verb_forms()
    {
        $language = Language::factory()->create();
        VerbForm::factory()->count(2)->create(['language_code' => $language->code]);
        NominalForm::factory()->count(2)->create(['language_code' => $language->code]);

        $response = $this->get("/api/verb-forms?language=$language->code");

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
    }

    /** @test */
    public function it_responds_with_a_400_if_a_suitable_key_is_not_provided()
    {
        $response = $this->get('/api/verb-forms');
        $response->assertStatus(400);
    }

    /** @test */
    public function verb_forms_are_filtered_by_language()
    {
        $language1 = Language::factory()->create(['code' => 'TL']);
        $language2 = Language::factory()->create();
        $verbForm1 = VerbForm::factory()->create([
            'shape' => 'V-a',
            'language_code' => $language1->code
        ]);
        $verbForm2 = VerbForm::factory()->create([
            'shape' => 'V-b',
            'language_code' => $language2->code
        ]);

        $response = $this->get("/api/verb-forms?language=$language1->code");

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJson([
            'data' => [
                ['shape' => 'V-a']
            ]
        ]);
    }

    /** @test */
    public function verb_forms_are_filtered_by_source()
    {
        $source1 = Source::factory()->create();
        $source2 = Source::factory()->create();
        $verbForm1 = VerbForm::factory()->create(['shape' => 'V-a']);
        $verbForm2 = VerbForm::factory()->create(['shape' => 'V-b',]);
        $verbForm1->addSource($source1);
        $verbForm2->addSource($source2);

        $response = $this->get("/api/verb-forms?source_id=$source1->id");

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJson([
            'data' => [
                ['shape' => 'V-a']
            ]
        ]);
    }

    /** @test */
    public function verb_forms_are_filtered_by_source_and_language()
    {
        $language1 = Language::factory()->create();
        $language2 = Language::factory()->create();
        $source1 = Source::factory()->create();
        $source2 = Source::factory()->create();
        $verbForm1 = VerbForm::factory()->create([
            'language_code' => $language1->code,
            'shape' => 'V-a'
        ]);
        $verbForm2 = VerbForm::factory()->create([
            'language_code' => $language1->code,
            'shape' => 'V-b'
        ]);
        $verbForm3 = VerbForm::factory()->create([
            'language_code' => $language2->code,
            'shape' => 'V-c'
        ]);
        $verbForm1->addSource($source1);
        $verbForm2->addSource($source2);
        $verbForm3->addSource($source1);

        $response = $this->get("/api/verb-forms?source_id=$source1->id&language=$language1->code");

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJson([
            'data' => [
                ['shape' => 'V-a']
            ]
        ]);
    }

    /** @test */
    public function it_paginates_verb_forms()
    {
        $language = Language::factory()->create();
        VerbForm::factory()->count(3)->create(['language_code' => $language->code]);

        $response = $this->get("/api/verb-forms?language=$language->code&per_page=2");

        $response->assertOk();
        $response->assertJsonCount(2, 'data');

        $nextResponse = $this->get($response->decodeResponseJson()['links']['next']);
        $nextResponse->assertOk();
        $nextResponse->assertJsonCount(1, 'data');
    }

    /** @test */
    public function it_includes_formatted_shapes()
    {
        $language = Language::factory()->create();
        $verbForm = VerbForm::factory()->create(['language_code' => $language]);

        $response = $this->get("/api/verb-forms?language=$language->code");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                ['formatted_shape' => $verbForm->formatted_shape]
            ]
        ]);
    }
}
