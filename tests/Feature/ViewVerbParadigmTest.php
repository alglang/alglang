<?php

namespace Tests\Feature;

use App\Feature;
use App\Language;
use App\VerbClass;
use App\VerbForm;
use App\VerbMode;
use App\VerbOrder;
use App\VerbStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewVerbParadigmTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_the_correct_view()
    {
        $verbForm = factory(VerbForm::class)->create();

        $response = $this->get($verbForm->paradigm->url);

        $response->assertOk();
        $response->assertViewIs('verb-paradigms.show');
    }

    /** @test */
    public function it_passes_in_a_paradigm()
    {
        $language = factory(Language::class)->create();
        $verbForm = factory(VerbForm::class)->create([
            'language_code' => $language,
            'structure_id' => factory(VerbStructure::class)->create([
                'mode_name' => 'MODE',
                'class_abv' => 'CLASS',
                'order_name' => 'ORDER',
                'is_negative' => true,
                'is_diminutive' => false
            ])
        ]);

        $response = $this->get($verbForm->paradigm->url);

        $response->assertOk();
        $response->assertViewHas('paradigm');
        $this->assertEquals($language->code, $response['paradigm']->language_code);
        $this->assertEquals('MODE', $response['paradigm']->mode_name);
        $this->assertEquals('CLASS', $response['paradigm']->class_abv);
        $this->assertEquals('ORDER', $response['paradigm']->order_name);
        $this->assertEquals(1, $response['paradigm']->is_negative);
        $this->assertEquals(0, $response['paradigm']->is_diminutive);
    }

    /** @test */
    public function it_shows_its_name()
    {
        $verbForm = factory(VerbForm::class)->create([
            'language_code' => factory(Language::class)->create(),
            'structure_id' => factory(VerbStructure::class)->create([
                'mode_name' => 'MODE',
                'class_abv' => 'CLASS',
                'order_name' => 'ORDER',
                'is_negative' => true,
                'is_diminutive' => false
            ])
        ]);

        $response = $this->get($verbForm->paradigm->url);

        $response->assertOk();
        $response->assertSee('CLASS ORDER MODE (negative)');
    }

    /** @test */
    public function it_shows_its_forms()
    {
        $language = factory(Language::class)->create();
        $class = factory(VerbClass::class)->create(['abv' => 'CLS']);
        $mode = factory(VerbMode::class)->create(['name' => 'MODE']);
        $order = factory(VerbOrder::class)->create(['name' => 'ORDER']);

        $structure1 = factory(VerbStructure::class)->create([
            'class_abv' => $class,
            'mode_name' => $mode,
            'order_name' => $order,
            'is_negative' => false,
            'is_diminutive' => false,
            'subject_name' => factory(Feature::class)->create(['name' => '1s'])
        ]);
        $structure2 = factory(VerbStructure::class)->create([
            'class_abv' => $class,
            'mode_name' => $mode,
            'order_name' => $order,
            'is_negative' => false,
            'is_diminutive' => false,
            'subject_name' => factory(Feature::class)->create(['name' => '1p'])
        ]);

        $form1 = factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'language_code' => $language,
            'structure_id' => $structure1
        ]);
        $form2 = factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'language_code' => $language,
            'structure_id' => $structure2
        ]);

        $response = $this->get($form1->paradigm->url);

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertSee('V-bar');
    }

    /** @test */
    public function it_shows_its_forms_orderd_by_features()
    {
        $language = factory(Language::class)->create();
        $class = factory(VerbClass::class)->create(['abv' => 'CLS']);
        $mode = factory(VerbMode::class)->create(['name' => 'MODE']);
        $order = factory(VerbOrder::class)->create(['name' => 'ORDER']);

        $structure1 = factory(VerbStructure::class)->create([
            'class_abv' => $class,
            'mode_name' => $mode,
            'order_name' => $order,
            'is_negative' => false,
            'is_diminutive' => false,
            'subject_name' => factory(Feature::class)->create([
                'name' => '1p',
                'person' => '1',
                'number' => 3
            ])
        ]);
        $structure2 = factory(VerbStructure::class)->create([
            'class_abv' => $class,
            'mode_name' => $mode,
            'order_name' => $order,
            'is_negative' => false,
            'is_diminutive' => false,
            'subject_name' => factory(Feature::class)->create([
                'name' => '1s',
                'person' => '1',
                'number' => 1
            ])
        ]);

        $form1 = factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'language_code' => $language,
            'structure_id' => $structure1
        ]);
        $form2 = factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'language_code' => $language,
            'structure_id' => $structure2
        ]);

        $response = $this->get($form1->paradigm->url);

        $response->assertOk();
        $response->assertSeeInOrder(['V-bar', 'V-foo']);
    }
}
