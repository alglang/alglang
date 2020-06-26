<?php

namespace Tests\Unit;

use App\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LanguageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_url_property()
    {
        $language = factory(Language::class)->create(['algo_code' => 'PA']);
        $this->assertEquals('/languages/pa', $language->url);
    }

    /** @test */
    public function it_converts_to_map_data()
    {
        $language = factory(Language::class)->create([
            'name' => 'Test Language',
            'slug' => 'tl',
            'position' => '{"lat":5.2,"lng":6.1}'
        ]);

        $this->assertEquals([
            'content' => '<a href="/languages/tl">Test Language</a>',
            'position' => (object) [
                'lat' => 5.2,
                'lng' => 6.1
            ]
        ], $language->map_data);
    }
}
