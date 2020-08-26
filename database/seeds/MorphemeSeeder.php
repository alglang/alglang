<?php

use App\Morpheme;
use App\Source;
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
                'abv' => 'N',
                'name' => 'noun stem'
            ],
            [
                'abv' => '1s',
                'name' => 'first person singular'
            ],
            [
                'abv' => 'an',
                'name' => 'animate'
            ],
            [
                'abv' => 'obv',
                'name' => 'obviative'
            ],
            [
                'abv' => 'sg',
                'name' => 'singular'
            ],
            [
                'abv' => 'pl',
                'name' => 'plural'
            ],
            [
                'abv' => 'pret',
                'name' => 'preterit'
            ],
            [
                'abv' => '3',
                'name' => 'third person'
            ],
            [
                'abv' => 'indic',
                'name' => 'conjunct indicative'
            ],
            [
                'abv' => '3p',
                'name' => 'third person plural'
            ],
            [
                'abv' => '1',
                'name' => 'first person'
            ],
            [
                'abv' => 'dir',
                'name' => 'direct or animate 3rd-person object'
            ],
            [
                'abv' => 'ftv',
                'name' => 'formative'
            ],
            [
                'abv' => 'm',
                'name' => 'm-suffix'
            ]
        ]);

        DB::table('slots')->insert([
            [
                'abv' => 'STM',
                'name' => 'stem',
                'colour' => '#000000',
                'description' => null
            ],
            [
                'abv' => 'CCEN',
                'name' => 'conjunct central suffix',
                'colour' => '#0000ff',
                'description' => null
            ],
            [
                'abv' => 'PER',
                'name' => 'peripheral suffix',
                'colour' => '#ff00ff',
                'description' => null
            ],
            [
                'abv' => 'MOD',
                'name' => 'mode sign',
                'colour' => 'rgb(50, 205, 50)',
                'description' => '<p>Currently being used for independent mode signs (which appear adjacent to the central suffix) and conjunct mode signs (which appear word-finally), as well as the medial -w element in the interrogative order.</p>'
            ],
            [
                'abv' => 'CAUG',
                'name' => 'central suffix argument',
                'colour' => 'rgb(0, 127, 255)',
                'description' => '<p>Obviative */-ri/, conjunct plural */-wa·(w)/, conjunct impersonal */-en/</p>'
            ],
            [
                'abv' => 'PFX',
                'name' => 'prefix',
                'colour' => 'rgb(0, 0, 255)',
                'description' => null
            ],
            [
                'abv' => 'TS',
                'name' => 'theme sign',
                'colour' => '#ff0000',
                'description' => null
            ],
            [
                'abv' => 'FTV',
                'name' => 'central suffix formative',
                'colour' => '#000080',
                'description' => '<p>Combines with central suffix pluralizer to form an independent-order central suffix.</p>'
            ],
            [
                'abv' => 'ICEN',
                'name' => 'independent central suffix',
                'colour' => '#0000ff',
                'description' => '<p>In conservative independent forms, the central suffix is a composite of a formative element (FTV) plus a pluralizer (PLZ). The slot CEN is used only in instances where the formative and pluralizer portions of the central suffix can no longer be segmented.</p>'
            ]
        ]);

        DB::table('morphemes')->insert([
            [
                'id' => 1,
                'shape' => 'V-',
                'slug' => 'V-1',
                'language_code' => 'PA',
                'parent_id' => null,
                'slot_abv' => 'STM',
                'gloss' => 'V',
                'allomorphy_notes' => null,
                'historical_notes' => null,
                /* 'usage_notes' => null, */
                'private_notes' => '<p>This is a test note.</p>'
            ],
            [
                'id' => 2,
                'shape' => 'N-',
                'slug' => 'N-1',
                'language_code' => 'PA',
                'parent_id' => null,
                'slot_abv' => 'STM',
                'gloss' => 'N',
                'allomorphy_notes' => null,
                'historical_notes' => null,
                /* 'usage_notes' => null, */
                'private_notes' => null
            ],
            [
                'id' => 3,
                'shape' => '-a·n',
                'slug' => 'a·n-1',
                'language_code' => 'PA',
                'parent_id' => null,
                'slot_abv' => 'CCEN',
                'gloss' => '1s',
                'allomorphy_notes' => '<p>Becomes -ya:n after a vowel.</p>',
                'historical_notes' => null,
                /* 'usage_notes' => null, */
                'private_notes' => null
            ],
            [
                'id' => 4,
                'shape' => '-ari',
                'slug' => 'ari-1',
                'language_code' => 'PA',
                'parent_id' => null,
                'slot_abv' => 'PER',
                'gloss' => 'an.obv.sg',
                'allomorphy_notes' => null,
                'historical_notes' => '<p>This is a test historical note.</p>',
                /* 'usage_notes' => null, */
                'private_notes' => null
            ],
            [
                'id' => 5,
                'shape' => 'wa·pam-',
                'slug' => 'wa·pam-1',
                'language_code' => 'PA',
                'parent_id' => null,
                'slot_abv' => 'STM',
                'gloss' => 'see',
                'allomorphy_notes' => null,
                'historical_notes' => null,
                /* 'usage_notes' => null, */
                'private_notes' => null
            ],
            [
                'id' => 6,
                'shape' => '-(e)pan',
                'slug' => '(e)pan-1',
                'language_code' => 'PA',
                'parent_id' => null,
                'slot_abv' => 'MOD',
                'gloss' => 'pret',
                'allomorphy_notes' => '<p>Word-finally -(e)pa (Goddard 2007:249). Also, although Goddard represents it as *-(e)pan, he states (p. 250) that "*-pa(n-) did not originally take connective *e (Pentland 1979:381; Proulx 1990:106; Costa 2003:355-360)".</p>',
                'historical_notes' => null,
                /* 'usage_notes' => null, */
                'private_notes' => null
            ],
            [
                'id' => 7,
                'shape' => '-wa·',
                'slug' => 'wa·-1',
                'language_code' => 'PA',
                'parent_id' => null,
                'slot_abv' => 'CAUG',
                'gloss' => '3p',
                'allomorphy_notes' => '<ul><li>When it precedes the central suffix /-t/, it does not trigger the postconsonantal /-k/, so it must be /-wa:-t/ rather than /-wa:w-t/ (which ought to become /-wa:w-k/ > /-wa:kw/)</li><li>Becomes /-owa:/ after a nasal (after AI n-stems and TI1 -am).</li></ul>',
                'historical_notes' => null,
                /* 'usage_notes' => '<p>Precedes and pluralizes the central suffix.</p>', */
                'private_notes' => null
            ],
            [
                'id' => 8,
                'shape' => '-t',
                'slug' => 't-1',
                'language_code' => 'PA',
                'parent_id' => null,
                'slot_abv' => 'CCEN',
                'gloss' => '3',
                'allomorphy_notes' => '<p>Palatalizes to c before i. Becomes k after a consonant (which is usually either a nasal or theta).</p>',
                'historical_notes' => null,
                'private_notes' => null
            ],
            [
                'id' => 9,
                'shape' => '-i',
                'slug' => 'i-1',
                'language_code' => 'PA',
                'parent_id' => null,
                'slot_abv' => 'MOD',
                'gloss' => 'indic',
                'allomorphy_notes' => null,
                'historical_notes' => null,
                'private_notes' => null
            ],
            [
                'id' => 10,
                'shape' => 'mi·čihswi-',
                'slug' => 'mi·čihswi-1',
                'language_code' => 'PA',
                'parent_id' => null,
                'slot_abv' => 'STM',
                'gloss' => 'eat',
                'allomorphy_notes' => '<p>-wi- becomes -o- before w. (at least in Independent, not in Conjunct 3p)</p>',
                'historical_notes' => '<p>Reflected in Cheyenne, Arapaho, Cree, Menominee (Goddard 2000:91).</p>',
                'private_notes' => null
            ],
            [
                'id' => 11,
                'shape' => 'ne-',
                'slug' => 'ne-1',
                'language_code' => 'PA',
                'parent_id' => null,
                'slot_abv' => 'PFX',
                'gloss' => '1',
                'allomorphy_notes' => null,
                'historical_notes' => null,
                'private_notes' => null
            ],
            [
                'id' => 12,
                'shape' => 'a-',
                'slug' => 'a-1',
                'language_code' => 'PA',
                'parent_id' => null,
                'slot_abv' => 'PER',
                'gloss' => 'an.sg',
                'allomorphy_notes' => null,
                'historical_notes' => null,
                'private_notes' => null
            ],
            [
                'id' => 13,
                'shape' => 'aki-',
                'slug' => 'aki-1',
                'language_code' => 'PA',
                'parent_id' => null,
                'slot_abv' => 'PER',
                'gloss' => 'an.pl',
                'allomorphy_notes' => null,
                'historical_notes' => null,
                'private_notes' => null
            ],
            [
                'id' => 14,
                'shape' => 'a-',
                'slug' => 'a-2',
                'language_code' => 'PA',
                'parent_id' => null,
                'slot_abv' => 'TS',
                'gloss' => 'dir',
                'allomorphy_notes' => null,
                'historical_notes' => null,
                'private_notes' => null
            ],
            [
                'id' => 15,
                'shape' => 'a-',
                'slug' => 'a-1',
                'language_code' => 'SwO',
                'parent_id' => 14,
                'slot_abv' => 'TS',
                'gloss' => 'dir',
                'allomorphy_notes' => null,
                'historical_notes' => null,
                'private_notes' => null
            ],
            [
                'id' => 16,
                'shape' => '-w',
                'slug' => 'w-1',
                'language_code' => 'PA',
                'parent_id' => null,
                'slot_abv' => 'FTV',
                'gloss' => 'ftv.m',
                'allomorphy_notes' => null,
                'historical_notes' => null,
                'private_notes' => null
            ],
            [
                'id' => 17,
                'shape' => '-w',
                'slug' => 'w-1',
                'language_code' => 'SwO',
                'parent_id' => null,
                'slot_abv' => 'ICEN',
                'gloss' => '3',
                'allomorphy_notes' => null,
                'historical_notes' => null,
                'private_notes' => null
            ],
            [
                'id' => 18,
                'shape' => 'V-',
                'slug' => 'V-1',
                'language_code' => 'SwO',
                'parent_id' => null,
                'slot_abv' => 'STM',
                'gloss' => 'V',
                'allomorphy_notes' => null,
                'historical_notes' => null,
                'private_notes' => null
            ]
        ]);

        Morpheme::find(5)->addSource(Source::find(2)); // Goddard 2007

        DB::table('disambiguations')->insert([
            [
                'disambiguatable_type' => Morpheme::class,
                'disambiguatable_id' => 12,
                'disambiguator' => 0
            ],
            [
                'disambiguatable_type' => Morpheme::class,
                'disambiguatable_id' => 14,
                'disambiguator' => 1
            ]
        ]);
    }
}
