<?php

namespace Tests\Feature;

use App\Models\Feature;
use App\Models\Language;
use App\Models\Morpheme;
use App\Models\NominalForm;
use App\Models\NominalParadigm;
use App\Models\NominalParadigmType;
use App\Models\NominalStructure;
use App\Models\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewNominalParadigmTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_a_nominal_paradigm()
    {
        $paradigm = NominalParadigm::factory()->create([
            'name' => 'Test Paradigm Name',
            'language_code' => Language::factory()->create([
                'name' => 'Test Language'
            ]),
            'paradigm_type_name' => NominalParadigmType::factory()->create([
                'name' => 'Test Paradigm Type'
            ])
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
        $paradigm = NominalParadigm::factory()->create();
        $form = NominalForm::factory()->create([
            'shape' => 'N-foo',
            'language_code' => $paradigm->language_code,
            'structure_id' => NominalStructure::factory()->create([
                'paradigm_id' => $paradigm->id,
                'pronominal_feature_name' => Feature::factory()->create(['name' => '2p']),
                'nominal_feature_name' => Feature::factory()->create(['name' => '3p'])
            ])
        ]);

        $response = $this->get($paradigm->url);

        $response->assertOk();
        $response->assertSee('2p→3p');
        $response->assertSee('N-foo');
    }

    /** @test */
    public function it_shows_form_morphemes_and_glosses()
    {
        $paradigm = NominalParadigm::factory()->create();
        $form = NominalForm::factory()->create([
            'shape' => 'N-foo',
            'language_code' => $paradigm->language_code,
            'structure_id' => NominalStructure::factory()->create([
                'paradigm_id' => $paradigm->id
            ])
        ]);
        $morpheme = Morpheme::factory()->create([
            'language_code' => $paradigm->language_code,
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
        $paradigm = NominalParadigm::factory()->create();
        NominalForm::factory()->create([
            'shape' => 'N-foo',
            'language_code' => $paradigm->language_code,
            'structure_id' => NominalStructure::factory()->create([
                'paradigm_id' => $paradigm->id,
                'pronominal_feature_name' => Feature::factory()->create([
                    'name' => '3p',
                    'person' => '3',
                    'number' => 3
                ])
            ])
        ]);
        NominalForm::factory()->create([
            'shape' => 'N-foo',
            'language_code' => $paradigm->language_code,
            'structure_id' => NominalStructure::factory()->create([
                'paradigm_id' => $paradigm->id,
                'pronominal_feature_name' => Feature::factory()->create([
                    'name' => '0p',
                    'person' => '0',
                    'number' => 3
                ])
            ])
        ]);
        NominalForm::factory()->create([
            'shape' => 'N-foo',
            'language_code' => $paradigm->language_code,
            'structure_id' => NominalStructure::factory()->create([
                'paradigm_id' => $paradigm->id,
                'pronominal_feature_name' => Feature::factory()->create([
                    'name' => '1p',
                    'person' => '1',
                    'number' => 3
                ])
            ])
        ]);

        $response = $this->get($paradigm->url);

        $response->assertOk();
        $response->assertSeeInOrder(['1p', '3p', '0p']);
    }

    /** @test */
    public function sources_appear_if_the_paradigm_has_sources()
    {
        $paradigm = NominalParadigm::factory()->create();
        $source = Source::factory()->create(['author' => 'Foo Bar']);
        $paradigm->addSource($source);

        $response = $this->get($paradigm->url);
        $response->assertOk();

        $response->assertSee('Sources');
        $response->assertSee('Foo Bar');
    }

    /** @test */
    public function sources_do_not_appear_if_the_paradigm_has_no_sources()
    {
        $paradigm = NominalParadigm::factory()->create();

        $response = $this->get($paradigm->url);
        $response->assertOk();

        $response->assertDontSee('Sources');
    }
}
