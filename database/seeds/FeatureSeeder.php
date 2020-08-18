<?php

use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('features')->insert([
            [
                'id' => 1,
                'name' => '3p'
            ],
            [
                'id' => 2,
                'name' => '3s'
            ],
            [
                'id' => 3,
                'name' => '1s'
            ]
        ]);
    }
}
