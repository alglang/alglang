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

    /** @test */
    public function its_preview_is_its_description()
    {
        $group = factory(Group::class)->create([
            'description' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
        ]);

        $this->assertEquals(
            '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>',
            $group->preview
        );
    }

    /** @test */
    public function groups_are_ordered_by_order_key_by_default()
    {
        $group1 = factory(Group::class)->create(['order_key' => 2]);
        $group2 = factory(Group::class)->create(['order_key' => 1]);

        $groups = Group::all();
        $this->assertEquals([$group2->name, $group1->name], $groups->pluck('name')->toArray());
    }
}
