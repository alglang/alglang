<?php

namespace Tests\Browser;

use App\Models\ConsonantManner;
use App\Models\ConsonantPlace;
use App\Models\Language;
use App\Models\Phoneme;
use App\Models\VowelBackness;
use App\Models\VowelHeight;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ViewPhonemesTest extends DuskTestCase
{
    /** @test */
    public function the_vowel_inventory_is_shown()
    {
        $language = Language::factory()->create();

        $phoneme = Phoneme::factory()->vowel([
            'backness_name' => VowelBackness::factory(['name' => 'front']),
            'height_name' => VowelHeight::factory(['name' => 'high'])
        ])->create([
            'language_code' => $language,
            'shape' => 'i'
        ]);

        $this->browse(function (Browser $browser) use ($language) {
            $browser->visit($language->url)
                    ->clickLink('Phonemes')
                    ->assertPresent('[data-backness="front"][data-height="high"]');

            $this->assertEquals('i', $browser->text('[data-backness="front"][data-height="high"]'));
        });
    }

    /** @test */
    public function the_consonant_inventory_is_shown()
    {
        $language = Language::factory()->create();

        $phoneme = Phoneme::factory()->consonant([
            'place_name' => ConsonantPlace::factory(['name' => 'velar']),
            'manner_name' => ConsonantManner::factory(['name' => 'fricative']),
        ])->create([
            'language_code' => $language,
            'shape' => 'x'
        ]);

        $this->browse(function (Browser $browser) use ($language) {
            $browser->visit($language->url)
                    ->clickLink('Phonemes')
                    ->assertPresent('[data-place="velar"][data-manner="fricative"]');

            $this->assertEquals('x', $browser->text('[data-place="velar"][data-manner="fricative"]'));
        });
    }
}
