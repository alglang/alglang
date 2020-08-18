<?php

namespace Tests\Unit;

use App\Feature;
use App\NominalStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NominalStructureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_renders_its_pronominal_feature_as_its_feature_string_when_it_has_no_nominal_feature()
    {
        $structure = factory(NominalStructure::class)->create([
            'pronominal_feature_id' => factory(Feature::class)->create(['name' => '21'])->id,
            'nominal_feature_id' => null
        ]);

        $this->assertEquals('21', $structure->feature_string);
    }

    /** @test */
    public function it_renders_its_nominal_feature_as_its_feature_string_when_it_has_no_pronominal_feature()
    {
        $structure = factory(NominalStructure::class)->create([
            'pronominal_feature_id' => null,
            'nominal_feature_id' => factory(Feature::class)->create(['name' => '3s'])->id
        ]);

        $this->assertEquals('3s', $structure->feature_string);
    }

    /** @test */
    public function it_renders_its_features_with_an_arrow_in_between_if_it_has_both()
    {
        $structure = factory(NominalStructure::class)->create([
            'pronominal_feature_id' => factory(Feature::class)->create(['name' => '21'])->id,
            'nominal_feature_id' => factory(Feature::class)->create(['name' => '3s'])->id
        ]);

        $this->assertEquals('21→3s', $structure->feature_string);
    }
}
