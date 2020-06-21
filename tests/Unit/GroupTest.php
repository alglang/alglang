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
        $group = factory(Group::class)->create(['name' => 'Test Group']);
        $this->assertEquals('/groups/test-group', $group->url);
    }
}
