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
    public function it_renders_its_subject_as_its_feature_string_when_there_are_no_other_features()
    {
        $structure = factory(VerbStructure::class)->create([
            'subject_name' => factory(Feature::class)->create(['name' => '21'])
        ]);

        $this->assertEquals('21', $structure->feature_string);
    }

    /** @test */
    public function it_renders_its_primary_object_with_an_arrow_in_its_feature_string()
    {
        $structure = factory(VerbStructure::class)->create([
            'subject_name' => factory(Feature::class)->create(['name' => '3s']),
            'primary_object_name' => factory(Feature::class)->create(['name' => '1p'])
        ]);

        $this->assertEquals('3s→1p', $structure->feature_string);
    }

    /** @test */
    public function it_renders_its_secondary_object_with_a_plus_in_its_feature_string()
    {
        $structure = factory(VerbStructure::class)->create([
            'subject_name' => factory(Feature::class)->create(['name' => '3s']),
            'secondary_object_name' => factory(Feature::class)->create(['name' => '1p'])
        ]);

        $this->assertEquals('3s+1p', $structure->feature_string);
    }

    /** @test */
    public function it_can_be_instantiated_from_a_search_query()
    {
        $structure = VerbStructure::fromSearchQuery([
            'subject_persons' => ['1'],
            'secondary_object' => false,
            'classes' => ['TA'],
            'orders' => ['Conjunct'],
            'modes' => ['Indicative']
        ]);

        $this->assertEquals('1', $structure->subject_name);
        $this->assertEquals('?', $structure->primary_object_name);
        $this->assertNull($structure->secondary_object_name);
        $this->assertEquals('TA', $structure->class_abv);
        $this->assertEquals('Conjunct', $structure->order_name);
        $this->assertEquals('Indicative', $structure->mode_name);
    }
}
