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
                'algo_code' => 'PA',
                'slug' => 'pa',
                'position' => '{"lat":46.0,"lng":-87.659916}',
                'group_id' => 1
            ]
        ]);
    }
}
