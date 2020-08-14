<?php

use App\Source;
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
            ],
            [
                'id' => 5,
                'author' => 'Goddard',
                'year' => 2001,
                'full_citation' => '<p>Goddard, Ives. 2001. Contraction in Fox (Meskwaki). <i>Actes du 32e Congrès des Algonquinistes</i>, ed. by John D. Nichols, pp. 164-230. Winnipeg: University of Manitoba.</p>',
                'slug' => 'goddard-2001-a',
                'website' => null
            ],
            [
                'id' => 6,
                'author' => 'Goddard',
                'year' => 2001,
                'full_citation' => '<p>Goddard, Ives. 2001. The Algonquian languages of the Plains. <i>Handbook of North American Indians</i>, vol. 13: Plains, ed. by Raymond J. DeMallie, 71–79. Washington, DC: Smithsonian Institution.</p>',
                'slug' => 'goddard-2001-b',
                'website' => null
            ],
            [
                'id' => 7,
                'author' => 'Pentland',
                'year' => 1999,
                'full_citation' => '<p>Pentland, David H. 1999. The morphology of the Algonquian independent order. <i>Papers of the 30th Algonquian Conference</i>, ed. by David H. Pentland, pp. 222-266. Winnipeg: University of Manitoba.</p>',
                'slug' => 'pentland-1999',
                'website' => null
            ]
        ]);

        DB::table('disambiguations')->insert([
            [
                'id' => 1,
                'disambiguatable_type' => Source::class,
                'disambiguatable_id' => 5,
                'disambiguator' => 0
            ],
            [
                'id' => 2,
                'disambiguatable_type' => Source::class,
                'disambiguatable_id' => 6,
                'disambiguator' => 1
            ]
        ]);

        factory(Source::class, 150)->create();
    }
}
