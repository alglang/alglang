<?php

namespace Tests\Unit;

use App\Feature;
use App\Language;
use App\VerbClass;
use App\VerbForm;
use App\VerbMode;
use App\VerbOrder;
use App\VerbParadigm;
use App\VerbStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerbParadigmTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_be_generated_from_a_language_and_verb_structure()
    {
        $language = factory(Language::class)->make(['id' => 440]);
        $structure = factory(VerbStructure::class)->make([
            'mode_name' => 'MODE',
            'class_abv' => 'CLASS',
            'order_name' => 'ORDER',
            'is_diminutive' => false,
            'is_negative' => true
        ]);

        $paradigm = VerbParadigm::generate($language, $structure);

        $this->assertEquals($language->id, $paradigm->language_id);
        $this->assertEquals('CLASS', $paradigm->class_abv);
        $this->assertEquals('MODE', $paradigm->mode_name);
        $this->assertEquals('ORDER', $paradigm->order_name);
        $this->assertEquals(false, $paradigm->is_diminutive);
        $this->assertEquals(true, $paradigm->is_negative);
    }

    /** @test */
    public function it_infers_that_it_should_have_a_subject()
    {
        $paradigm = new VerbParadigm([
            'language_id' => 440,
            'mode_name' => 'MODE',
            'class_abv' => 'CLASS',
            'order_name' => 'ORDER',
            'is_diminutive' => false,
            'is_negative' => true,
            'subject_name' => 'SUBJ'
        ]);

        $this->assertEquals('?', $paradigm->subject_name);
        $this->assertNull($paradigm->primary_object_name);
        $this->assertNull($paradigm->secondary_object_name);
    }

    /** @test */
    public function it_infers_that_it_should_have_a_primary_object()
    {
        $paradigm = new VerbParadigm([
            'language_id' => 440,
            'mode_name' => 'MODE',
            'class_abv' => 'CLASS',
            'order_name' => 'ORDER',
            'is_diminutive' => false,
            'is_negative' => true,
            'primary_object_name' => 'POBJ'
        ]);

        $this->assertNull($paradigm->subject_name);
        $this->assertEquals('?', $paradigm->primary_object_name);
        $this->assertNull($paradigm->secondary_object_name);
    }

    /** @test */
    public function it_infers_that_it_should_have_a_secondary_object()
    {
        $paradigm = new VerbParadigm([
            'language_id' => 440,
            'mode_name' => 'MODE',
            'class_abv' => 'CLASS',
            'order_name' => 'ORDER',
            'is_diminutive' => false,
            'is_negative' => true,
            'secondary_object_name' => 'POBJ'
        ]);

        $this->assertNull($paradigm->subject_name);
        $this->assertNull($paradigm->primary_object_name);
        $this->assertEquals('?', $paradigm->secondary_object_name);
    }

    /** @test */
    public function it_has_a_url()
    {
        $language = factory(Language::class)->create(['id' => 440]);

        $paradigm = new VerbParadigm([
            'language_id' => $language->id,
            'mode_name' => 'MODE',
            'class_abv' => 'CLASS',
            'order_name' => 'ORDER',
            'is_diminutive' => false,
            'is_negative' => true,
            'subject_name' => 'SUBJ',
            'primary_object_name' => null,
            'secondary_object_name' => null
        ]);

        $this->assertEquals(
            "/languages/$language->slug/verb-paradigms?class=CLASS&order=ORDER&mode=MODE&negative=1&diminutive=0&subject=%3F",
            $paradigm->url
        );
    }

    /** @test */
    public function it_has_a_name()
    {
        $paradigm = new VerbParadigm([
            'language_id' => 440,
            'mode_name' => 'MODE',
            'class_abv' => 'CLASS',
            'order_name' => 'ORDER',
            'is_diminutive' => false,
            'is_negative' => false
        ]);

        $this->assertEquals('CLASS ORDER MODE', $paradigm->name);
    }

    /** @test */
    public function it_adds_negative_to_its_name_if_it_is_negative()
    {
        $paradigm = new VerbParadigm([
            'language_id' => 440,
            'mode_name' => 'MODE',
            'class_abv' => 'CLASS',
            'order_name' => 'ORDER',
            'is_diminutive' => false,
            'is_negative' => true
        ]);

        $this->assertEquals('CLASS ORDER MODE (negative)', $paradigm->name);
    }

    /** @test */
    public function it_adds_diminutive_to_its_name_if_it_is_diminutive()
    {
        $paradigm = new VerbParadigm([
            'language_id' => 440,
            'mode_name' => 'MODE',
            'class_abv' => 'CLASS',
            'order_name' => 'ORDER',
            'is_diminutive' => true,
            'is_negative' => false
        ]);

        $this->assertEquals('CLASS ORDER MODE (diminutive)', $paradigm->name);
    }

    /** @test */
    public function it_adds_negative_and_diminutive_to_its_name_if_it_is_negative_and_diminutive()
    {
        $paradigm = new VerbParadigm([
            'language_id' => 440,
            'mode_name' => 'MODE',
            'class_abv' => 'CLASS',
            'order_name' => 'ORDER',
            'is_diminutive' => true,
            'is_negative' => true
        ]);

        $this->assertEquals('CLASS ORDER MODE (negative, diminutive)', $paradigm->name);
    }

    /** @test */
    public function it_has_forms()
    {
        $language = factory(Language::class)->create();

        $structure = factory(VerbStructure::class)->create();

        $form = factory(VerbForm::class)->create([
            'language_id' => $language,
            'structure_id' => $structure
        ]);

        $paradigm = VerbParadigm::generate($language, $structure);

        $this->assertCount(1, $paradigm->forms);
        $this->assertEquals($form->id, $paradigm->forms[0]->id);
    }

    /** @test */
    public function it_filters_forms_by_language()
    {
        $language1 = factory(Language::class)->create();
        $language2 = factory(Language::class)->create();

        $structure = factory(VerbStructure::class)->create();

        $form1 = factory(VerbForm::class)->create([
            'language_id' => $language1,
            'structure_id' => $structure
        ]);

        $form2 = factory(VerbForm::class)->create([
            'language_id' => $language2,
            'structure_id' => $structure
        ]);

        $paradigm = VerbParadigm::generate($language1, $structure);

        $this->assertCount(1, $paradigm->forms);
        $this->assertEquals($form1->id, $paradigm->forms[0]->id);
    }

    /** @test */
    public function it_filters_forms_by_structure()
    {
        $language = factory(Language::class)->create();
        $class = factory(VerbClass::class)->create(['abv' => 'CLS']);
        $mode = factory(VerbMode::class)->create(['name' => 'MODE']);
        $order = factory(VerbOrder::class)->create(['name' => 'ORDER']);

        $structure1 = factory(VerbStructure::class)->create([
            'class_abv' => $class,
            'mode_name' => $mode,
            'order_name' => $order,
            'is_negative' => true,
            'is_diminutive' => false
        ]);
        $structure2 = factory(VerbStructure::class)->create([
            'class_abv' => $class,
            'mode_name' => $mode,
            'order_name' => $order,
            'is_negative' => false,
            'is_diminutive' => false
        ]);

        $form1 = factory(VerbForm::class)->create([
            'language_id' => $language,
            'structure_id' => $structure1
        ]);

        $form2 = factory(VerbForm::class)->create([
            'language_id' => $language,
            'structure_id' => $structure2
        ]);

        $paradigm = VerbParadigm::generate($language, $structure1);

        $this->assertCount(1, $paradigm->forms);
        $this->assertEquals($form1->id, $paradigm->forms[0]->id);
    }
}
