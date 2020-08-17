<?php

namespace Tests\Unit;

use App\Feature;
use App\VerbStructure;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VerbStructureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_renders_its_subject_as_its_argument_string_when_there_are_no_other_features()
    {
        $structure = factory(VerbStructure::class)->create([
            'subject_id' => factory(Feature::class)->create(['name' => '21'])->id
        ]);

        $this->assertEquals('21', $structure->argument_string);
    }

    /** @test */
    public function it_renders_its_primary_object_with_an_arrow_in_its_argument_string()
    {
        $structure = factory(VerbStructure::class)->create([
            'subject_id' => factory(Feature::class)->create(['name' => '3s'])->id,
            'primary_object_id' => factory(Feature::class)->create(['name' => '1p'])->id
        ]);

        $this->assertEquals('3s→1p', $structure->argument_string);
    }

    /** @test */
    public function it_renders_its_secondary_object_with_a_plus_in_its_argument_string()
    {
        $structure = factory(VerbStructure::class)->create([
            'subject_id' => factory(Feature::class)->create(['name' => '3s'])->id,
            'secondary_object_id' => factory(Feature::class)->create(['name' => '1p'])->id
        ]);

        $this->assertEquals('3s+1p', $structure->argument_string);
    }
}
