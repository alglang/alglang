<?php

namespace Tests\Browser;

use App\Models\Phoneme;
use App\Models\Reflex;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ViewReflexesTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function the_root_phoneme_is_in_the_root_reflex_graph()
    {
        $phoneme = Phoneme::factory()->create(['shape' => 'root']);

        $this->browse(function (Browser $browser) use ($phoneme) {
            $browser->visit($phoneme->url)
                    ->assertSeeLink('Reflexes')
                    ->clickLink('Reflexes')
                    ->assertPresent('.reflex-graph--root .reflex-graph__phoneme');

            $this->assertEquals('root', $browser->text('.reflex-graph--root .reflex-graph__phoneme'));
        });
    }

    /** @test */
    public function child_reflexes_are_shown_in_the_child_list()
    {
        $phoneme = Phoneme::factory()->create();
        Reflex::factory()->create([
            'phoneme_id' => $phoneme,
            'reflex_id' => Phoneme::factory()->create(['shape' => 'the_reflex']),
            'environment' => 'whenever'
        ]);

        $this->browse(function (Browser $browser) use ($phoneme) {
            $browser->visit($phoneme->url)
                    ->assertSeeLink('Reflexes')
                    ->clickLink('Reflexes')
                    ->with('.reflex-graph--root .reflex-graph__children .reflex-graph', function ($graph) {
                        $graph->assertPresent('.reflex-graph__phoneme')
                              ->assertPresent('.reflex-graph__environment');

                        $this->assertEquals('the_reflex', $graph->text('.reflex-graph__phoneme'));
                        $this->assertEquals('whenever', $graph->text('.reflex-graph__environment'));
                    });
        });
    }

    /** @test */
    public function parent_reflexes_are_shown_in_the_parent_list()
    {
        $phoneme = Phoneme::factory()->create();
        Reflex::factory()->create([
            'phoneme_id' => Phoneme::factory()->create(['shape' => 'the_parent']),
            'reflex_id' => $phoneme,
            'environment' => 'whenever'
        ]);

        $this->browse(function (Browser $browser) use ($phoneme) {
            $browser->visit($phoneme->url)
                    ->assertSeeLink('Reflexes')
                    ->clickLink('Reflexes')
                    ->with('.reflex-graph--root .reflex-graph__parents .reflex-graph', function ($graph) {
                        $graph->assertPresent('.reflex-graph__phoneme')
                              ->assertPresent('.reflex-graph__environment');

                        $this->assertEquals('the_parent', $graph->text('.reflex-graph__phoneme'));
                        $this->assertEquals('whenever', $graph->text('.reflex-graph__environment'));
                    });
        });
    }
}
