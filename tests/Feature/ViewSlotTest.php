<?php

namespace Tests\Feature;

use App\Slot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewSlotTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function slots_can_be_viewed()
    {
        $slot = factory(Slot::class)->create([
            'abv' => 'SLT',
            'name' => 'the slot',
            'colour' => '#ff0000',
        ]);

        $response = $this->get($slot->url);

        $response->assertOk();
        $response->assertSee('SLT');
        $response->assertSee('#ff0000');
        $response->assertSee('the slot');
    }

    /** @test */
    public function slot_displays_a_description_if_a_description_exists()
    {
        $slot = factory(Slot::class)->create([
            'description' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
        ]);

        $response = $this->get($slot->url);

        $response->assertOk();
        $response->assertSee('Description');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function slot_does_not_display_a_description_if_no_description_exists()
    {
        $slot = factory(Slot::class)->create(['description' => null]);

        $response = $this->get($slot->url);

        $response->assertOk();
        $response->assertDontSee('Description');
    }
}
