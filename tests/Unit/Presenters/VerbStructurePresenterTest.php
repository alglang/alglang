<?php

namespace Tests\Unit\Presenters;

use App\Models\Feature;
use App\Models\VerbStructure;
use Tests\TestCase;

class VerbStructurePresenterTest extends TestCase
{
    /** @test */
    public function its_name_is_its_features_class_order_mode()
    {
        $structure = new VerbStructure([
            'subject_name' => '3s',
            'primary_object_name' => '1p',
            'class_abv' => 'TA',
            'order_name' => 'Conjunct',
            'mode_name' => 'Indicative'
        ]);

        $this->assertEquals('3s→1p TA Conjunct Indicative', $structure->name);
    }

    /** @test */
    public function its_name_includes_negative_if_it_is_negative()
    {
        $structure = new VerbStructure([
            'subject_name' => 'A',
            'class_abv' => 'B',
            'order_name' => 'C',
            'mode_name' => 'D',
            'is_negative' => true
        ]);

        $this->assertEquals('A B C D (negative)', $structure->name);
    }

    /** @test */
    public function its_name_includes_diminutive_if_it_is_diminutive()
    {
        $structure = new VerbStructure([
            'subject_name' => 'A',
            'class_abv' => 'B',
            'order_name' => 'C',
            'mode_name' => 'D',
            'is_diminutive' => true
        ]);

        $this->assertEquals('A B C D (diminutive)', $structure->name);
    }

    /** @test */
    public function its_name_includes_negative_and_diminutive_if_it_is_both()
    {
        $structure = new VerbStructure([
            'subject_name' => 'A',
            'class_abv' => 'B',
            'order_name' => 'C',
            'mode_name' => 'D',
            'is_negative' => true,
            'is_diminutive' => true
        ]);

        $this->assertEquals('A B C D (negative, diminutive)', $structure->name);
    }

    /** @test */
    public function its_name_includes_absolute_if_is_absolute_is_true()
    {
        $structure = new VerbStructure([
            'subject_name' => 'A',
            'class_abv' => 'B',
            'order_name' => 'C',
            'mode_name' => 'D',
            'is_absolute' => true,
        ]);

        $this->assertEquals('A B C D (absolute)', $structure->name);
    }

    /** @test */
    public function its_name_includes_objective_if_is_absolute_is_false()
    {
        $structure = new VerbStructure([
            'subject_name' => 'A',
            'class_abv' => 'B',
            'order_name' => 'C',
            'mode_name' => 'D',
            'is_absolute' => false,
        ]);

        $this->assertEquals('A B C D (objective)', $structure->name);
    }

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
