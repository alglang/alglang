<?php

namespace Tests\Unit;

use App\Language;
use App\Morpheme;
use App\Source;
use App\VerbForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LanguageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_url_property()
    {
        $language = factory(Language::class)->create(['algo_code' => 'PA']);
        $this->assertEquals('/languages/pa', $language->url);
    }

    /** @test */
    public function its_sources_are_those_of_its_data()
    {
        $language = factory(Language::class)->create();

        $morphemeSource = factory(Source::class)->create(['author' => 'Morpheme Source']);
        factory(Morpheme::class)->create([
            'language_id' => $language->id
        ])->addSource($morphemeSource);

        $verbFormSource = factory(Source::class)->create(['author' => 'Verbform Source']);
        factory(VerbForm::class)->create([
            'language_id' => $language->id
        ])->addSource($verbFormSource);

        $this->assertCount(2, $language->sources);
        $this->assertTrue($language->sources->contains($morphemeSource));
        $this->assertTrue($language->sources->contains($verbFormSource));
    }

    /** @test */
    public function it_loads_its_sources_count()
    {
        $language = factory(Language::class)->create();

        $source = factory(Source::class)->create(['author' => 'Morpheme Source']);
        factory(Morpheme::class)->create([
            'language_id' => $language->id
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
}
