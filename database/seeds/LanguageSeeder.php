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
                'name' => 'Proto-Algonquian',
                'alternate_names' => null,
                'parent_code' => null,
                'code' => 'PA',
                'iso' => 'alg',
                'reconstructed' => true,
                'position' => '{"lat":46.0,"lng":-87.659916}',
                'group_name' => 'Algonquian'
            ],
            [
                'name' => 'Common Cree',
                'alternate_names' => null,
                'parent_code' =>'PA',
                'code' => 'C',
                'iso' => null,
                'reconstructed' => false,
                'position' => null,
                'group_name' => 'Algonquian'
            ],
            [
                'name' => 'Southwestern Ojibwe',
                'alternate_names' => '["Chippewa"]',
                'parent_code' => 'PA',
                'code' => 'SwO',
                'iso' => 'ciw',
                'reconstructed' => false,
                'position' => '{"lat":46.271362,"lng":-93.392167}',
                'group_name' => 'Algonquian'
            ]
        ]);
    }
}
