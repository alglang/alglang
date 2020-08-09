<?php

namespace Tests\Unit;

use App\Example;
use App\Language;
use App\Morpheme;
use App\VerbForm;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_url_attribute()
    {
        $language = factory(Language::class)->create(['algo_code' => 'TL']);
        $form = factory(VerbForm::class)->create([
            'language_id' => $language->id,
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
    public function its_language_is_the_same_as_its_form()
    {
        $language = factory(Language::class)->create(['algo_code' => 'TL']);
        $example = factory(Example::class)->create([
            'form_id' => factory(VerbForm::class)->create(['language_id' => $language->id])->id
        ]);

        $this->assertEquals($language->id, $example->language->id);
    }

    /** @test */
    public function its_morphemes_are_the_same_as_its_forms_but_with_V_replaced_with_stem()
    {
        $language = factory(Language::class)->create();
        $stem = factory(Morpheme::class)->create([
            'language_id' => $language->id,
            'shape' => 'foo-'
        ]);
        $suffix = factory(Morpheme::class)->create([
            'language_id' => $language->id,
            'shape' => '-bar'
        ]);
        $form = factory(VerbForm::class)->create([
            'language_id' => $language->id,
            'morpheme_structure' => "{$language->vStem->id}-{$suffix->id}"
        ]);
        $example = factory(Example::class)->create([
            'form_id' => $form->id,
            'stem_id' => $stem->id
        ]);

        $this->assertEquals(['foo-', '-bar'], $example->morphemes->pluck('shape')->toArray());
    }
}