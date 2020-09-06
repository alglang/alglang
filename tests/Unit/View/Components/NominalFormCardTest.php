<?php

namespace Tests\Unit\View\Components;

use App\Models\Feature;
use App\Models\NominalForm;
use App\Models\NominalParadigm;
use App\Models\NominalStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NominalFormCardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_a_formatted_shape()
    {
        $form = factory(NominalForm::class)->create(['shape' => 'V-foo']);

        $view = $this->blade('<x-nominal-form-card :form="$form" />', compact('form'));

        $view->assertSee($form->formatted_shape, false);
    }

    /** @test */
    public function it_shows_the_feature_string()
    {
        $form = factory(NominalForm::class)->create([
            'structure_id' => factory(NominalStructure::class)->create([
                'pronominal_feature_name' => factory(Feature::class)->create(['name' => '1s']),
            ])
        ]);

        $view = $this->blade('<x-nominal-form-card :form="$form" />', compact('form'));

        $view->assertSee('1s', false);
    }

    /** @test */
    public function it_shows_its_paradigm()
    {
        $form = factory(NominalForm::class)->create([
            'structure_id' => factory(NominalStructure::class)->create([
                'paradigm_id' => factory(NominalParadigm::class)->create(['name' => 'theparadigm']),
            ])
        ]);

        $view = $this->blade('<x-nominal-form-card :form="$form" />', compact('form'));

        $view->assertSee('theparadigm');
    }
}
