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
        DB::table('morphemes')->insert([
            [
                'id' => 1,
                'shape' => 'ak-',
                'slug' => 'ak',
                'language_id' => 1,
                'slot_id' => 1
            ]
        ]);
    }
}
