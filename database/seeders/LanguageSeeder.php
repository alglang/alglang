<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            ],
            [
                'name' => 'Proto-Arapaho-Gros Ventre',
                'alternate_names' => null,
                'parent_code' => 'PA',
                'code' => 'PAGV',
                'iso' => null,
                'reconstructed' => true,
                'position' => null,
                'group_name' => 'Arapahoan'
            ],
            [
                'name' => 'Arapaho',
                'alternate_names' => null,
                'parent_code' => 'PAGV',
                'code' => 'Ar',
                'iso' => 'arp',
                'reconstructed' => false,
                'position' => null,
                'group_name' => 'Arapahoan'
            ],
            [
                'name' => 'Cheyenne',
                'alternate_names' => '["Tsėhésenėstsestȯtse"]',
                'parent_code' => 'PA',
                'code' => 'Ch',
                'iso' => 'chy',
                'reconstructed' => false,
                'position' => null,
                'group_name' => 'Plains'
            ],
            [
                'name' => 'Proto-Easter Algonquian',
                'alternate_names' => null,
                'parent_code' => 'PA',
                'code' => 'PEA',
                'iso' => null,
                'reconstructed' => false,
                'position' => null,
                'group_name' => 'Eastern'
            ],
            [
                'name' => 'Common Delaware',
                'alternate_names' => null,
                'parent_code' => 'PEA',
                'code' => 'Del',
                'iso' => null,
                'reconstructed' => false,
                'position' => null,
                'group_name' => 'Delaware'
            ],
            [
                'name' => 'Munsee',
                'alternate_names' => '["Munsee Delaware", "Lenape"]',
                'parent_code' => 'Del',
                'code' => 'Mun',
                'iso' => 'umu',
                'reconstructed' => false,
                'position' => null,
                'group_name' => 'Delaware'
            ]
        ]);
    }
}
