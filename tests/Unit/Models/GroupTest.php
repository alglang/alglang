<?php

namespace Tests\Unit\Models;

use App\Models\Group;
use App\Models\Language;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_url_property()
    {
        $group = new Group(['slug' => 'test-group']);
        $this->assertEquals('/groups/test-group', $group->url);
    }

    /** @test */
    public function its_preview_is_its_description()
    {
        $group = new Group([
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
        $group1 = Group::factory()->create(['order_key' => 2]);
        $group2 = Group::factory()->create(['order_key' => 1]);

        $groups = Group::all();
        $this->assertEquals([$group2->name, $group1->name], $groups->pluck('name')->toArray());
    }

    /** @test */
    public function it_has_languages_with_descendants()
    {
        $group = Group::factory()->create();
        $groupChild = Group::factory()->create(['parent_name' => $group->name]);

        Language::factory()->create([
            'code' => 'tl1',
            'group_name' => $group->name
        ]);
        Language::factory()->create([
            'code' => 'tl2',
            'parent_code' => 'tl1',
            'group_name' => $groupChild->name
        ]);

        $languagesWithDescendants = $group->languagesWithDescendants();

        $this->assertEquals(['tl1', 'tl2'], $languagesWithDescendants->pluck('code')->toArray());
    }
}
