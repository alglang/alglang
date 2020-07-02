<?php

namespace Tests\Feature;

use App\Gloss;
use App\Language;
use App\Morpheme;
use App\Slot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewMorphemeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_morpheme_can_be_viewed()
    {
        $this->withoutMix();

        $language = factory(Language::class)->create(['name' => 'Test Language']);
        $slot = factory(Slot::class)->create(['abv' => 'PER']);
        $gloss1 = factory(Gloss::class)->create(['abv' => 'AN', 'name' => 'Gloss name']);
        $gloss2 = factory(Gloss::class)->create(['abv' => 'PL']);

        $morpheme = factory(Morpheme::class)->create([
            'shape' => '-ak',
            'language_id' => $language->id,
            'slot_abv' => $slot->abv,
            'historical_notes' => 'The quick brown fox jumps over the lazy brown dog',
            'allomorphy_notes' => 'Lorem ipsum dolor sit amet',
            'private_notes' => 'Abcdefghijklmnopqrstuvwxyz',
            'gloss' => 'AN.PL'
        ]);

        $response = $this->get($morpheme->url);

        $response->assertOk();
        $response->assertSee('-ak');
        $response->assertSee('Test Language');
        $response->assertSee('PER');
        $response->assertSee("\"url\":\"\\/slots\\/PER\"");
        $response->assertSee('Gloss name');
        $response->assertSee('AN.PL');
        $response->assertSee("\"url\":\"\\/glosses\\/AN\"");
        $response->assertSee('The quick brown fox jumps over the lazy brown dog');
        $response->assertSee('Lorem ipsum dolor sit amet');
        $response->assertSee('Abcdefghijklmnopqrstuvwxyz');
    }
}
