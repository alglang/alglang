<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SmartSearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_redirects_to_a_language_from_its_code()
    {
        $language = factory(Language::class)->create(['code' => 'PA']);

        $response = $this->get(route('smart-search', ['q' => 'PA']));

        $response->assertRedirect($language->url);
    }

    /** @test */
    public function it_checks_the_language_code_case_insensitively()
    {
        $language = factory(Language::class)->create(['code' => 'PA']);

        $response = $this->get(route('smart-search', ['q' => 'pa']));

        $response->assertRedirect($language->url);
    }

    /** @test */
    public function it_redirects_to_a_language_from_its_name()
    {
        $language = factory(Language::class)->create(['name' => 'Proto-Algonquian']);

        $response = $this->get(route('smart-search', ['q' => 'Proto-Algonquian']));

        $response->assertRedirect($language->url);
    }

    /** @test */
    public function it_checks_the_language_name_case_insensitively()
    {
        $language = factory(Language::class)->create(['name' => 'Proto-Algonquian']);

        $response = $this->get(route('smart-search', ['q' => 'PROTO-ALGONQUIAN']));

        $response->assertRedirect($language->url);
    }

    /** @test */
    public function it_redirects_to_a_language_from_its_alternate_name()
    {
        $language = factory(Language::class)->create([
            'alternate_names' => ['Algonq']
        ]);

        $response = $this->get(route('smart-search', ['q' => 'Algonq']));

        $response->assertRedirect($language->url);
    }

    /** @test */
    public function it_checks_the_language_alternate_names_case_insensitively()
    {
        $language = factory(Language::class)->create([
            'alternate_names' => ['Algonq']
        ]);

        $response = $this->get(route('smart-search', ['q' => 'AlGoNq']));

        $response->assertRedirect($language->url);
    }

    /** @test */
    public function it_redirects_to_a_group_from_its_name()
    {
        $group = factory(Group::class)->create(['name' => 'Algonquian']);

        $response = $this->get(route('smart-search', ['q' => 'Algonquian']));

        $response->assertRedirect($group->url);
    }

    /** @test */
    public function it_checks_the_group_name_case_insensitively()
    {
        $group = factory(Group::class)->create(['name' => 'Algonquian']);

        $response = $this->get(route('smart-search', ['q' => 'AlGOnqUIan']));

        $response->assertRedirect($group->url);
    }

    /** @test */
    public function it_bounces_back_with_an_error_message_if_it_isnt_successful()
    {
        $response = $this->get(route('smart-search', ['q' => 'foo']));

        $response->assertRedirect('/');
        $response->assertSessionHasErrors('q');
    }
}
