<?php

namespace Tests\Feature;

use App\Models\Feature;
use App\Models\FormGap;
use App\Models\NominalParadigm;
use App\Models\VerbClass;
use App\Models\VerbMode;
use App\Models\VerbOrder;
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

    /** @test */
    public function it_shows_a_verb_structure_name()
    {
        $gap = FormGap::factory()->verb([
            'subject_name' => Feature::factory()->create(['name' => '3s']),
            'class_abv' => VerbClass::factory()->create(['abv' => 'TC']),
            'order_name' => VerbOrder::factory()->create(['name' => 'Test_order']),
            'mode_name' => VerbMode::factory()->create(['name' => 'Test_mode']),
            'is_negative' => true,
            'is_diminutive' => true,
            'is_absolute' => false
        ])->create();

        $response = $this->get($gap->url);

        $response->assertOk();
        $response->assertSee('3s TC Test_order Test_mode (negative, diminutive, objective)');
    }

    /** @test */
    public function it_shows_a_nominal_structure_name()
    {
        $gap = FormGap::factory()->nominal([
            'pronominal_feature_name' => Feature::factory()->create(['name' => 'X']),
            'nominal_feature_name' => Feature::factory()->create(['name' => 'Y']),
            'paradigm_id' => NominalParadigm::factory()->create(['name' => 'Test_paradigm'])
        ])->create();

        $response = $this->get($gap->url);

        $response->assertOk();
        $response->assertSee('X→Y Test_paradigm');
    }
}
