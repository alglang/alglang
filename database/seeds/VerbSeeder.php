<?php

use App\Example;
use App\Form;
use App\Morpheme;
use App\Source;
use App\VerbStructure;
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
            ],
            [
                'id' => 3,
                'subject_id' => 2,  // 3s
                'class_id' => 2,    // AI
                'order_id' => 2,    // Independent
                'mode_id' => 1      // Indicative
            ]
        ]);

        DB::table('forms')->insert([
            [
                'id' => 1,
                'shape' => 'V-(o)wa·či',
                'language_id' => 1,  // Proto-Algonquian
                'parent_id' => null,
                'structure_type' => VerbStructure::class,
                'structure_id' => 1,  // 3p TA Conjunct Indicative
                'slug' => 'V-(o)wa·či',
                'allomorphy_notes' => 'Insert /o/ after a consonant'
            ],
            [
                'id' => 2,
                'shape' => 'V-pa',
                'language_id' => 1,  // Proto-Algonquian
                'parent_id' => null,
                'structure_type' => VerbStructure::class,
                'structure_id' => 2,  // 3s AI Independent Preterit
                'slug' => 'V-pa',
                'allomorphy_notes' => '<p>Presence of underlying -w\' diagnosed by umlaut of preceding vowel (Goddard 2007:249). On the surface -w\' deletes because *-pan "did not originally take connective *e" (250).</p>'
            ],
            [
                'id' => 5,
                'shape' => 'V-wa',
                'language_id' => 1,
                'parent_id' => null,
                'structure_type' => VerbStructure::class,
                'structure_id' => 3,  // 3s AI Independent Indicative
                'slug' => 'V-wa',
                'allomorphy_notes' => null
            ],
            [
                'id' => 6,
                'shape' => 'V',
                'language_id' => 3,  // Southwestern Ojibwe
                'parent_id' => 5,
                'structure_type' => VerbStructure::class,
                'structure_id' => 3,  // 3s AI Independent Indicative
                'slug' => 'V',
                'allomorphy_notes' => null
            ]
        ]);

        DB::table('examples')->insert([
            [
                'id' => 1,
                'shape' => 'mi·čihswiwa·či',
                'stem_id' => 10,
                'form_id' => 1,
                'translation' => 'they eat',
                'slug' => 'mi·čihswiwa·či'
            ]
        ]);

        Form::find(1)->assignMorphemes([
            Morpheme::find(1),
            Morpheme::find(7),
            Morpheme::find(8),
            Morpheme::find(9)
        ]);  // V-wa·-t-i

        Form::find(5)->assignMorphemes([
            Morpheme::find(1),
            Morpheme::find(16),
            Morpheme::find(14)
        ]);  // V-w-a

        Form::find(6)->assignMorphemes([
            Morpheme::find(18),
            Morpheme::find(17),
            Morpheme::find(15)
        ]);

        Form::find(1)->addSource(Source::find(3));
        Form::find(1)->addSource(Source::find(4));
        Form::find(2)->addSource(Source::find(3));

        Example::find(1)->addSource(Source::find(3));
    }
}
