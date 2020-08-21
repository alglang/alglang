<?php

namespace Tests\Unit;

use App\Language;
use App\VerbForm;
use App\VerbStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerbFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_url()
    {
        $language = factory(Language::class)->create(['algo_code' => 'PA']);
        $form = factory(VerbForm::class)->create([
            'shape' => 'V-test',
            'language_id' => $language->id
        ]);
        $this->assertEquals('/languages/pa/verb-forms/V-test', $form->url);
    }

    /** @test */
    public function it_has_a_paradigm()
    {
        $language = factory(Language::class)->create(['algo_code' => 'PA']);
        $form = factory(VerbForm::class)->make([
            'shape' => 'V-test',
            'language_id' => $language,
            'structure_id' => factory(VerbStructure::class)->create([
                'mode_name' => 'MODE',
                'class_abv' => 'CLASS',
                'order_name' => 'ORDER',
                'is_diminutive' => false,
                'is_negative' => true
            ])
        ]);

        $paradigm = $form->paradigm;

        $this->assertEquals($language->id, $paradigm->language_id);
        $this->assertEquals('CLASS', $paradigm->class_abv);
        $this->assertEquals('MODE', $paradigm->mode_name);
        $this->assertEquals('ORDER', $paradigm->order_name);
        $this->assertEquals(0, $paradigm->is_diminutive);
        $this->assertEquals(1, $paradigm->is_negative);
    }
}
