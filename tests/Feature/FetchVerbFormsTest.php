<?php

namespace Tests\Feature;

use App\Language;
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
    public function verb_forms_can_be_fetched()
    {
        $this->withoutExceptionHandling();

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

        $response = $this->get("/api/languages/{$language->slug}/verb-forms");

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

        $response = $this->get("/api/languages/{$language1->slug}/verb-forms");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                ['shape' => 'V-a']
            ]
        ]);
        $response->assertJsonMissing(['shape' => 'V-b']);
    }
}
