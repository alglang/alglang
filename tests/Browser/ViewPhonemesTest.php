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
    use DatabaseMigrations;

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
                    ->with('.vowel-inventory', function ($table) {
                        $table->assertPresent('[data-backness="front"][data-height="high"]');
                        $this->assertEquals('i', $table->text('[data-backness="front"][data-height="high"]'));
                    });
        });
    }

    /** @test */
    public function archiphoneme_consonants_are_shown()
    {
        $language = Language::factory()->create();

        $phoneme = Phoneme::factory()->vowel([
            'backness_name' => null
        ])->create([
            'language_code' => $language,
            'shape' => 'ARCHY',
            'is_archiphoneme' => true
        ]);

        $this->browse(function (Browser $browser) use ($language) {
            $browser->visit($language->url)
                    ->clickLink('Phonemes')
                    ->with('.vowel-inventory', function ($table) {
                        $table->assertDontSee('ARCHY');
                    });
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
                    ->with('.consonant-inventory', function ($table) {
                        $table->assertPresent('[data-place="velar"][data-manner="fricative"]');
                        $this->assertEquals('x', $table->text('[data-place="velar"][data-manner="fricative"]'));
                    });
        });
    }

    /** @test */
    public function archiphonemes_are_not_shown_in_the_consonant_inventory()
    {
        $language = Language::factory()->create();

        $phoneme = Phoneme::factory()->consonant([
            'place_name' => null
        ])->create([
            'language_code' => $language,
            'shape' => 'ARCH',
            'is_archiphoneme' => true
        ]);

        $this->browse(function (Browser $browser) use ($language) {
            $browser->visit($language->url)
                    ->clickLink('Phonemes')
                    ->with('.consonant-inventory', function ($table) {
                        $table->assertDontSee('ARCH');
                    });
        });
    }
}