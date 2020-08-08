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

        DB::table('verb_forms')->insert([
            [
                'id' => 1,
                'shape' => 'V-(o)wa·či',
                'morpheme_structure' => '1-6-7-8',  // V-wa·-t-i
                'language_id' => 1,  // Proto-Algonquian
                'class_id' => 1,  // TA
                'mode_id' => 1,  // Indicative
                'order_id' => 1,  // Conjunct
                'subject_id' => 1,  // 3p
                'slug' => 'V-(o)wa·či',
                'allomorphy_notes' => 'Insert /o/ after a consonant'
            ],
            [
                'id' => 2,
                'shape' => 'V-pa',
                'morpheme_structure' => null,
                'language_id' => 1,  // Proto-Algonquian
                'class_id' => 2,  // AI
                'mode_id' => 2,  // Preterit
                'order_id' => 2,  // Independent
                'subject_id' => 2,  // 3s
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

        VerbForm::find(2)->addSource(Source::find(3));
        Example::find(1)->addSource(Source::find(3));
    }
}
