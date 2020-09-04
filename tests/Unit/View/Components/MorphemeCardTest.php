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
        $morpheme = factory(Morpheme::class)->create();

        $view = $this->blade('<x-morpheme-card :morpheme="$morpheme" />', compact('morpheme'));

        $view->assertSee($morpheme->formatted_shape, false);
    }

    /** @test */
    public function it_displays_the_slot()
    {
        $slot = factory(Slot::class)->create([
            'abv' => 'FOO',
            'colour' => 'rgb(255, 0, 0)'
        ]);
        $morpheme = factory(Morpheme::class)->create(['slot_abv' => $slot]);

        $view = $this->blade('<x-morpheme-card :morpheme="$morpheme" />', compact('morpheme'));

        $view->assertSee('FOO');
        $view->assertSee('rgb(255, 0, 0)');
    }

    /** @test */
    public function it_displays_the_glosses()
    {
        $gloss1 = factory(Gloss::class)->create(['abv' => 'G1']);
        $gloss2 = factory(Gloss::class)->create(['abv' => 'G2']);
        $morpheme = factory(Morpheme::class)->create(['gloss' => 'G1.G2']);

        $view = $this->blade('<x-morpheme-card :morpheme="$morpheme" />', compact('morpheme'));

        $view->assertSee('G1');
        $view->assertSee('G2');
    }
}
