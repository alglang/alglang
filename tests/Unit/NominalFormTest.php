<?php

namespace Tests\Unit;

use App\Language;
use App\NominalForm;
use App\NominalParadigm;
use App\NominalStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NominalFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_url()
    {
        $language = factory(Language::class)->create(['algo_code' => 'PA']);
        $form = factory(NominalForm::class)->create([
            'shape' => 'N-test',
            'language_id' => $language->id,
            'structure_id' => factory(NominalStructure::class)->create([
                'paradigm_id' => factory(NominalParadigm::class)->create([
                    'language_id' => $language->id
                ])->id
            ])->id
        ]);
        $this->assertEquals('/languages/pa/nominal-forms/N-test', $form->url);
    }
}
