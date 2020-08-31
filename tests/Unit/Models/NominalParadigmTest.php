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
        $paradigm = factory(NominalParadigm::class)->create([
            'language_code' => factory(Language::class)->create(['code' => 'tl']),
            'name' => 'Test Paradigm'
        ]);

        $this->assertEquals(
            '/languages/tl/nominal-paradigms/test-paradigm',
            $paradigm->url
        );
    }
}
