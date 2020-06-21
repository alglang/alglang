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
}
