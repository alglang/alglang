<?php

namespace Tests\Unit;

use App\Models\NominalParadigmType;
use Tests\TestCase;

class NominalParadigmTypeTest extends TestCase
{
    /** @test */
    public function its_metatype_is_third_person_if_it_has_no_pronominal_feature()
    {
        $type = new NominalParadigmType([
            'has_pronominal_feature' => false,
            'has_nominal_feature' => true
        ]);

        $this->assertEquals('Third person form', $type->meta_type);
    }

    /** @test */
    public function its_metatype_is_personal_pronoun_if_it_has_no_nominal_feature()
    {
        $type = new NominalParadigmType([
            'has_pronominal_feature' => true,
            'has_nominal_feature' => false
        ]);

        $this->assertEquals('Personal pronoun', $type->meta_type);
    }

    /** @test */
    public function its_metatype_is_possessed_noun_if_it_has_pronominal_and_nominal_features()
    {
        $type = new NominalParadigmType([
            'has_pronominal_feature' => true,
            'has_nominal_feature' => true
        ]);

        $this->assertEquals('Possessed noun', $type->meta_type);
    }

    /** @test */
    public function it_has_no_metatype_if_it_has_no_features()
    {
        $type = new NominalParadigmType([
            'has_pronominal_feature' => false,
            'has_nominal_feature' => false
        ]);

        $this->expectException(\UnexpectedValueException::class);

        $type->metatype;
    }
}
