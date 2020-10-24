<?php

namespace Tests\Unit\Presenters;

use App\Models\NominalParadigm;
use App\Models\NominalStructure;
use PHPUnit\Framework\TestCase;

class NominalStructurePresenterTest extends TestCase
{
    /** @test */
    public function its_name_is_its_feature_string_followed_by_its_paradigm_name()
    {
        $structure = new NominalStructure([
            'pronominal_feature_name' => '3s',
            'nominal_feature_name' => '2p',
            'paradigm' => new NominalParadigm(['name' => 'test paradigm'])
        ]);

        $this->assertEquals('3s→2p test paradigm', $structure->name);
    }

    /** @test */
    public function it_renders_its_pronominal_feature_as_its_feature_string_when_it_has_no_nominal_feature()
    {
        $structure = new NominalStructure([
            'pronominal_feature_name' => '21',
            'nominal_feature_name' => null
        ]);

        $this->assertEquals('21', $structure->feature_string);
    }

    /** @test */
    public function it_renders_its_nominal_feature_as_its_feature_string_when_it_has_no_pronominal_feature()
    {
        $structure = new NominalStructure([
            'pronominal_feature_name' => null,
            'nominal_feature_name' => '3s'
        ]);

        $this->assertEquals('3s', $structure->feature_string);
    }

    /** @test */
    public function it_renders_its_features_with_an_arrow_in_between_if_it_has_both()
    {
        $structure = new NominalStructure([
            'pronominal_feature_name' => '21',
            'nominal_feature_name' => '3s'
        ]);

        $this->assertEquals('21→3s', $structure->feature_string);
    }
}
