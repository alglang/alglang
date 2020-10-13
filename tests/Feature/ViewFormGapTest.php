<?php

namespace Tests\Feature;

use App\Models\FormGap;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewFormGapTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_loads_the_correct_view_for_a_verb_gap()
    {
        $gap = FormGap::factory()->verb()->create();

        $response = $this->get($gap->url);

        $response->assertOk();
        $response->assertViewIs('gaps.show');
        $response->assertViewHas('gap', $gap);
    }

    /** @test */
    public function it_loads_the_correct_view_for_a_nominal_gap()
    {
        $gap = FormGap::factory()->nominal()->create();

        $response = $this->get($gap->url);

        $response->assertOk();
        $response->assertViewIs('gaps.show');
        $response->assertViewHas('gap', $gap);
    }
}
