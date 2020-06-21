<?php

namespace Tests\Feature;

use App\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewGroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_group_can_be_viewed()
    {
        $group = factory(Group::class)->create([
            'name' => 'Test Group',
            'description' => 'Lorem ipsum dolor sit amet'
        ]);

        $response = $this->get($group->url);

        $response->assertOk();
        $response->assertSee('Test Group');
        $response->assertSee('Lorem ipsum dolor sit amet');
    }
}
