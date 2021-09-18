<?php

namespace Tests\Feature;

use App\Models\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_page_loads(): void
    {
        $response = $this->get('/search');

        $response->assertOk();
    }

    /** @test */
    public function all_languages_are_shown(): void
    {
        $languages = Language::factory()->create(['name' => 'Test Language 1']);
        $languages = Language::factory()->create(['name' => 'Test Language 2']);

        $response = $this->get('/search');

        $response->assertOk();
        $response->assertSee('Test Language 1');
        $response->assertSee('Test Language 2');
    }
}
