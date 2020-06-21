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
}
