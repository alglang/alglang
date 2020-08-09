<?php

use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sources')->insert([
            [
                'id' => 1,
                'author' => 'Oxford',
                'year' => 2017,
                'full_citation' => '<p>Oxford, Will. 2017. Inverse marking as impoverishment. In <i>Proceedings of WCCFL 34</i>, ed. by Aaron Kaplan, Abby Kaplan, Miranda K. McCarvel, and Edward J. Rubin, 413–422. Somerville, MA: Cascadilla.</p>',
                'slug' => 'oxford-2017',
                'website' => null
            ],
            [
                'id' => 2,
                'author' => 'Goddard',
                'year' => 2007,
                'full_citation' => '<p>Goddard, Ives. 2007. Reconstruction and history of the independent indicative. <i>Papers of the 38th Algonquian Conference</i>, ed. H.C. Wolfart, 207-271. Winnipeg: University of Manitoba.</p>',
                'slug' => 'goddard-2007',
                'website' => 'https://ojs3.library.carleton.ca/index.php/ALGQP/article/view/94'
            ],
            [
                'id' => 3,
                'author' => 'Goddard',
                'year' => 2000,
                'full_citation' => '<p>Goddard, Ives. 2000. The historical origins of Cheyenne inflections. <i>Papers of the 31st Algonquian Conference</i>, ed. by John D. Nichols, pp. 77-129. Winnipeg: University of Manitoba.</p>',
                'slug' => 'goddard-2000',
                'website' => 'https://ojs3.library.carleton.ca/index.php/ALGQP/article/view/854'
            ],
            [
                'id' => 4,
                'author' => 'Bloomfield',
                'year' => 1946,
                'full_citation' => '<p>Bloomfield, Leonard. 1946. Algonquian. In <i>Linguistic Structures of Native America</i>, ed. by Harry Hoijer et al., pp. 85-129. Viking Fund Publications in Anthropology 6. New York.</p>',
                'slug' => 'bloomfield-1946',
                'website' => 'https://home.cc.umanitoba.ca/~oxfordwr/bloomfield1946/'
            ]
        ]);
    }
}
