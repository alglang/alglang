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
                'name' => 'Algonquian',
                'parent_name' => null,
                'slug' => 'algonquian',
                'description' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
            ],

            [
                'name' => 'Central',
                'parent_name' => 'Algonquian',
                'slug' => 'central',
                'description' => null
            ]
        ]);
    }
}
