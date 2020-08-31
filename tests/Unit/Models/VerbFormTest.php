<?php

namespace Tests\Unit\Models;

use App\Models\Language;
use App\Models\VerbForm;
use App\Models\VerbStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerbFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_url()
    {
        $language = factory(Language::class)->create(['code' => 'PA']);
        $form = factory(VerbForm::class)->create([
            'shape' => 'V-test',
            'language_code' => $language->code
        ]);
        $this->assertEquals('/languages/PA/verb-forms/V-test', $form->url);
    }

    /** @test */
    public function it_has_a_paradigm()
    {
        $language = factory(Language::class)->create(['code' => 'PA']);
        $form = factory(VerbForm::class)->make([
            'shape' => 'V-test',
            'language_code' => $language,
            'structure_id' => factory(VerbStructure::class)->create([
                'mode_name' => 'MODE',
                'class_abv' => 'CLASS',
                'order_name' => 'ORDER',
                'is_diminutive' => false,
                'is_negative' => true
            ])
        ]);

        $paradigm = $form->paradigm;

        $this->assertEquals($language->code, $paradigm->language_code);
        $this->assertEquals('CLASS', $paradigm->class_abv);
        $this->assertEquals('MODE', $paradigm->mode_name);
        $this->assertEquals('ORDER', $paradigm->order_name);
        $this->assertEquals(0, $paradigm->is_diminutive);
        $this->assertEquals(1, $paradigm->is_negative);
    }
}
