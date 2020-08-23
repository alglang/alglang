<?php

use App\Morpheme;
use App\NominalForm;
use App\NominalStructure;
use App\Source;
use Illuminate\Database\Seeder;

class NominalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('nominal_paradigm_types')->insert([
            [
                'name' => 'Possessed noun',
                'has_pronominal_feature' => true,
                'has_nominal_feature' => false
            ],
            [
                'name' => 'Common noun',
                'has_pronominal_feature' => false,
                'has_nominal_feature' => true
            ],
            [
                'name' => 'Demonstrative',
                'has_pronominal_feature' => false,
                'has_nominal_feature' => true
            ]
        ]);

        DB::table('nominal_paradigms')->insert([
            [
                'id' => 1,
                'name' => 'Possessed noun',
                'slug' => 'possessed-noun',
                'language_code' => 'PA',
                'paradigm_type_name' => 'Possessed noun'
            ],
            [
                'id' => 2,
                'name' => 'Common noun',
                'slug' => 'common-noun',
                'language_code' => 'PA',
                'paradigm_type_name' => 'Common noun'
            ],
            [
                'id' => 3,
                'name' => 'Proximal demonstrative',
                'slug' => 'proximal-demonstrative',
                'language_code' => 'PA',
                'paradigm_type_name' => 'Demonstrative'
            ]
        ]);

        DB::table('nominal_structures')->insert([
            [
                'id' => 1,
                'pronominal_feature_name' => '1s',
                'nominal_feature_name' => '3s',
                'paradigm_id' => 1  // Posessed noun
            ],
            [
                'id' => 2,
                'pronominal_feature_name' => '1s',
                'nominal_feature_name' => '3p',
                'paradigm_id' => 1  // Posessed noun
            ],
            [
                'id' => 3,
                'pronominal_feature_name' => null,
                'nominal_feature_name' => '3s',
                'paradigm_id' => 2  // Common noun
            ],
            [
                'id' => 4,
                'pronominal_feature_name' => null,
                'nominal_feature_name' => '3p',
                'paradigm_id' => 2  // Common noun
            ],
            [
                'id' => 5,
                'pronominal_feature_name' => null,
                'nominal_feature_name' => '3s',
                'paradigm_id' => 3  // Proximal demonstrative
            ]
        ]);

        DB::table('forms')->insert([
            [
                'id' => 3,
                'shape' => 'ne-N-a',
                'language_code' => 'PA',
                'structure_type' => NominalStructure::class,
                'structure_id' => 1,
                'slug' => 'ne-N-a'
            ],
            [
                'id' => 4,
                'shape' => 'ne-N-aki',
                'language_code' => 'PA',
                'structure_type' => NominalStructure::class,
                'structure_id' => 2,
                'slug' => 'ne-N-aki'
            ],
            [
                'id' => 9,
                'shape' => 'N-a',
                'language_code' => 'PA',
                'structure_type' => NominalStructure::class,
                'structure_id' => 3,
                'slug' => 'N-a'
            ],
            [
                'id' => 10,
                'shape' => 'N-aki',
                'language_code' => 'PA',
                'structure_type' => NominalStructure::class,
                'structure_id' => 4,
                'slug' => 'N-aki'
            ],
            [
                'id' => 11,
                'shape' => 'ewa',
                'language_code' => 'PA',
                'structure_type' => NominalStructure::class,
                'structure_id' => 5,
                'slug' => 'ewa'
            ]
        ]);

        NominalForm::find(3)->assignMorphemes([
            Morpheme::find(11),
            Morpheme::find(2),
            Morpheme::find(12)
        ]);
        NominalForm::find(4)->assignMorphemes([
            Morpheme::find(11),
            Morpheme::find(2),
            Morpheme::find(13)
        ]);

        NominalForm::find(3)->addSource(Source::find(7));  // Pentland 1999
    }
}
