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
            [
                'name' => 'labial',
                'order_key' => 0
            ],
            [
                'name' => 'interdental',
                'order_key' => 1
            ],
            [
                'name' => 'dental/alveolar',
                'order_key' => 2
            ],
            [
                'name' => 'alveopalatal',
                'order_key' => 3
            ],
            [
                'name' => 'palatal',
                'order_key' => 4
            ],
            [
                'name' => 'velar',
                'order_key' => 5
            ],
            [
                'name' => 'glottal',
                'order_key' => 6
            ]
        ]);

        DB::table('consonant_manners')->insert([
            [
                'name' => 'stop',
                'order_key' => 0
            ],
            [
                'name' => 'fricative',
                'order_key' => 1
            ],
            [
                'name' => 'affricate',
                'order_key' => 2
            ],
            [
                'name' => 'nasal',
                'order_key' => 3
            ],
            [
                'name' => 'liquid',
                'order_key' => 4
            ],
            [
                'name' => 'glide',
                'order_key' => 5
            ]
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
            ],
            [
                'id' => 3,
                'place_name' => 'dental/alveolar',
                'manner_name' => 'stop'
            ],
            [
                'id' => 4,
                'place_name' => 'velar',
                'manner_name' => 'stop'
            ],
            [
                'id' => 5,
                'place_name' => 'interdental',
                'manner_name' => 'fricative'
            ],
            [
                'id' => 6,
                'place_name' => 'dental/alveolar',
                'manner_name' => 'fricative'
            ],
            [
                'id' => 7,
                'place_name' => 'alveopalatal',
                'manner_name' => 'fricative'
            ],
            [
                'id' => 8,
                'place_name' => 'glottal',
                'manner_name' => 'fricative'
            ],
            [
                'id' => 9,
                'place_name' => 'alveopalatal',
                'manner_name' => 'affricate'
            ],
            [
                'id' => 10,
                'place_name' => 'labial',
                'manner_name' => 'nasal'
            ],
            [
                'id' => 11,
                'place_name' => 'dental/alveolar',
                'manner_name' => 'nasal'
            ],
            [
                'id' => 12,
                'place_name' => 'labial',
                'manner_name' => 'glide'
            ],
            [
                'id' => 13,
                'place_name' => 'palatal',
                'manner_name' => 'glide'
            ],
            [
                'id' => 14,
                'place_name' => 'dental/alveolar',
                'manner_name' => 'liquid'
            ],
            [
                'id' => 15,
                'place_name' => null,
                'manner_name' => 'nasal'
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
            [
                'id' => 5,
                'height_name' => 'high',
                'backness_name' => 'front',
                'length_name' => 'long'
            ],
            [
                'id' => 6,
                'height_name' => 'low',
                'backness_name' => 'front',
                'length_name' => 'long'
            ],
            [
                'id' => 7,
                'height_name' => 'high',
                'backness_name' => 'back',
                'length_name' => 'long'
            ],
            [
                'id' => 8,
                'height_name' => 'low',
                'backness_name' => 'back',
                'length_name' => 'long'
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
                'featureable_id' => 1,
                'is_marginal' => false
            ],
            [
                'id' => 2,
                'shape' => 'ʔ',
                'ipa' => 'ʔ',
                'slug' => 'ʔ',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 2,
                'is_marginal' => true
            ],
            [
                'id' => 3,
                'shape' => 'i',
                'ipa' => 'i',
                'slug' => 'i',
                'language_code' => 'PA',
                'featureable_type' => VowelFeatureSet::class,
                'featureable_id' => 1,
                'is_marginal' => false
            ],
            [
                'id' => 4,
                'shape' => 'xp',
                'ipa' => 'ʔp',
                'slug' => 'xp',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 1,
                'is_marginal' => false
            ],
            [
                'id' => 5,
                'shape' => 'p',
                'ipa' => 'p',
                'slug' => 'p',
                'language_code' => 'C',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 1,
                'is_marginal' => false
            ],
            [
                'id' => 6,
                'shape' => 'e',
                'ipa' => 'e',
                'slug' => 'e',
                'language_code' => 'PA',
                'featureable_type' => VowelFeatureSet::class,
                'featureable_id' => 2,
                'is_marginal' => false
            ],
            [
                'id' => 7,
                'shape' => 'o',
                'ipa' => 'o',
                'slug' => 'o',
                'language_code' => 'PA',
                'featureable_type' => VowelFeatureSet::class,
                'featureable_id' => 3,
                'is_marginal' => false
            ],
            [
                'id' => 8,
                'shape' => 'a',
                'ipa' => 'a',
                'slug' => 'a',
                'language_code' => 'PA',
                'featureable_type' => VowelFeatureSet::class,
                'featureable_id' => 4,
                'is_marginal' => false
            ],
            [
                'id' => 9,
                'shape' => 'i·',
                'ipa' => 'iː',
                'slug' => 'i·',
                'language_code' => 'PA',
                'featureable_type' => VowelFeatureSet::class,
                'featureable_id' => 5,
                'is_marginal' => false
            ],
            [
                'id' => 10,
                'shape' => 'e·',
                'ipa' => 'eː',
                'slug' => 'e·',
                'language_code' => 'PA',
                'featureable_type' => VowelFeatureSet::class,
                'featureable_id' => 6,
                'is_marginal' => false
            ],
            [
                'id' => 11,
                'shape' => 'o·',
                'ipa' => 'oː',
                'slug' => 'o·',
                'language_code' => 'PA',
                'featureable_type' => VowelFeatureSet::class,
                'featureable_id' => 7,
                'is_marginal' => false
            ],
            [
                'id' => 12,
                'shape' => 'a·',
                'ipa' => 'aː',
                'slug' => 'a·',
                'language_code' => 'PA',
                'featureable_type' => VowelFeatureSet::class,
                'featureable_id' => 8,
                'is_marginal' => false
            ],
            [
                'id' => 13,
                'shape' => 't',
                'ipa' => 't',
                'slug' => 't',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 3,
                'is_marginal' => false
            ],
            [
                'id' => 14,
                'shape' => 'k',
                'ipa' => 'k',
                'slug' => 'k',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 4,
                'is_marginal' => false
            ],
            [
                'id' => 15,
                'shape' => 'θ',
                'ipa' => 'θ',
                'slug' => 'θ',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 5,
                'is_marginal' => false
            ],
            [
                'id' => 16,
                'shape' => 's',
                'ipa' => 's',
                'slug' => 's',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 6,
                'is_marginal' => false
            ],
            [
                'id' => 17,
                'shape' => 'š',
                'ipa' => 'ʃ',
                'slug' => 'š',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 7,
                'is_marginal' => false
            ],
            [
                'id' => 18,
                'shape' => 'h',
                'ipa' => 'h',
                'slug' => 'h',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 8,
                'is_marginal' => false
            ],
            [
                'id' => 19,
                'shape' => 'č',
                'ipa' => 'tʃ',
                'slug' => 'č',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 9,
                'is_marginal' => false
            ],
            [
                'id' => 20,
                'shape' => 'm',
                'ipa' => 'm',
                'slug' => 'm',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 10,
                'is_marginal' => false
            ],
            [
                'id' => 21,
                'shape' => 'n',
                'ipa' => 'n',
                'slug' => 'n',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 11,
                'is_marginal' => false
            ],
            [
                'id' => 22,
                'shape' => 'w',
                'ipa' => 'w',
                'slug' => 'w',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 12,
                'is_marginal' => false
            ],
            [
                'id' => 23,
                'shape' => 'y',
                'ipa' => 'j',
                'slug' => 'y',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 13,
                'is_marginal' => false
            ],
            [
                'id' => 24,
                'shape' => 'r',
                'ipa' => 'r',
                'slug' => 'r',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 14,
                'is_marginal' => false
            ]
        ]);

        DB::table('phonemes')->insert([
            [
                'id' => 25,
                'shape' => 'N',
                'slug' => 'N',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 15,
                'is_archiphoneme' => true
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
