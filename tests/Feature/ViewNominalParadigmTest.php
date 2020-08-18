<?php

namespace Tests\Feature;

use App\Feature;
use App\Language;
use App\Morpheme;
use App\NominalForm;
use App\NominalParadigm;
use App\NominalParadigmType;
use App\NominalStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewNominalParadigmTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_a_nominal_paradigm()
    {
        $this->withoutExceptionHandling();
        $paradigm = factory(NominalParadigm::class)->create([
            'name' => 'Test Paradigm Name',
            'language_id' => factory(Language::class)->create([
                'name' => 'Test Language'
            ])->id,
            'paradigm_type_id' => factory(NominalParadigmType::class)->create([
                'name' => 'Test Paradigm Type'
            ])->id
        ]);

        $response = $this->get($paradigm->url);

        $response->assertOk();
        $response->assertViewIs('nominal-paradigms.show');
        $response->assertViewHas('paradigm', $paradigm);
        $response->assertSee('Test Paradigm Name');
        $response->assertSee('Test Language');
        $response->assertSee('Test Paradigm Type');
    }

    /** @test */
    public function it_shows_its_forms()
    {
        $paradigm = factory(NominalParadigm::class)->create();
        $form = factory(NominalForm::class)->create([
            'shape' => 'N-foo',
            'language_id' => $paradigm->language_id,
            'structure_id' => factory(NominalStructure::class)->create([
                'paradigm_id' => $paradigm->id,
                'pronominal_feature_id' => factory(Feature::class)->create(['name' => '2p'])->id,
                'nominal_feature_id' => factory(Feature::class)->create(['name' => '3p'])->id
            ])->id
        ]);

        $response = $this->get($paradigm->url);

        $response->assertOk();
        $response->assertSee('2p→3p');
        $response->assertSee('N-foo');
    }

    /** @test */
    public function it_shows_form_morphemes_and_glosses()
    {
        $paradigm = factory(NominalParadigm::class)->create();
        $form = factory(NominalForm::class)->create([
            'shape' => 'N-foo',
            'language_id' => $paradigm->language_id,
            'structure_id' => factory(NominalStructure::class)->create([
                'paradigm_id' => $paradigm->id
            ])->id
        ]);
        $morpheme = factory(Morpheme::class)->create([
            'language_id' => $paradigm->language_id,
            'shape' => 'testmorph-',
            'gloss' => 'testgloss'
        ]);
        $form->assignMorphemes([$morpheme]);

        $response = $this->get($paradigm->url);

        $response->assertOk();
        $response->assertSee('testmorph');
        $response->assertSee('testgloss');
    }

    /** @test */
    public function it_shows_its_form_ordered_by_features()
    {
        $paradigm = factory(NominalParadigm::class)->create();
        factory(NominalForm::class)->create([
            'shape' => 'N-foo',
            'language_id' => $paradigm->language_id,
            'structure_id' => factory(NominalStructure::class)->create([
                'paradigm_id' => $paradigm->id,
                'pronominal_feature_id' => factory(Feature::class)->create([
                    'name' => '3p',
                    'person' => '3',
                    'number' => 3
                ])->id
            ])->id
        ]);
        factory(NominalForm::class)->create([
            'shape' => 'N-foo',
            'language_id' => $paradigm->language_id,
            'structure_id' => factory(NominalStructure::class)->create([
                'paradigm_id' => $paradigm->id,
                'pronominal_feature_id' => factory(Feature::class)->create([
                    'name' => '0p',
                    'person' => '0',
                    'number' => 3
                ])
            ])->id
        ]);
        factory(NominalForm::class)->create([
            'shape' => 'N-foo',
            'language_id' => $paradigm->language_id,
            'structure_id' => factory(NominalStructure::class)->create([
                'paradigm_id' => $paradigm->id,
                'pronominal_feature_id' => factory(Feature::class)->create([
                    'name' => '1p',
                    'person' => '1',
                    'number' => 3
                ])
            ])->id
        ]);

        $response = $this->get($paradigm->url);

        $response->assertOk();
        $response->assertSeeInOrder(['1p', '3p', '0p']);
    }
}
