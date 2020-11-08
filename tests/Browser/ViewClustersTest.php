<?php

namespace Tests\Browser;

use App\Models\Language;
use App\Models\Phoneme;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ViewClustersTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function the_consonant_inventory_is_shown()
    {
        $language = Language::factory()->create();

        $cluster = Phoneme::factory()->cluster([
            'first_segment_id' => Phoneme::factory(['shape' => 'h']),
            'second_segment_id' => Phoneme::factory(['shape' => 'k'])
        ])->create([
            'language_code' => $language,
            'shape' => 'foo'
        ]);

        $this->browse(function (Browser $browser) use ($language) {
            $browser->visit($language->url)
                    ->clickLink('Clusters')
                    ->assertPresent('[data-first-segment="h"][data-second-segment="k"]');

            $this->assertEquals('foo', $browser->text('[data-first-segment="h"][data-second-segment="k"]'));
        });
    }
}
