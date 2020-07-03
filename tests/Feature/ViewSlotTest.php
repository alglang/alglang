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
        $this->withoutMix();

        $slot = factory(Slot::class)->create([
            'abv' => 'SLOT',
            'name' => 'the slot',
            'colour' => '#ff0000',
            'description' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
        ]);

        $response = $this->get($slot->url);

        $response->assertOk();
        $response->assertSee('SLOT');
        $response->assertSee('the slot');
        $response->assertSee('#ff0000');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }
}
