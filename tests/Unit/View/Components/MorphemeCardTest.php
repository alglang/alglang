<?php

namespace Tests\Unit\View\Components;

use App\Models\Gloss;
use App\Models\Morpheme;
use App\Models\Slot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MorphemeCardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_the_formatted_shape()
    {
        $morpheme = Morpheme::factory()->create();

        $view = $this->blade('<x-morpheme-card :morpheme="$morpheme" />', compact('morpheme'));

        $view->assertSee($morpheme->formatted_shape, false);
    }

    /** @test */
    public function it_displays_the_slot()
    {
        $slot = Slot::factory()->create([
            'abv' => 'FOO',
            'colour' => 'rgb(255, 0, 0)'
        ]);
        $morpheme = Morpheme::factory()->create(['slot_abv' => $slot]);

        $view = $this->blade('<x-morpheme-card :morpheme="$morpheme" />', compact('morpheme'));

        $view->assertSee('FOO');
        $view->assertSee('rgb(255, 0, 0)');
    }

    /** @test */
    public function it_displays_the_glosses()
    {
        $gloss1 = Gloss::factory()->create(['abv' => 'G1']);
        $gloss2 = Gloss::factory()->create(['abv' => 'G2']);
        $morpheme = Morpheme::factory()->create(['gloss' => 'G1.G2']);

        $view = $this->blade('<x-morpheme-card :morpheme="$morpheme" />', compact('morpheme'));

        $view->assertSee('G1');
        $view->assertSee('G2');
    }
}
