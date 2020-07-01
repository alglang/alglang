<?php

namespace Tests\Feature;

use App\Language;
use App\Morpheme;
use App\Slot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchMorphemesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_retrieves_morphemes_from_the_database()
    {
        $slot = factory(Slot::class)->create();
        $language = factory(Language::class)->create(['slug' => 'foo']);

        $morpheme = factory(Morpheme::class)->create([
            'language_id' => $language->id,
            'shape' => '-ak',
            'slot_id' => $slot->id,
            'historical_notes' => 'The quick brown fox jumps over the lazy brown dog',
            'allomorphy_notes' => 'Lorem ipsum dolor sit amet',
            'private_notes' => 'Abcdefghijklmnopqrstuvwxyz'
        ]);

        $response = $this->get('/languages/foo/morphemes');

        $response->assertOk();
        $response->assertJson([
            'data' => [
                [
                    'shape' => '-ak',
                    'url' => $morpheme->url,
                    'slot_id' => $slot->id,
                    'language_id' => $language->id,
                    'historical_notes' => 'The quick brown fox jumps over the lazy brown dog',
                    'allomorphy_notes' => 'Lorem ipsum dolor sit amet',
                    'private_notes' => 'Abcdefghijklmnopqrstuvwxyz'
                ]
            ]
        ]);
    }
}
