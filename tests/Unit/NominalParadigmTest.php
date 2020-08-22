<?php

namespace Tests\Unit;

use App\Language;
use App\NominalParadigm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NominalParadigmTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_url()
    {
        $paradigm = factory(NominalParadigm::class)->create([
            'language_id' => factory(Language::class)->create([
                'algo_code' => 'tl'
            ])->id,
            'name' => 'Test Paradigm'
        ]);

        $this->assertEquals(
            '/languages/tl/nominal-paradigms/test-paradigm',
            $paradigm->url
        );
    }
}
