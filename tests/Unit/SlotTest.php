<?php

namespace Tests\Unit;

use App\Models\Slot;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SlotTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_url_property()
    {
        $slot = factory(Slot::class)->create(['abv' => 'FOO']);
        $this->assertEquals('/slots/FOO', $slot->url);
    }
}
