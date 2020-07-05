<?php

namespace Tests\Feature;

use App\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchGroupsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_retrieves_groups_from_the_database()
    {
        $this->withoutExceptionHandling();

        $group = factory(Group::class)->create([
            'name' => 'Test Group',
            'description' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
        ]);

        $response = $this->get('/groups');

        $response->assertOk();
        $response->assertJson([
            'data' => [
                [
                    'name' => 'Test Group',
                    'url' => $group->url,
                    'description' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
                ]
            ]
        ]);
    }
}
