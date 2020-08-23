<?php

namespace Tests\Unit;

use App\Language;
use App\Morpheme;
use App\NominalForm;
use App\NominalParadigm;
use App\NominalStructure;
use App\Source;
use App\VerbForm;
use DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LanguageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function its_slug_is_its_code()
    {
        $language = factory(Language::class)->make(['code' => 'PA']);
        $this->assertEquals('PA', $language->slug);
    }

    /** @test */
    public function it_has_a_url_property()
    {
        $language = factory(Language::class)->create(['code' => 'PA']);
        $this->assertEquals('/languages/PA', $language->url);
    }

    /** @test */
    public function its_sources_are_those_of_its_data()
    {
        $language = factory(Language::class)->create();

        $morphemeSource = factory(Source::class)->create(['author' => 'Morpheme Source']);
        factory(Morpheme::class)->create([
            'language_code' => $language->code
        ])->addSource($morphemeSource);

        $verbFormSource = factory(Source::class)->create(['author' => 'Verbform Source']);
        factory(VerbForm::class)->create([
            'language_code' => $language->code
        ])->addSource($verbFormSource);

        $nominalParadigmSource = factory(Source::class)->create(['author' => 'NominalParadigm Source']);
        $nominalParadigm = factory(NominalParadigm::class)->create([
            'language_code' => $language->code
        ])->addSource($nominalParadigmSource);

        $nominalFormSource = factory(Source::class)->create(['author' => 'Nominalform Source']);
        factory(NominalForm::class)->create([
            'language_code' => $language->code,
        ])->addSource($nominalFormSource);

        $this->assertCount(4, $language->sources);
        $this->assertTrue($language->sources->contains($morphemeSource));
        $this->assertTrue($language->sources->contains($verbFormSource));
        $this->assertTrue($language->sources->contains($nominalParadigmSource));
        $this->assertTrue($language->sources->contains($nominalFormSource));
    }

    /** @test */
    public function it_loads_its_sources_count()
    {
        $language = factory(Language::class)->create();

        $source = factory(Source::class)->create(['author' => 'Morpheme Source']);
        factory(Morpheme::class)->create([
            'language_code' => $language->code
        ])->addSource($source);

        $language->loadSourcesCount();

        $this->assertEquals(1, $language->sources_count);
    }

    /** @test */
    public function it_retrieves_its_verb_stem()
    {
        $language = factory(Language::class)->create();
        $this->assertEquals('V-', $language->vStem->shape);
    }

    /** @test */
    public function it_caches_the_sources_attribute()
    {
        $language = factory(Language::class)->create();

        DB::connection()->enableQueryLog();

        $language->sources;
        $queryCount = count(DB::getQueryLog());

        $language->sources;
        $this->assertCount($queryCount, DB::getQueryLog());

        DB::connection()->disableQueryLog();
    }

    /** @test */
    public function its_forms_are_its_nominal_and_verb_forms_combined()
    {
        $language = factory(Language::class)->create();
        $verbForm = factory(VerbForm::class)->create(['language_code' => $language->code]);
        $nominalForm = factory(NominalForm::class)->create(['language_code' => $language->code]);

        $this->assertCount(2, $language->forms);
        $this->assertEquals([$verbForm->id, $nominalForm->id], $language->forms->pluck('id')->toArray());
    }
}
