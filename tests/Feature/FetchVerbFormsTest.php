<?php

namespace Tests\Feature;

use App\Language;
use App\Morpheme;
use App\Source;
use App\VerbFeature;
use App\Form;
use App\VerbClass;
use App\VerbForm;
use App\VerbMode;
use App\VerbOrder;
use App\VerbStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchVerbFormsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_language_verb_forms()
    {
        $language = factory(Language::class)->create(['algo_code' => 'TL']);

        $verbForm = factory(Form::class)->state('verb')->create([
            'shape' => 'V-a',
            'language_id' => $language->id,
            'structure_id' => factory(VerbStructure::class)->create([
                'subject_id' => factory(VerbFeature::class)->create(['name' => '1s'])->id,
                'primary_object_id' => factory(VerbFeature::class)->create(['name' => '2p'])->id,
                'secondary_object_id' => factory(VerbFeature::class)->create(['name' => '3d'])->id,
                'mode_id' => factory(VerbMode::class)->create(['name' => 'Indicative'])->id,
                'order_id' => factory(VerbOrder::class)->create(['name' => 'Conjunct']),
                'class_id' => factory(VerbClass::class)->create(['abv' => 'TA'])
            ])->id
        ]);

        $response = $this->get("/api/verb-forms?language_id=$language->id");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                [
                    'shape' => 'V-a',
                    'url' => $verbForm->url,
                    'language' => ['algo_code' => 'TL'],
                    'structure' => [
                        'argument_string' => '1s→2p+3d',
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
    public function it_fetches_verb_forms_by_morpheme()
    {
        $morpheme = factory(Morpheme::class)->create();
        $verbForm1 = factory(VerbForm::class)->create([
            'language_id' => $morpheme->language_id,
            'morpheme_structure' => "$morpheme->id"
        ]);
        $verbForm2 = factory(VerbForm::class)->create([
            'language_id' => $morpheme->language_id,
            'morpheme_structure' => "{$morpheme->id}-foo"
        ]);
        $verbForm3 = factory(VerbForm::class)->create([
            'language_id' => $morpheme->language_id,
            'morpheme_structure' => "foo-{$morpheme->id}"
        ]);
        $verbForm4 = factory(VerbForm::class)->create([
            'language_id' => $morpheme->language_id,
            'morpheme_structure' => "foo-{$morpheme->id}-bar"
        ]);

        factory(VerbForm::class)->create([
            'language_id' => $morpheme->language_id,
            'morpheme_structure' => 'foo-bar'
        ]);

        $response = $this->get("/api/verb-forms?with_morphemes[]=$morpheme->id");

        $response->assertOk();
        $response->assertJsonCount(4, 'data');
        $response->assertJson([
            'data' => [
                ['id' => $verbForm1->id],
                ['id' => $verbForm2->id],
                ['id' => $verbForm3->id],
                ['id' => $verbForm4->id],
            ]
        ]);
    }

    /** @test */
    public function it_fetches_source_verb_forms()
    {
        $source = factory(Source::class)->create();
        $verbForm = factory(Form::class)->state('verb')->create([
            'shape' => 'V-a',
            'language_id' => factory(Language::class)->create(['algo_code' => 'TL'])->id,
            'structure_id' => factory(VerbStructure::class)->create([
                'subject_id' => factory(VerbFeature::class)->create(['name' => '1s'])->id,
                'primary_object_id' => factory(VerbFeature::class)->create(['name' => '2p'])->id,
                'secondary_object_id' => factory(VerbFeature::class)->create(['name' => '3d'])->id,
                'mode_id' => factory(VerbMode::class)->create(['name' => 'Indicative'])->id,
                'order_id' => factory(VerbOrder::class)->create(['name' => 'Conjunct']),
                'class_id' => factory(VerbClass::class)->create(['abv' => 'TA'])
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
                    'language' => ['algo_code' => 'TL'],
                    'structure' => [
                        'argument_string' => '1s→2p+3d',
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
        $language = factory(Language::class)->create();
        factory(Form::class, 2)->state('verb')->create(['language_id' => $language->id]);
        factory(Form::class, 2)->state('nominal')->create(['language_id' => $language->id]);

        $response = $this->get("/api/verb-forms?language_id=$language->id");

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
        $language1 = factory(Language::class)->create(['algo_code' => 'TL']);
        $language2 = factory(Language::class)->create();
        $verbForm1 = factory(Form::class)->state('verb')->create([
            'shape' => 'V-a',
            'language_id' => $language1->id
        ]);
        $verbForm2 = factory(Form::class)->state('verb')->create([
            'shape' => 'V-b',
            'language_id' => $language2->id
        ]);

        $response = $this->get("/api/verb-forms?language_id=$language1->id");

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
        $source1 = factory(Source::class)->create();
        $source2 = factory(Source::class)->create();
        $verbForm1 = factory(Form::class)->state('verb')->create(['shape' => 'V-a']);
        $verbForm2 = factory(Form::class)->state('verb')->create(['shape' => 'V-b',]);
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
        $language1 = factory(Language::class)->create();
        $language2 = factory(Language::class)->create();
        $source1 = factory(Source::class)->create();
        $source2 = factory(Source::class)->create();
        $verbForm1 = factory(Form::class)->state('verb')->create([
            'language_id' => $language1->id,
            'shape' => 'V-a'
        ]);
        $verbForm2 = factory(Form::class)->state('verb')->create([
            'language_id' => $language1->id,
            'shape' => 'V-b'
        ]);
        $verbForm3 = factory(Form::class)->state('verb')->create([
            'language_id' => $language2->id,
            'shape' => 'V-c'
        ]);
        $verbForm1->addSource($source1);
        $verbForm2->addSource($source2);
        $verbForm3->addSource($source1);

        $response = $this->get("/api/verb-forms?source_id=$source1->id&language_id=$language1->id");

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
        $language = factory(Language::class)->create();
        factory(Form::class, 3)->state('verb')->create(['language_id' => $language->id]);

        $response = $this->get("/api/verb-forms?language_id=$language->id&per_page=2");

        $response->assertOk();
        $response->assertJsonCount(2, 'data');

        $nextResponse = $this->get($response->decodeResponseJson()['links']['next']);
        $nextResponse->assertOk();
        $nextResponse->assertJsonCount(1, 'data');
    }
}
