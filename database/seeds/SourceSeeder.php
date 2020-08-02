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
                'slug' => 'oxford-2017'
            ]
        ]);
    }
}
