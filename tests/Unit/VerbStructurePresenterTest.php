<?php

namespace Tests\Unit;

use App\Models\Feature;
use App\Models\VerbStructure;
use Tests\TestCase;

class VerbStructurePresenterTest extends TestCase
{
    /** @test */
    public function it_renders_its_subject_as_its_feature_string_when_there_are_no_other_features()
    {
        $structure = new VerbStructure(['subject_name' => '21']);
        $this->assertEquals('21', $structure->feature_string);
    }

    /** @test */
    public function it_renders_its_primary_object_with_an_arrow_in_its_feature_string()
    {
        $structure = new VerbStructure([
            'subject_name' => '3s',
            'primary_object_name' => '1p'
        ]);
        $this->assertEquals('3s→1p', $structure->feature_string);
    }

    /** @test */
    public function it_renders_its_secondary_object_with_a_plus_in_its_feature_string()
    {
        $structure = new VerbStructure([
            'subject_name' => '3s',
            'secondary_object_name' => '1p'
        ]);
        $this->assertEquals('3s+1p', $structure->feature_string);
    }

    /** @test */
    public function it_underlines_the_subject_if_the_head_is_subject()
    {
        $structure = new VerbStructure([
            'subject_name' => '3s',
            'primary_object_name' => '2d',
            'secondary_object_name' => '1p',
            'head' => 'subject'
        ]);
        $this->assertEquals('<u>3s</u>→2d+1p', $structure->feature_string);
    }

    /** @test */
    public function it_underlines_the_primary_object_if_the_head_is_primary_object()
    {
        $structure = new VerbStructure([
            'subject_name' => '3s',
            'primary_object_name' => '2d',
            'secondary_object_name' => '1p',
            'head' => 'primary object'
        ]);
        $this->assertEquals('3s→<u>2d</u>+1p', $structure->feature_string);
    }

    /** @test */
    public function it_underlines_the_secondary_object_if_the_head_is_secondary_object()
    {
        $structure = new VerbStructure([
            'subject_name' => '3s',
            'primary_object_name' => '2d',
            'secondary_object_name' => '1p',
            'head' => 'secondary object'
        ]);
        $this->assertEquals('3s→2d+<u>1p</u>', $structure->feature_string);
    }
}
