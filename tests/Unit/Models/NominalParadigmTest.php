<?php

namespace Tests\Unit\Models;

use App\Models\Language;
use App\Models\NominalParadigm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NominalParadigmTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_url()
    {
        $paradigm = NominalParadigm::factory()->create([
            'language_code' => Language::factory()->create(['code' => 'tl']),
            'name' => 'Test Paradigm'
        ]);

        $this->assertEquals(
            '/languages/tl/nominal-paradigms/test-paradigm',
            $paradigm->url
        );
    }
}
