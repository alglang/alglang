<?php

namespace Database\Seeders;

use App\Models\ClusterFeatureSet;
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
            ['name' => 'labial'],
            ['name' => 'glottal']
        ]);

        DB::table('consonant_manners')->insert([
            ['name' => 'stop']
        ]);

        DB::table('vowel_heights')->insert([
            ['name' => 'high'],
            ['name' => 'low']
        ]);

        DB::table('vowel_backnesses')->insert([
            ['name' => 'front'],
            ['name' => 'back']
        ]);

        DB::table('vowel_lengths')->insert([
            ['name' => 'short']
        ]);

        DB::table('consonant_feature_sets')->insert([
            [
                'id' => 1,
                'place_name' => 'labial',
                'manner_name' => 'stop'
            ],
            [
                'id' => 2,
                'place_name' => 'glottal',
                'manner_name' => 'stop'
            ]
        ]);

        DB::table('vowel_feature_sets')->insert([
            [
                'id' => 1,
                'height_name' => 'high',
                'backness_name' => 'front',
                'length_name' => 'short'
            ],
            [
                'id' => 2,
                'height_name' => 'low',
                'backness_name' => 'front',
                'length_name' => 'short'
            ],
            [
                'id' => 3,
                'height_name' => 'high',
                'backness_name' => 'back',
                'length_name' => 'short'
            ],
            [
                'id' => 4,
                'height_name' => 'low',
                'backness_name' => 'back',
                'length_name' => 'short'
            ],
        ]);

        DB::table('cluster_feature_sets')->insert([
            [
                'id' => 1,
                'first_segment_id' => 2,
                'second_segment_id' => 1
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
                'shape' => 'ʔ',
                'ipa' => 'ʔ',
                'slug' => 'ʔ',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 2  // Glottal stop
            ],
            [
                'id' => 3,
                'shape' => 'i',
                'ipa' => 'i',
                'slug' => 'i',
                'language_code' => 'PA',
                'featureable_type' => VowelFeatureSet::class,
                'featureable_id' => 1  // Labial stop
            ],
            [
                'id' => 4,
                'shape' => 'xp',
                'ipa' => 'ʔp',
                'slug' => 'xp',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 1
            ],
            [
                'id' => 5,
                'shape' => 'p',
                'ipa' => 'p',
                'slug' => 'p',
                'language_code' => 'C',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 1  // Labial stop
            ],
            [
                'id' => 6,
                'shape' => 'e',
                'ipa' => 'e',
                'slug' => 'e',
                'language_code' => 'PA',
                'featureable_type' => VowelFeatureSet::class,
                'featureable_id' => 2
            ],
            [
                'id' => 7,
                'shape' => 'o',
                'ipa' => 'o',
                'slug' => 'o',
                'language_code' => 'PA',
                'featureable_type' => VowelFeatureSet::class,
                'featureable_id' => 3
            ],
            [
                'id' => 8,
                'shape' => 'a',
                'ipa' => 'a',
                'slug' => 'a',
                'language_code' => 'PA',
                'featureable_type' => VowelFeatureSet::class,
                'featureable_id' => 4
            ]
        ]);

        DB::table('reflexes')->insert([
            [
                'id' => 1,
                'phoneme_id' => 1,  // PA p
                'reflex_id' => 5    // C p
            ]
        ]);
    }
}
