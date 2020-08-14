<?php

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
        DB::table('nominal_features')->insert([
            [
                'id' => 1,
                'name' => '1s'
            ],
            [
                'id' => 2,
                'name' => '3s'
            ]
        ]);

        DB::table('nominal_paradigms')->insert([
            [
                'id' => 1,
                'name' => 'Posessed noun'
            ]
        ]);

        DB::table('nominal_structures')->insert([
            [
                'pronominal_feature_id' => 1,  // 1s
                'nominal_feature_id' => 2,     // 3s
                'paradigm_id' => 1             // Posessed noun
            ]
        ]);

        DB::table('forms')->insert([
            [
                'id' => 3,
                'shape' => 'ne-N-a',
                'morpheme_structure' => '11-2-12',
                'language_id' => 1,  // Proto-Algonquian
                'structure_type' => NominalStructure::class,
                'structure_id' => 1,
                'slug' => 'ne-N-a'
            ]
        ]);

        NominalForm::find(3)->addSource(Source::find(7));  // Pentland 1999
    }
}
