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
                'name' => '3',
                'person' => '3',
                'number' => null,
                'obviative_code' => null
            ],
            [
                'name' => '3p',
                'person' => '3',
                'number' => 3,
                'obviative_code' => null
            ],
            [
                'name' => '3s',
                'person' => '3',
                'number' => 1,
                'obviative_code' => null
            ],
            [
                'name' => '1',
                'person' => '1',
                'number' => null,
                'obviative_code' => null
            ],
            [
                'name' => '1s',
                'person' => '1',
                'number' => 1,
                'obviative_code' => null
            ],
            [
                'name' => '1p',
                'person' => '1',
                'number' => 3,
                'obviative_code' => null
            ],
            [
                'name' => '2',
                'person' => '2',
                'number' => null,
                'obviative_code' => null
            ],
            [
                'name' => '2s',
                'person' => '2',
                'number' => 1,
                'obviative_code' => null
            ],
            [
                'name' => '21',
                'person' => '21',
                'number' => 3,
                'obviative_code' => null
            ],
            [
                'name' => '2p',
                'person' => '2',
                'number' => 3,
                'obviative_code' => null
            ]
        ]);
    }
}
