<?php

namespace Tests\Feature;

use App\Group;
use App\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewLanguageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_language_can_be_viewed()
    {
        $group = factory(Group::class)->create(['name' => 'Test Group']);
        $language = factory(Language::class)->create([
            'name' => 'Test Language',
            'algo_code' => 'PA',
            'group_id' => $group->id
        ]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertSee('Test Language');
        $response->assertSee('PA');
        $response->assertSee('Test Group');
    }

    /** @test */
    public function a_map_is_displayed_of_the_language()
    {
        $language = factory(Language::class)->create([
            'name' => 'Test Language',
            'position' => '{"lat":57.5,"lng":74.3}'
        ]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertSee('{"lat":57.5,"lng":74.3}');
    }
}
