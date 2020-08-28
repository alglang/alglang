<?php

namespace Tests\Unit;

use App\Models\Example;
use App\Models\Form;
use App\Models\Language;
use App\Models\Morpheme;
use App\Models\NominalForm;
use App\Models\VerbForm;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_url_attribute_when_it_has_a_verb_form()
    {
        $language = factory(Language::class)->create(['code' => 'TL']);
        $form = factory(VerbForm::class)->create([
            'language_code' => $language->code,
            'shape' => 'V-bar'
        ]);
        $example = factory(Example::class)->create([
            'form_id' => $form->id,
            'shape' => 'foo'
        ]);

        $expected = "/languages/$language->slug/verb-forms/$form->slug/examples/foo";
        $this->assertEquals($expected, $example->url);
    }

    /** @test */
    public function it_has_a_url_attribute_when_it_has_a_nominal_form()
    {
        $language = factory(Language::class)->create(['code' => 'TL']);
        $form = factory(NominalForm::class)->create([
            'language_code' => $language->code,
            'shape' => 'V-bar'
        ]);
        $example = factory(Example::class)->create([
            'form_id' => $form->id,
            'shape' => 'foo'
        ]);

        $expected = "/languages/$language->slug/nominal-forms/$form->slug/examples/foo";
        $this->assertEquals($expected, $example->url);
    }

    /** @test */
    public function its_language_is_the_same_as_its_form()
    {
        $language = factory(Language::class)->create(['code' => 'TL']);
        $example = factory(Example::class)->create([
            'form_id' => factory(Form::class)->create(['language_code' => $language->code])
        ]);

        $this->assertEquals($language->code, $example->language->code);
    }

    /** @test */
    public function its_morphemes_are_the_same_as_its_forms_but_with_V_replaced_with_stem()
    {
        $language = factory(Language::class)->create();
        $stem = factory(Morpheme::class)->create([
            'language_code' => $language->code,
            'shape' => 'foo-'
        ]);
        $suffix = factory(Morpheme::class)->create([
            'language_code' => $language->code,
            'shape' => '-bar'
        ]);
        $form = factory(Form::class)->create(['language_code' => $language->code]);
        $form->assignMorphemes([$language->vStem, $suffix]);

        $example = factory(Example::class)->create([
            'form_id' => $form->id,
            'stem_id' => $stem->id
        ]);

        $this->assertEquals(['foo-', '-bar'], $example->morphemes->pluck('shape')->toArray());
    }
}
