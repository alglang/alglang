<?php

namespace Tests\Feature;

use App\Feature;
use App\Language;
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
                'pronominal_feature_id' => factory(Feature::class)->create(['name' => '2p']),
                'nominal_feature_id' => factory(Feature::class)->create(['name' => '3p'])
            ])->id
        ]);

        $response = $this->get($paradigm->url);

        $response->assertOk();
        $response->assertSee('2p');
        $response->assertSee('3p');
        $response->assertSee('N-foo');
    }
}
