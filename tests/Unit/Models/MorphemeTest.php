<?php

namespace Tests\Unit\Models;

use App\Models\Form;
use App\Models\Gloss;
use App\Models\Language;
use App\Models\Morpheme;
use App\Models\NominalForm;
use App\Models\NominalParadigm;
use App\Models\NominalStructure;
use App\Models\VerbForm;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class MorphemeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_url_property()
    {
        $language = Language::factory()->create(['code' => 'PA']);
        $morpheme = Morpheme::factory()->create([
            'language_code' => $language->code,
            'shape' => '-ak'
        ]);
        $this->assertEquals('/languages/PA/morphemes/ak-1', $morpheme->url);
    }

    /** @test */
    public function it_preserves_utf_8_in_its_url()
    {
        $language = Language::factory()->create(['code' => 'PA']);
        $morpheme = Morpheme::factory()->create([
            'language_code' => $language->code,
            'shape' => '-a·'
        ]);
        $this->assertEquals('/languages/PA/morphemes/a·-1', $morpheme->url);
    }

    /** @test */
    public function it_updates_its_url_when_its_disambiguator_changes()
    {
        $language = Language::factory()->create(['code' => 'PA']);
        $morpheme1 = Morpheme::factory()->create([
            'language_code' => $language->code,
            'shape' => '-ak'
        ]);
        $morpheme2 = Morpheme::factory()->create([
            'language_code' => $language->code,
            'shape' => '-ak'
        ]);

        $this->assertEquals('/languages/PA/morphemes/ak-2', $morpheme2->url);

        $morpheme1->delete();

        $this->assertEquals('/languages/PA/morphemes/ak-1', $morpheme2->fresh()->url);
    }

    /** @test */
    public function its_slug_isnt_affected_by_morphemes_from_other_languages()
    {
        $language1 = Language::factory()->create(['code' => 'A']);
        $language2 = Language::factory()->create(['code' => 'B']);
        $morpheme1 = Morpheme::factory()->create([
            'language_code' => $language1->code,
            'shape' => '-x'
        ]);
        $morpheme2 = Morpheme::factory()->create([
            'language_code' => $language2->code,
            'shape' => '-x'
        ]);

        $this->assertEquals('/languages/B/morphemes/x-1', $morpheme2->url);
    }

    /** @test */
    public function language_is_always_eager_loaded()
    {
        Morpheme::factory()->create();
        $morpheme = Morpheme::first();

        $this->assertTrue($morpheme->relationLoaded('language'));
    }

    /** @test */
    public function it_fetches_gloss_models_based_on_its_gloss()
    {
        $gloss1 = Gloss::factory()->create(['abv' => 'AN']);
        $gloss2 = Gloss::factory()->create(['abv' => 'PL']);
        $morpheme = Morpheme::factory()->create(['gloss' => 'AN.PL']);

        $this->assertEquals(collect(['AN', 'PL']), $morpheme->glosses->pluck('abv'));
        $this->assertTrue($morpheme->glosses[0]->exists);
        $this->assertTrue($morpheme->glosses[1]->exists);
    }

    /** @test */
    public function it_handles_glosses_that_dont_exist()
    {
        $morpheme = Morpheme::factory()->create(['gloss' => 'AN.PL']);

        $this->assertEquals(collect(['AN', 'PL']), $morpheme->glosses->pluck('abv'));
        $this->assertFalse($morpheme->glosses[0]->exists);
    }

    /** @test */
    public function it_caches_the_glosses_attribute()
    {
        $gloss1 = Gloss::factory()->create(['abv' => 'AN']);
        $gloss2 = Gloss::factory()->create(['abv' => 'PL']);
        $morpheme = Morpheme::factory()->create(['gloss' => 'AN.PL']);

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
        $language1 = Language::factory()->create();
        $language2 = Language::factory()->create();

        $morpheme1 = Morpheme::factory()->create([
            'language_code' => $language1->code,
            'shape' => '-ak'
        ]);
        $morpheme2 = Morpheme::factory()->create([
            'language_code' => $language1->code,
            'shape' => '-ak'
        ]);
        $morpheme3 = Morpheme::factory()->create([
            'language_code' => $language1->code,
            'shape' => 'foo-'
        ]);
        $morpheme4 = Morpheme::factory()->create([
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
        $morpheme = Morpheme::factory()->create(['slot_abv' => 'STM']);
        $this->assertTrue($morpheme->isStem());
    }

    /** @test */
    public function it_determines_if_it_is_not_a_stem()
    {
        $morpheme = Morpheme::factory()->create(['slot_abv' => 'FOO']);
        $this->assertFalse($morpheme->isStem());
    }

    /** @test */
    public function it_determines_if_it_is_a_placeholder()
    {
        $vPlaceholder = new Morpheme(['shape' => 'V-']);
        $nPlaceholder = new Morpheme(['shape' => 'N-']);

        $this->assertTrue($vPlaceholder->isPlaceholder());
        $this->assertTrue($nPlaceholder->isPlaceholder());
    }

    /** @test */
    public function it_retrieves_forms_that_contain_it()
    {
        $language = Language::factory()->create();
        $morpheme = Morpheme::factory()->create(['language_code' => $language->code]);

        $form = Form::factory()->create(['language_code' => $language->code]);
        $form->assignMorphemes([$morpheme]);

        $forms = $morpheme->forms;

        $this->assertCount(1, $forms);
        $this->assertContains($form->id, $forms->pluck('id')->toArray());
    }

    /** @test */
    public function it_does_not_include_forms_from_other_languages()
    {
        $morpheme = Morpheme::factory()->create(['language_code' => Language::factory()->create()->code]);
        $form = Form::factory()->create(['language_code' => Language::factory()->create()->code]);
        $form->assignMorphemes([$morpheme]);

        $this->assertCount(0, $morpheme->forms);
    }

    /** @test */
    public function it_only_counts_forms_with_duplicate_morphemes_once()
    {
        $morpheme = Morpheme::factory()->create(['language_code' => Language::factory()->create()->code]);
        $form = Form::factory()->create(['language_code' => $morpheme->language_code]);
        $form->assignMorphemes([$morpheme, $morpheme]);

        $this->assertCount(1, $morpheme->forms);
    }

    /** @test */
    public function it_retrieves_verb_forms_that_contain_it()
    {
        $language = Language::factory()->create();
        $morpheme = Morpheme::factory()->create(['language_code' => $language->code]);
        $verbForm = VerbForm::factory()->create(['language_code' => $language->code]);
        $nominalForm = NominalForm::factory()->create([
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
        $language = Language::factory()->create();
        $morpheme = Morpheme::factory()->create(['language_code' => $language->code]);
        $verbForm = VerbForm::factory()->create(['language_code' => $language->code]);
        $nominalForm = NominalForm::factory()->create([
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
        $language = Language::factory()->create();
        Morpheme::factory()->create(['shape' => 'foo-', 'language_code' => $language->code]);
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
