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
                'slug' => 'V-1',
                'language_id' => 1,
                'slot_abv' => 'STM',
                'gloss' => 'V',
                'allomorphy_notes' => null,
                'historical_notes' => null,
                'private_notes' => '<p>This is a test note.</p>'
            ],
            [
                'id' => 2,
                'shape' => '-a·n',
                'slug' => 'a0n-1',
                'language_id' => 1,
                'slot_abv' => 'CCEN',
                'gloss' => '1s',
                'allomorphy_notes' => '<p>Becomes -ya:n after a vowel.</p>',
                'historical_notes' => null,
                'private_notes' => null
            ],
            [
                'id' => 3,
                'shape' => '-ari',
                'slug' => 'ari-1',
                'language_id' => 1,
                'slot_abv' => 'PER',
                'gloss' => 'AN.OBV.SG',
                'allomorphy_notes' => null,
                'historical_notes' => '<p>This is a test historical note.</p>',
                'private_notes' => null
            ],
            [
                'id' => 4,
                'shape' => 'wa·pam-',
                'slug' => 'wa0pam-1',
                'language_id' => 1,
                'slot_abv' => 'STM',
                'gloss' => 'see',
                'allomorphy_notes' => null,
                'historical_notes' => null,
                'private_notes' => null
            ]
        ]);
    }
}
