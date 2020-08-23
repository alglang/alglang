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
            ['name' => 'Possessed noun']
        ]);

        DB::table('nominal_paradigms')->insert([
            [
                'id' => 1,
                'name' => 'Possessed noun',
                'slug' => 'possessed-noun',
                'language_code' => 'PA',
                'paradigm_type_name' => 'Possessed noun'
            ]
        ]);

        DB::table('nominal_structures')->insert([
            [
                'pronominal_feature_name' => '1s',
                'nominal_feature_name' => '3s',
                'paradigm_id' => 1  // Posessed noun
            ],
            [
                'pronominal_feature_name' => '1s',
                'nominal_feature_name' => '3p',
                'paradigm_id' => 1  // Posessed noun
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
