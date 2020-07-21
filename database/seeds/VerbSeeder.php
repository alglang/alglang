<?php

use Illuminate\Database\Seeder;

class VerbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('verb_orders')->insert([
            'id' => 1,
            'name' => 'Conjunct'
        ]);

        DB::table('verb_classes')->insert([
            'id' => 1,
            'abv' => 'TA'
        ]);

        DB::table('verb_modes')->insert([
            'id' => 1,
            'name' => 'Indicative'
        ]);

        DB::table('verb_features')->insert([
            'id' => 1,
            'name' => '3p'
        ]);

        DB::table('verb_forms')->insert([
            [
                'id' => 1,
                'shape' => 'V-(o)wa·či',
                'language_id' => 1,  // Proto-Algonquian
                'class_id' => 1,  // TA
                'mode_id' => 1,  // Indicative
                'order_id' => 1,  // Conjunct
                'subject_id' => 1,
                'slug' => 'V-(o)wa·či',
                'allomorphy_notes' => 'Insert /o/ after a consonant'
            ]
        ]);
    }
}
