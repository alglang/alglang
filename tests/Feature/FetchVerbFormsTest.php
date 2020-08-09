<?php

namespace Tests\Feature;

use App\Language;
use App\Source;
use App\VerbFeature;
use App\VerbForm;
use App\VerbClass;
use App\VerbMode;
use App\VerbOrder;
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

        $verbForm = factory(VerbForm::class)->create([
            'shape' => 'V-a',
            'language_id' => $language->id,
            'subject_id' => factory(VerbFeature::class)->create(['name' => '1s'])->id,
            'primary_object_id' => factory(VerbFeature::class)->create(['name' => '2p'])->id,
            'secondary_object_id' => factory(VerbFeature::class)->create(['name' => '3d'])->id,
            'mode_id' => factory(VerbMode::class)->create(['name' => 'Indicative'])->id,
            'order_id' => factory(VerbOrder::class)->create(['name' => 'Conjunct']),
            'class_id' => factory(VerbClass::class)->create(['abv' => 'TA'])
        ]);

        $response = $this->get("/api/verb-forms?language_id=$language->id");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                [
                    'shape' => 'V-a',
                    'url' => $verbForm->url,
                    'argument_string' => '1s→2p+3d',
                    'language' => ['algo_code' => 'TL'],
                    'subject' => ['name' => '1s'],
                    'primary_object' => ['name' => '2p'],
                    'secondary_object' => ['name' => '3d'],
                    'mode' => ['name' => 'Indicative'],
                    'order' =>['name' => 'Conjunct'],
                    'class' => ['abv' => 'TA']
                ]
            ]
        ]);
    }

    /** @test */
    public function it_fetches_source_verb_forms()
    {
        $source = factory(Source::class)->create();
        $verbForm = factory(VerbForm::class)->create([
            'shape' => 'V-a',
            'language_id' => factory(Language::class)->create(['algo_code' => 'TL'])->id,
            'subject_id' => factory(VerbFeature::class)->create(['name' => '1s'])->id,
            'primary_object_id' => factory(VerbFeature::class)->create(['name' => '2p'])->id,
            'secondary_object_id' => factory(VerbFeature::class)->create(['name' => '3d'])->id,
            'mode_id' => factory(VerbMode::class)->create(['name' => 'Indicative'])->id,
            'order_id' => factory(VerbOrder::class)->create(['name' => 'Conjunct']),
            'class_id' => factory(VerbClass::class)->create(['abv' => 'TA'])
        ]);
        $verbForm->addSource($source);

        $response = $this->get("/api/verb-forms?source_id=$source->id");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                [
                    'shape' => 'V-a',
                    'url' => $verbForm->url,
                    'argument_string' => '1s→2p+3d',
                    'language' => ['algo_code' => 'TL'],
                    'subject' => ['name' => '1s'],
                    'primary_object' => ['name' => '2p'],
                    'secondary_object' => ['name' => '3d'],
                    'mode' => ['name' => 'Indicative'],
                    'order' =>['name' => 'Conjunct'],
                    'class' => ['abv' => 'TA']
                ]
            ]
        ]);
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
        $verbForm1 = factory(VerbForm::class)->create([
            'shape' => 'V-a',
            'language_id' => $language1->id
        ]);
        $verbForm2 = factory(VerbForm::class)->create([
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
        $verbForm1 = factory(VerbForm::class)->create(['shape' => 'V-a']);
        $verbForm2 = factory(VerbForm::class)->create(['shape' => 'V-b',]);
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
        $verbForm1 = factory(VerbForm::class)->create([
            'language_id' => $language1->id,
            'shape' => 'V-a'
        ]);
        $verbForm2 = factory(VerbForm::class)->create([
            'language_id' => $language1->id,
            'shape' => 'V-b'
        ]);
        $verbForm3 = factory(VerbForm::class)->create([
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
    public function it_paginates_language_verb_forms()
    {
        $language = factory(Language::class)->create();
        factory(VerbForm::class, 11)->create(['language_id' => $language->id]);

        $response = $this->get("/api/verb-forms?language_id=$language->id");

        $response->assertOk();
        $response->assertJsonCount(10, 'data');

        $nextResponse = $this->get($response->decodeResponseJson()['links']['next']);
        $nextResponse->assertOk();
        $nextResponse->assertJsonCount(1, 'data');
    }

    /** @test */
    public function it_paginates_source_verb_forms()
    {
        $source = factory(Source::class)->create();
        $verbForms = factory(VerbForm::class, 11)->create();

        foreach ($verbForms as $verbForm) {
            $verbForm->addSource($source);
        }

        $response = $this->get("/api/verb-forms?source_id=$source->id");

        $response->assertOk();
        $response->assertJsonCount(10, 'data');

        $nextResponse = $this->get($response->decodeResponseJson()['links']['next']);
        $nextResponse->assertOk();
        $nextResponse->assertJsonCount(1, 'data');
    }
}
