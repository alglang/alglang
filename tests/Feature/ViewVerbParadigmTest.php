<?php

namespace Tests\Feature;

use App\Models\Feature;
use App\Models\Language;
use App\Models\VerbClass;
use App\Models\VerbForm;
use App\Models\VerbMode;
use App\Models\VerbOrder;
use App\Models\VerbStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewVerbParadigmTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_the_correct_view()
    {
        $verbForm = VerbForm::factory()->create();

        $response = $this->get($verbForm->paradigm->url);

        $response->assertOk();
        $response->assertViewIs('verb-paradigms.show');
    }

    /** @test */
    public function it_passes_in_a_paradigm()
    {
        $language = Language::factory()->create();
        $verbForm = VerbForm::factory()->create([
            'language_code' => $language,
            'structure_id' => VerbStructure::factory()->create([
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
        $verbForm = VerbForm::factory()->create([
            'language_code' => Language::factory()->create(),
            'structure_id' => VerbStructure::factory()->create([
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
        $language = Language::factory()->create();
        $class = VerbClass::factory()->create(['abv' => 'CLS']);
        $mode = VerbMode::factory()->create(['name' => 'MODE']);
        $order = VerbOrder::factory()->create(['name' => 'ORDER']);

        $structure1 = VerbStructure::factory()->create([
            'class_abv' => $class,
            'mode_name' => $mode,
            'order_name' => $order,
            'is_negative' => false,
            'is_diminutive' => false,
            'subject_name' => Feature::factory()->create(['name' => '1s'])
        ]);
        $structure2 = VerbStructure::factory()->create([
            'class_abv' => $class,
            'mode_name' => $mode,
            'order_name' => $order,
            'is_negative' => false,
            'is_diminutive' => false,
            'subject_name' => Feature::factory()->create(['name' => '1p'])
        ]);

        $form1 = VerbForm::factory()->create([
            'shape' => 'V-foo',
            'language_code' => $language,
            'structure_id' => $structure1
        ]);
        $form2 = VerbForm::factory()->create([
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
        $language = Language::factory()->create();
        $class = VerbClass::factory()->create(['abv' => 'CLS']);
        $mode = VerbMode::factory()->create(['name' => 'MODE']);
        $order = VerbOrder::factory()->create(['name' => 'ORDER']);

        $structure1 = VerbStructure::factory()->create([
            'class_abv' => $class,
            'mode_name' => $mode,
            'order_name' => $order,
            'is_negative' => false,
            'is_diminutive' => false,
            'subject_name' => Feature::factory()->create([
                'name' => '1p',
                'person' => '1',
                'number' => 3
            ])
        ]);
        $structure2 = VerbStructure::factory()->create([
            'class_abv' => $class,
            'mode_name' => $mode,
            'order_name' => $order,
            'is_negative' => false,
            'is_diminutive' => false,
            'subject_name' => Feature::factory()->create([
                'name' => '1s',
                'person' => '1',
                'number' => 1
            ])
        ]);

        $form1 = VerbForm::factory()->create([
            'shape' => 'V-foo',
            'language_code' => $language,
            'structure_id' => $structure1
        ]);
        $form2 = VerbForm::factory()->create([
            'shape' => 'V-bar',
            'language_code' => $language,
            'structure_id' => $structure2
        ]);

        $response = $this->get($form1->paradigm->url);

        $response->assertOk();
        $response->assertSeeInOrder(['V-bar', 'V-foo']);
    }
}
