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
    public function language_is_always_eager_loaded()
    {
        factory(Morpheme::class)->create();
        $morpheme = Morpheme::first();

        $this->assertTrue($morpheme->relationLoaded('language'));
    }

    /** @test */
    public function it_has_a_gloss_string()
    {
        $morpheme = factory(Morpheme::class)->create();
        $gloss1 = factory(Gloss::class)->create(['abv' => 'AN']);
        $gloss2 = factory(Gloss::class)->create(['abv' => 'PL']);
        $morpheme->glosses()->attach([$gloss1->id, $gloss2->id]);

        $this->assertEquals('AN.PL', $morpheme->gloss);
    }
}
