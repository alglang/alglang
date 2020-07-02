<?php

use Illuminate\Database\Seeder;

class MorphemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('glosses')->insert([
            [
                'abv' => 'V',
                'name' => 'verb stem'
            ],
            [
                'abv' => '1s',
                'name' => 'first person singular'
            ],
            [
                'abv' => 'AN',
                'name' => 'animate'
            ],
            [
                'abv' => 'OBV',
                'name' => 'obviative'
            ],
            [
                'abv' => 'SG',
                'name' => 'singular'
            ]
        ]);

        DB::table('slots')->insert([
            [
                'abv' => 'STM',
                'name' => 'stem'
            ],
            [
                'abv' => 'CCEN',
                'name' => 'conjunct central suffix'
            ],
            [
                'abv' => 'PER',
                'name' => 'peripheral suffix'
            ]
        ]);

        DB::table('morphemes')->insert([
            [
                'id' => 1,
                'shape' => 'V-',
                'slug' => 'V',
                'language_id' => 1,
                'slot_abv' => 'STM',
                'gloss' => 'V'
            ],
            [
                'id' => 2,
                'shape' => '-a·n',
                'slug' => 'a0',
                'language_id' => 1,
                'slot_abv' => 'CCEN',
                'gloss' => '1s'
            ],
            [
                'id' => 3,
                'shape' => '-ari',
                'slug' => 'ari',
                'language_id' => 1,
                'slot_abv' => 'PER',
                'gloss' => 'AN.OBV.SG'
            ],
            [
                'id' => 4,
                'shape' => 'wa·pam-',
                'slug' => 'wa0pam',
                'language_id' => 1,
                'slot_abv' => 'STM',
                'gloss' => 'see'
            ]
        ]);
    }
}
