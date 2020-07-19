<?php

use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->insert([
            [
                'id' => 1,
                'parent_id' => null,
                'name' => 'Algonquian',
                'slug' => 'algonquian',
                'description' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
            ],

            [
                'id' => 2,
                'parent_id' => 1,  // Algonquian
                'name' => 'Central',
                'slug' => 'central',
                'description' => null
            ]
        ]);
    }
}
