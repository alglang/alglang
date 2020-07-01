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
        $this->assertEquals('/languages/pa/morphemes/ak', $morpheme->url);
    }

    /** @test */
    public function shapes_with_length_symbols_are_handled_in_slugs()
    {
        $language = factory(Language::class)->create(['algo_code' => 'PA']);
        $morpheme = factory(Morpheme::class)->create([
            'language_id' => $language->id,
            'shape' => '-a·'
        ]);
        $this->assertEquals('/languages/pa/morphemes/a0', $morpheme->url);
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
}
