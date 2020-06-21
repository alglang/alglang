<?php

namespace Tests\Unit;

use App\Group;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_url_property()
    {
        $group = factory(Group::class)->create();
        $this->assertEquals('/groups/1', $group->url);
    }
}
