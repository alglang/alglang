<?php

namespace Tests\Feature;

use App\Models\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchLanguagesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_retrieves_languages_from_the_database()
    {
        $language = factory(Language::class)->create([
            'name' => 'Test Language',
            'code' => 'TL',
            'notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam',
            'position' => ['lat' => 53, 'lng' => 34]
        ]);

        $response = $this->get('/api/languages');

        $response->assertOk();
        $response->assertJson([
            'data' => [
                [
                    'name' => 'Test Language',
                    'url' => $language->url,
                    'code' => 'TL',
                    'notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam',
                    'position' => ['lat' => 53, 'lng' => 34]
                ]
            ]
        ]);
    }
}
