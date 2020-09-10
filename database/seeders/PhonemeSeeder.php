<?php

namespace Database\Seeders;

use App\Models\ConsonantFeatureSet;
use App\Models\VowelFeatureSet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhonemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('consonant_places')->insert([
            ['name' => 'labial']
        ]);

        DB::table('consonant_manners')->insert([
            ['name' => 'stop']
        ]);

        DB::table('vowel_heights')->insert([
            ['name' => 'high']
        ]);

        DB::table('vowel_backnesses')->insert([
            ['name' => 'front']
        ]);

        DB::table('vowel_lengths')->insert([
            ['name' => 'short']
        ]);

        DB::table('consonant_feature_sets')->insert([
            [
                'id' => 1,
                'place_name' => 'labial',
                'manner_name' => 'stop'
            ]
        ]);

        DB::table('vowel_feature_sets')->insert([
            [
                'id' => 1,
                'height_name' => 'high',
                'backness_name' => 'front',
                'length_name' => 'short'
            ]
        ]);

        DB::table('phonemes')->insert([
            [
                'id' => 1,
                'shape' => 'p',
                'ipa' => 'p',
                'slug' => 'p',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 1  // Labial stop
            ],
            [
                'id' => 2,
                'shape' => 'i',
                'ipa' => 'i',
                'slug' => 'i',
                'language_code' => 'PA',
                'featureable_type' => VowelFeatureSet::class,
                'featureable_id' => 1  // Labial stop
            ]
        ]);
    }
}
