<?php

use App\Example;
use App\Source;
use App\VerbForm;
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
            [
                'id' => 1,
                'name' => 'Conjunct'
            ],
            [
                'id' => 2,
                'name' => 'Independent'
            ]
        ]);

        DB::table('verb_classes')->insert([
            [
                'id' => 1,
                'abv' => 'TA'
            ],
            [
                'id' => 2,
                'abv' => 'AI'
            ]
        ]);

        DB::table('verb_modes')->insert([
            [
                'id' => 1,
                'name' => 'Indicative'
            ],
            [
                'id' => 2,
                'name' => 'Preterit'
            ]
        ]);

        DB::table('verb_features')->insert([
            [
                'id' => 1,
                'name' => '3p'
            ],
            [
                'id' => 2,
                'name' => '3s'
            ]
        ]);

        DB::table('verb_structures')->insert([
            [
                'id' => 1,
                'subject_id' => 1,  // 3p
                'class_id' => 1,    // TA
                'order_id' => 1,    // Conjunct
                'mode_id' => 1      // Indicative
            ],
            [
                'id' => 2,
                'subject_id' => 2,  // 3s
                'class_id' => 2,    // AI
                'order_id' => 2,    // Independent
                'mode_id' => 2      // Preterit
            ]
        ]);

        DB::table('forms')->insert([
            [
                'id' => 1,
                'shape' => 'V-(o)wa·či',
                'morpheme_structure' => '1-6-7-8',  // V-wa·-t-i
                'language_id' => 1,  // Proto-Algonquian
                'structure_id' => 1,  // 3p TA Conjunct Indicative
                'slug' => 'V-(o)wa·či',
                'allomorphy_notes' => 'Insert /o/ after a consonant'
            ],
            [
                'id' => 2,
                'shape' => 'V-pa',
                'morpheme_structure' => null,
                'language_id' => 1,  // Proto-Algonquian
                'structure_id' => 2,  // 3s AI Independent Preterit
                'slug' => 'V-pa',
                'allomorphy_notes' => '<p>Presence of underlying -w\' diagnosed by umlaut of preceding vowel (Goddard 2007:249). On the surface -w\' deletes because *-pan "did not originally take connective *e" (250).</p>'
            ]
        ]);

        DB::table('examples')->insert([
            [
                'id' => 1,
                'shape' => 'mi·čihswiwa·či',
                'stem_id' => 9,
                'form_id' => 1,
                'translation' => 'they eat',
                'slug' => 'mi·čihswiwa·či'
            ]
        ]);

        VerbForm::find(1)->addSource(Source::find(3));
        VerbForm::find(1)->addSource(Source::find(4));
        VerbForm::find(2)->addSource(Source::find(3));
        Example::find(1)->addSource(Source::find(3));
    }
}
