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
                'id' => 1,
                'abv' => 'STM'
            ],
            [
                'id' => 2,
                'abv' => 'CCEN'
            ],
            [
                'id' => 3,
                'abv' => 'PER'
            ]
        ]);

        DB::table('morphemes')->insert([
            [
                'id' => 1,
                'shape' => 'V-',
                'slug' => 'V',
                'language_id' => 1,
                'slot_id' => 1,
                'gloss' => 'V'
            ],
            [
                'id' => 2,
                'shape' => '-a·n',
                'slug' => 'a0',
                'language_id' => 1,
                'slot_id' => 2,
                'gloss' => '1s'
            ],
            [
                'id' => 3,
                'shape' => '-ari',
                'slug' => 'ari',
                'language_id' => 1,
                'slot_id' => 3,
                'gloss' => 'AN.OBV.SG'
            ],
            [
                'id' => 4,
                'shape' => 'wa·pam-',
                'slug' => 'wa0pam',
                'language_id' => 1,
                'slot_id' => 1,
                'gloss' => 'see'
            ]
        ]);
    }
}
