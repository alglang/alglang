<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rules')->insert([
            'abv' => 'LinkY',
            'language_code' => 'PA',
            'name' => 'Linking y',
            'description' => 'Linking /y/ realized only after a vowel',
            'public_notes' => null,
            'private_notes' => null
        ]);
    }
}
