<?php

namespace Tests\Unit;

use \DB;
use App\Form;
use App\Gloss;
use App\Language;
use App\Morpheme;
use App\NominalForm;
use App\NominalParadigm;
use App\NominalStructure;
use App\VerbForm;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MorphemeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_url_property()
    {
        $language = factory(Language::class)->create(['code' => 'PA']);
        $morpheme = factory(Morpheme::class)->create([
            'language_code' => $language->code,
            'shape' => '-ak'
        ]);
        $this->assertEquals('/languages/PA/morphemes/ak-1', $morpheme->url);
    }

    /** @test */
    public function it_preserves_utf_8_in_its_url()
    {
        $language = factory(Language::class)->create(['code' => 'PA']);
        $morpheme = factory(Morpheme::class)->create([
            'language_code' => $language->code,
            'shape' => '-a·'
        ]);
        $this->assertEquals('/languages/PA/morphemes/a·-1', $morpheme->url);
    }

    /** @test */
    public function it_updates_its_url_when_its_disambiguator_changes()
    {
        
        $language = factory(Language::class)->create(['code' => 'PA']);
        $morpheme1 = factory(Morpheme::class)->create([
            'language_code' => $language->code,
            'shape' => '-ak'
        ]);
        $morpheme2 = factory(Morpheme::class)->create([
            'language_code' => $language->code,
            'shape' => '-ak'
        ]);

        $this->assertEquals('/languages/PA/morphemes/ak-2', $morpheme2->url);

        $morpheme1->delete();

        $this->assertEquals('/languages/PA/morphemes/ak-1', $morpheme2->fresh()->url);
    }

    /** @test */
    public function language_is_always_eager_loaded()
    {
        factory(Morpheme::class)->create();
        $morpheme = Morpheme::first();

        $this->assertTrue($morpheme->relationLoaded('language'));
    }

    /** @test */
    public function it_fetches_gloss_models_based_on_its_gloss()
    {
        $gloss1 = factory(Gloss::class)->create(['abv' => 'AN']);
        $gloss2 = factory(Gloss::class)->create(['abv' => 'PL']);
        $morpheme = factory(Morpheme::class)->create(['gloss' => 'AN.PL']);

        $this->assertEquals(collect(['AN', 'PL']), $morpheme->glosses->pluck('abv'));
        $this->assertTrue($morpheme->glosses[0]->exists);
        $this->assertTrue($morpheme->glosses[1]->exists);
    }

    /** @test */
    public function it_handles_glosses_that_dont_exist()
    {
        $morpheme = factory(Morpheme::class)->create(['gloss' => 'AN.PL']);

        $this->assertEquals(collect(['AN', 'PL']), $morpheme->glosses->pluck('abv'));
        $this->assertFalse($morpheme->glosses[0]->exists);
    }

    /** @test */
    public function it_caches_the_glosses_attribute()
    {
        $gloss1 = factory(Gloss::class)->create(['abv' => 'AN']);
        $gloss2 = factory(Gloss::class)->create(['abv' => 'PL']);
        $morpheme = factory(Morpheme::class)->create(['gloss' => 'AN.PL']);

        DB::connection()->enableQueryLog();

        $morpheme->glosses;
        $this->assertCount(1, DB::getQueryLog());  // First call should run a query

        $morpheme->glosses;
        $this->assertCount(1, DB::getQueryLog());  // Glosses should be cached; no further queries

        DB::connection()->disableQueryLog();
    }

    /** @test */
    public function it_has_a_unique_disambiguator()
    {
        $language1 = factory(Language::class)->create();
        $language2 = factory(Language::class)->create();

        $morpheme1 = factory(Morpheme::class)->create([
            'language_code' => $language1->code,
            'shape' => '-ak'
        ]);
        $morpheme2 = factory(Morpheme::class)->create([
            'language_code' => $language1->code,
            'shape' => '-ak'
        ]);
        $morpheme3 = factory(Morpheme::class)->create([
            'language_code' => $language1->code,
            'shape' => 'foo-'
        ]);
        $morpheme4 = factory(Morpheme::class)->create([
            'language_code' => $language2->code,
            'shape' => '-ak'
        ]);

        $this->assertSame(0, $morpheme1->fresh()->disambiguator);  // First morpheme in database
        $this->assertSame(1, $morpheme2->fresh()->disambiguator);  // Duplicate of previous morpheme
        $this->assertSame(0, $morpheme3->fresh()->disambiguator);  // Different shape - no duplicate
        $this->assertSame(0, $morpheme4->fresh()->disambiguator);  // Different language - no duplicate
    }

    /** @test */
    public function it_determines_if_it_is_a_stem()
    {
        $morpheme = factory(Morpheme::class)->create(['slot_abv' => 'STM']);
        $this->assertTrue($morpheme->isStem());
    }

    /** @test */
    public function it_determines_if_it_is_not_a_stem()
    {
        $morpheme = factory(Morpheme::class)->create(['slot_abv' => 'FOO']);
        $this->assertFalse($morpheme->isStem());
    }

    /** @test */
    public function it_retrieves_forms_that_contain_it()
    {
        $language = factory(Language::class)->create();
        $morpheme = factory(Morpheme::class)->create(['language_code' => $language->code]);

        $form = factory(Form::class)->create(['language_code' => $language->code]);
        $form->assignMorphemes([$morpheme]);

        $forms = $morpheme->forms;

        $this->assertCount(1, $forms);
        $this->assertContains($form->id, $forms->pluck('id')->toArray());
    }

    /** @test */
    public function it_does_not_include_forms_from_other_languages()
    {
        $morpheme = factory(Morpheme::class)->create(['language_code' => factory(Language::class)->create()->code]);
        $form = factory(Form::class)->create(['language_code' => factory(Language::class)->create()->code]);
        $form->assignMorphemes([$morpheme]);

        $this->assertCount(0, $morpheme->forms);
    }

    /** @test */
    public function it_only_counts_forms_with_duplicate_morphemes_once()
    {
        $morpheme = factory(Morpheme::class)->create(['language_code' => factory(Language::class)->create()->code]);
        $form = factory(Form::class)->create(['language_code' => $morpheme->language_code]);
        $form->assignMorphemes([$morpheme, $morpheme]);

        $this->assertCount(1, $morpheme->forms);
    }

    /** @test */
    public function it_retrieves_verb_forms_that_contain_it()
    {
        $language = factory(Language::class)->create();
        $morpheme = factory(Morpheme::class)->create(['language_code' => $language->code]);
        $verbForm = factory(VerbForm::class)->create(['language_code' => $language->code]);
        $nominalForm = factory(NominalForm::class)->create([
            'language_code' => $language->code,
        ]);
        $verbForm->assignMorphemes([$morpheme]);
        $nominalForm->assignMorphemes([$morpheme]);

        $verbForms = $morpheme->verbForms;

        $this->assertCount(1, $verbForms);
        $this->assertEquals($verbForm->id, $verbForms[0]->id);
    }

    /** @test */
    public function it_retrieves_nominal_forms_that_contain_it()
    {
        $language = factory(Language::class)->create();
        $morpheme = factory(Morpheme::class)->create(['language_code' => $language->code]);
        $verbForm = factory(VerbForm::class)->create(['language_code' => $language->code]);
        $nominalForm = factory(NominalForm::class)->create([
            'language_code' => $language->code,
        ]);
        $verbForm->assignMorphemes([$morpheme]);
        $nominalForm->assignMorphemes([$morpheme]);

        $nominalForms = $morpheme->nominalForms;

        $this->assertCount(1, $nominalForms);
        $this->assertEquals($nominalForm->id, $nominalForms[0]->id);
    }

    /** @test */
    public function it_can_exclude_placeholders_from_queries()
    {
        $language = factory(Language::class)->create();
        factory(Morpheme::class)->create(['shape' => 'foo-', 'language_code' => $language->code]);
        $morphemes = Morpheme::all();

        // Verify that placeholder morphemes were created
        $this->assertCount(3, $morphemes);
        $this->assertContains('N-', $morphemes->pluck('shape'));
        $this->assertContains('V-', $morphemes->pluck('shape'));
        $this->assertContains('foo-', $morphemes->pluck('shape'));

        $filteredMorphemes = Morpheme::withoutPlaceholders()->get();

        $this->assertCount(1, $filteredMorphemes);
        $this->assertContains('foo-', $filteredMorphemes->pluck('shape'));
    }
}
