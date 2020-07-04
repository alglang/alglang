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
        $response->assertSee('-ak');                          // Shape
        $response->assertSee("\"disambiguator\":0");          // Disambiguator
        $response->assertSee('Test Language');                // Language name
        $response->assertSee('PER');                          // Slot abv
        $response->assertSee("\"url\":\"\\/slots\\/PER\"");   // Slot url
        $response->assertSee('AN.PL');                        // Gloss abbreviations
        $response->assertSee('Gloss name');                   // Gloss name
        $response->assertSee("\"url\":\"\\/glosses\\/AN\"");  // Gloss urls
        $response->assertSee('The quick brown fox jumps over the lazy brown dog');  // Historical notes
        $response->assertSee('Lorem ipsum dolor sit amet');  // Allomorphy notes
        $response->assertSee('Abcdefghijklmnopqrstuvwxyz');  // Private notes
    }
}
