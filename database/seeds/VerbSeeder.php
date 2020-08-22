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
            ['name' => 'Conjunct'],
            ['name' => 'Independent']
        ]);

        DB::table('verb_classes')->insert([
            ['abv' => 'TA'],
            ['abv' => 'AI']
        ]);

        DB::table('verb_modes')->insert([
            ['name' => 'Indicative'],
            ['name' => 'Preterit']
        ]);

        DB::table('verb_structures')->insert([
            [
                'id' => 1,
                'subject_name' => '3p',
                'class_abv' => 'TA',
                'order_name' => 'Conjunct',
                'mode_name' => 'Indicative'
            ],
            [
                'id' => 2,
                'subject_name' => '3s',
                'class_abv' => 'AI',
                'order_name' => 'Independent',
                'mode_name' => 'Preterit'
            ],
            [
                'id' => 3,
                'subject_name' => '3s',
                'class_abv' => 'AI',
                'order_name' => 'Independent',
                'mode_name' => 'Indicative'
            ],
            [
                'id' => 4,
                'subject_name' => '1s',
                'class_abv' => 'AI',
                'order_name' => 'Conjunct',
                'mode_name' => 'Indicative'
            ],
            [
                'id' => 5,
                'subject_name' => '1p',
                'class_abv' => 'AI',
                'order_name' => 'Conjunct',
                'mode_name' => 'Indicative'
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
            ],
            [
                'id' => 7,
                'shape' => 'V-(y)a·ni',
                'language_id' => 1,
                'parent_id' => null,
                'structure_type' => VerbStructure::class,
                'structure_id' => 4,  // 1s AI Conjunct Indicative
                'slug' => 'V-(y)a·ni',
                'allomorphy_notes' => '<p>Linking /y/ realized only after a vowel</p>'
            ],
            [
                'id' => 8,
                'shape' => 'V-(y)a·nke',
                'language_id' => 1,
                'parent_id' => null,
                'structure_type' => VerbStructure::class,
                'structure_id' => 5,  // 1p AI Conjunct Indicative
                'slug' => 'V-(y)a·nke',
                'allomorphy_notes' => '<p>Linking /y/ realized only after a vowel</p>'
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
        Form::find(7)->addSource(Source::find(3));
        Form::find(8)->addSource(Source::find(3));
        Form::find(8)->addSource(Source::find(4));

        Example::find(1)->addSource(Source::find(3));
    }
}
