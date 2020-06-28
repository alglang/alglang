<?php

use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('languages')->insert([
            [
                'id' => 1,
                'name' => 'Proto-Algonquian',
                'parent_id' => null,
                'algo_code' => 'PA',
                'reconstructed' => true,
                'slug' => 'pa',
                'position' => '{"lat":46.0,"lng":-87.659916}',
                'group_id' => 1
            ],
            [
                'id' => 2,
                'name' => 'Common Cree',
                'parent_id' => 1,
                'algo_code' => 'C',
                'reconstructed' => false,
                'slug' => 'c',
                'position' => null,
                'group_id' => 1
            ]
        ]);
    }
}
