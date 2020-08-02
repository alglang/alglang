<?php

namespace Tests\Unit;

use \DB;
use App\Gloss;
use App\Language;
use App\Morpheme;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MorphemeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_url_property()
    {
        $language = factory(Language::class)->create(['algo_code' => 'PA']);
        $morpheme = factory(Morpheme::class)->create([
            'language_id' => $language->id,
            'shape' => '-ak'
        ]);
        $this->assertEquals('/languages/pa/morphemes/ak-1', $morpheme->url);
    }

    /** @test */
    public function it_preserves_utf_8_in_its_url()
    {
        $language = factory(Language::class)->create(['algo_code' => 'PA']);
        $morpheme = factory(Morpheme::class)->create([
            'language_id' => $language->id,
            'shape' => '-a·'
        ]);
        $this->assertEquals('/languages/pa/morphemes/a·-1', $morpheme->url);
    }

    /** @test */
    public function it_updates_its_url_when_its_disambiguator_changes()
    {
        
        $language = factory(Language::class)->create(['algo_code' => 'PA']);
        $morpheme1 = factory(Morpheme::class)->create([
            'language_id' => $language->id,
            'shape' => '-ak'
        ]);
        $morpheme2 = factory(Morpheme::class)->create([
            'language_id' => $language->id,
            'shape' => '-ak'
        ]);

        $this->assertEquals('/languages/pa/morphemes/ak-2', $morpheme2->url);

        $morpheme1->delete();

        $this->assertEquals('/languages/pa/morphemes/ak-1', $morpheme2->fresh()->url);
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
            'language_id' => $language1->id,
            'shape' => '-ak'
        ]);
        $morpheme2 = factory(Morpheme::class)->create([
            'language_id' => $language1->id,
            'shape' => '-ak'
        ]);
        $morpheme3 = factory(Morpheme::class)->create([
            'language_id' => $language1->id,
            'shape' => 'foo-'
        ]);
        $morpheme4 = factory(Morpheme::class)->create([
            'language_id' => $language2->id,
            'shape' => '-ak'
        ]);

        $this->assertSame(0, $morpheme1->fresh()->disambiguator);  // First morpheme in database
        $this->assertSame(1, $morpheme2->fresh()->disambiguator);  // Duplicate of previous morpheme
        $this->assertSame(0, $morpheme3->fresh()->disambiguator);  // Different shape - no duplicate
        $this->assertSame(0, $morpheme4->fresh()->disambiguator);  // Different language - no duplicate
    }
}
