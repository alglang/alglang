<?php

namespace Tests\Unit;

use App\Models\Slot;
use Tests\TestCase;

class SlotTest extends TestCase
{
    /** @test */
    public function it_has_a_url_property()
    {
        $slot = new Slot(['abv' => 'FOO']);
        $this->assertEquals('/slots/FOO', $slot->url);
    }
}
