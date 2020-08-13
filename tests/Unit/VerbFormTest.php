<?php

namespace Tests\Unit;

use App\Language;
use App\Morpheme;
use App\VerbFeature;
use App\VerbForm;
use App\VerbStructure;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VerbFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_url()
    {
        $language = factory(Language::class)->create(['algo_code' => 'PA']);
        $verbForm = factory(VerbForm::class)->create([
            'shape' => 'V-test',
            'language_id' => $language->id
        ]);
        $this->assertEquals('/languages/pa/verb-forms/V-test', $verbForm->url);
    }

    /** @test */
    public function it_preserves_utf_8_in_its_url()
    {
        $language = factory(Language::class)->create(['algo_code' => 'PA']);
        $verbForm = factory(VerbForm::class)->create([
            'shape' => 'V-(o)wa·či',
            'language_id' => $language->id
        ]);
        $this->assertEquals('/languages/pa/verb-forms/V-(o)wa·či', $verbForm->url);
    }

    /** @test */
    public function language_is_always_eager_loaded()
    {
        factory(VerbForm::class)->create();
        $verbForm = VerbForm::first();

        $this->assertTrue($verbForm->relationLoaded('language'));
    }

    /*
    |--------------------------------------------------------------------------
    | Morpheme connections
    |--------------------------------------------------------------------------
    |
    */

    /** @test */
    public function its_morphemes_are_empty_if_it_has_no_morpheme_structure()
    {
        $verbForm = factory(VerbForm::class)->create(['morpheme_structure' => null]);
        $this->assertCount(0, $verbForm->morphemes);
    }

    /** @test */
    public function it_retreives_morphemes_based_on_its_morpheme_structure()
    {
        $language = factory(Language::class)->create();
        $morpheme1 = factory(Morpheme::class)->create([
            'language_id' => $language->id,
            'shape' => 'a-'
        ]);
        $morpheme2 = factory(Morpheme::class)->create([
            'language_id' => $language->id,
            'shape' => 'b-'
        ]);
        $verbForm = factory(VerbForm::class)->create([
            'language_id' => $language->id,
            'morpheme_structure' => "{$morpheme2->id}-{$morpheme1->id}"
        ]);

        $morphemes = $verbForm->morphemes;

        $this->assertEquals(['b-', 'a-'], $morphemes->pluck('shape')->toArray());
    }

    /** @test */
    public function it_creates_dummy_morphemes_if_they_are_not_in_the_database()
    {
        $verbForm = factory(VerbForm::class)->create(['morpheme_structure' => 'foo']);

        $morphemes = $verbForm->morphemes;

        $this->assertCount(1, $morphemes);
        $this->assertEquals('foo', $morphemes[0]->shape);
        $this->assertEquals($verbForm->language_id, $morphemes[0]->language_id);
    }
}
