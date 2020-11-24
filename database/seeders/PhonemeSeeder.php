<?php

namespace Database\Seeders;

use App\Models\ClusterFeatureSet;
use App\Models\ConsonantFeatureSet;
use App\Models\Phoneme;
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
            ['name' => 'labial',          'order_key' => 0],
            ['name' => 'interdental',     'order_key' => 1],
            ['name' => 'dental/alveolar', 'order_key' => 2],
            ['name' => 'alveopalatal',    'order_key' => 3],
            ['name' => 'palatal',         'order_key' => 4],
            ['name' => 'velar',           'order_key' => 5],
            ['name' => 'glottal',         'order_key' => 6]
        ]);

        DB::table('consonant_manners')->insert([
            ['name' => 'stop',      'order_key' => 0],
            ['name' => 'fricative', 'order_key' => 1],
            ['name' => 'affricate', 'order_key' => 2],
            ['name' => 'nasal',     'order_key' => 3],
            ['name' => 'liquid',    'order_key' => 4],
            ['name' => 'glide',     'order_key' => 5]
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
            ['id' => 1,  'shape' => 'p',  'place_name' => 'labial',          'manner_name' => 'stop'],
            ['id' => 2,  'shape' => 'ʔ',  'place_name' => 'glottal',         'manner_name' => 'stop'],
            ['id' => 3,  'shape' => 't',  'place_name' => 'dental/alveolar', 'manner_name' => 'stop'],
            ['id' => 4,  'shape' => 'k',  'place_name' => 'velar',           'manner_name' => 'stop'],
            ['id' => 5,  'shape' => 'θ',  'place_name' => 'interdental',     'manner_name' => 'fricative'],
            ['id' => 6,  'shape' => 's',  'place_name' => 'dental/alveolar', 'manner_name' => 'fricative'],
            ['id' => 7,  'shape' => 'ʃ',  'place_name' => 'alveopalatal',    'manner_name' => 'fricative'],
            ['id' => 8,  'shape' => 'h',  'place_name' => 'glottal',         'manner_name' => 'fricative'],
            ['id' => 9,  'shape' => 'tʃ', 'place_name' => 'alveopalatal',    'manner_name' => 'affricate'],
            ['id' => 10, 'shape' => 'm',  'place_name' => 'labial',          'manner_name' => 'nasal'],
            ['id' => 11, 'shape' => 'n',  'place_name' => 'dental/alveolar', 'manner_name' => 'nasal'],
            ['id' => 12, 'shape' => 'w',  'place_name' => 'labial',          'manner_name' => 'glide'],
            ['id' => 13, 'shape' => 'y',  'place_name' => 'palatal',         'manner_name' => 'glide'],
            ['id' => 14, 'shape' => 'r',  'place_name' => 'dental/alveolar', 'manner_name' => 'liquid'],
            ['id' => 15, 'shape' => 'N',  'place_name' => null,              'manner_name' => 'nasal'],
            ['id' => 16, 'shape' => 'x',  'place_name' => 'velar',           'manner_name' => 'fricative']
        ]);

        DB::table('vowel_feature_sets')->insert([
            ['id' => 1, 'shape' => 'i',  'height_name' => 'high', 'backness_name' => 'front', 'length_name' => 'short'],
            ['id' => 2, 'shape' => 'e',  'height_name' => 'low',  'backness_name' => 'front', 'length_name' => 'short'],
            ['id' => 3, 'shape' => 'u',  'height_name' => 'high', 'backness_name' => 'back',  'length_name' => 'short'],
            ['id' => 4, 'shape' => 'o',  'height_name' => 'low',  'backness_name' => 'back',  'length_name' => 'short'],
            ['id' => 5, 'shape' => 'i:', 'height_name' => 'high', 'backness_name' => 'front', 'length_name' => 'long'],
            ['id' => 6, 'shape' => 'e:', 'height_name' => 'low',  'backness_name' => 'front', 'length_name' => 'long'],
            ['id' => 7, 'shape' => 'u:', 'height_name' => 'high', 'backness_name' => 'back',  'length_name' => 'long'],
            ['id' => 8, 'shape' => 'o:', 'height_name' => 'low',  'backness_name' => 'back',  'length_name' => 'long'],
        ]);

        DB::table('cluster_feature_sets')->insert([
            ['id' => 1,  'first_segment_id' => 2,  'second_segment_id' => 1],  // ʔp
            ['id' => 2,  'first_segment_id' => 2,  'second_segment_id' => 4],  // ʔk
            ['id' => 3,  'first_segment_id' => 2,  'second_segment_id' => 3],  // ʔt
            ['id' => 4,  'first_segment_id' => 2,  'second_segment_id' => 9],  // ʔc
            ['id' => 5,  'first_segment_id' => 2,  'second_segment_id' => 6],  // ʔs
            ['id' => 6,  'first_segment_id' => 2,  'second_segment_id' => 7],  // ʔsh
            ['id' => 7,  'first_segment_id' => 2,  'second_segment_id' => 5],  // ʔth
            ['id' => 8,  'first_segment_id' => 2,  'second_segment_id' => 14],  // ʔr
            ['id' => 9,  'first_segment_id' => 8,  'second_segment_id' => 4],  // hk
            ['id' => 10, 'first_segment_id' => 8,  'second_segment_id' => 1],  // hp
            ['id' => 11, 'first_segment_id' => 8,  'second_segment_id' => 3],  // ht
            ['id' => 12, 'first_segment_id' => 8,  'second_segment_id' => 9],  // hc
            ['id' => 13, 'first_segment_id' => 8,  'second_segment_id' => 6],  // hs
            ['id' => 14, 'first_segment_id' => 8,  'second_segment_id' => 7],  // hsh
            ['id' => 15, 'first_segment_id' => 8,  'second_segment_id' => 5],  // hth
            ['id' => 16, 'first_segment_id' => 8,  'second_segment_id' => 14],  // hr
            ['id' => 17, 'first_segment_id' => 8,  'second_segment_id' => 10],  // Hm
            ['id' => 18, 'first_segment_id' => 15, 'second_segment_id' => 4],  // Nk
            ['id' => 19, 'first_segment_id' => 15, 'second_segment_id' => 1],  // Np
            ['id' => 20, 'first_segment_id' => 15, 'second_segment_id' => 3],  // Nt
            ['id' => 21, 'first_segment_id' => 15, 'second_segment_id' => 9],  // Nc
            ['id' => 22, 'first_segment_id' => 15, 'second_segment_id' => 6],  // Ns
            ['id' => 23, 'first_segment_id' => 15, 'second_segment_id' => 7],  // Nsh
            ['id' => 24, 'first_segment_id' => 15, 'second_segment_id' => 5],  // Nth
            ['id' => 25, 'first_segment_id' => 15, 'second_segment_id' => 14],  // Nr
            ['id' => 26, 'first_segment_id' => 7,  'second_segment_id' => 4],  // shk
            ['id' => 27, 'first_segment_id' => 7,  'second_segment_id' => 1],  // shp
            ['id' => 28, 'first_segment_id' => 7,  'second_segment_id' => 3],  // sht
            ['id' => 29, 'first_segment_id' => 5,  'second_segment_id' => 4],  // thk
            ['id' => 30, 'first_segment_id' => 5,  'second_segment_id' => 1],  // thp
            ['id' => 31, 'first_segment_id' => 14, 'second_segment_id' => 4],  // rk
        ]);

        DB::table('phonemes')->insert([
            [
                'id' => 2,
                'shape' => 'ʔ',
                'slug' => 'ʔ',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 2,
                'is_marginal' => true
            ],
            [
                'id' => 18,
                'shape' => 'h',
                'slug' => 'h',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 8,
                'is_marginal' => false
            ],
            [
                'id' => 14,
                'shape' => 'k',
                'slug' => 'k',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 4,
                'is_marginal' => false
            ],
            [
                'id' => 1,
                'shape' => 'p',
                'slug' => 'p',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 1,
                'is_marginal' => false
            ],
            [
                'id' => 13,
                'shape' => 't',
                'slug' => 't',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 3,
                'is_marginal' => false
            ],
            [
                'id' => 19,
                'shape' => 'č',
                'slug' => 'č',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 9,
                'is_marginal' => false
            ],
            [
                'id' => 16,
                'shape' => 's',
                'slug' => 's',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 6,
                'is_marginal' => false
            ],
            [
                'id' => 17,
                'shape' => 'š',
                'slug' => 'š',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 7,
                'is_marginal' => false
            ],
            [
                'id' => 15,
                'shape' => 'θ',
                'slug' => 'θ',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 5,
                'is_marginal' => false
            ],
            [
                'id' => 24,
                'shape' => 'r',
                'slug' => 'r',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 14,
                'is_marginal' => false
            ],
            [
                'id' => 20,
                'shape' => 'm',
                'slug' => 'm',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 10,
                'is_marginal' => false
            ]
        ]);

        DB::table('phonemes')->insert([
            [
                'id' => 3,
                'shape' => 'i',
                'slug' => 'i',
                'language_code' => 'PA',
                'featureable_type' => VowelFeatureSet::class,
                'featureable_id' => 1,
                'is_marginal' => false
            ],
            [
                'id' => 4,
                'shape' => 'xp',
                'slug' => 'xp',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 1,
                'is_marginal' => false
            ],
            [
                'id' => 5,
                'shape' => 'p',
                'slug' => 'p',
                'language_code' => 'C',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 1,
                'is_marginal' => false
            ],
            [
                'id' => 6,
                'shape' => 'e',
                'slug' => 'e',
                'language_code' => 'PA',
                'featureable_type' => VowelFeatureSet::class,
                'featureable_id' => 2,
                'is_marginal' => false
            ],
            [
                'id' => 7,
                'shape' => 'o',
                'slug' => 'o',
                'language_code' => 'PA',
                'featureable_type' => VowelFeatureSet::class,
                'featureable_id' => 3,
                'is_marginal' => false
            ],
            [
                'id' => 8,
                'shape' => 'a',
                'slug' => 'a',
                'language_code' => 'PA',
                'featureable_type' => VowelFeatureSet::class,
                'featureable_id' => 4,
                'is_marginal' => false
            ],
            [
                'id' => 9,
                'shape' => 'i·',
                'slug' => 'i·',
                'language_code' => 'PA',
                'featureable_type' => VowelFeatureSet::class,
                'featureable_id' => 5,
                'is_marginal' => false
            ],
            [
                'id' => 10,
                'shape' => 'e·',
                'slug' => 'e·',
                'language_code' => 'PA',
                'featureable_type' => VowelFeatureSet::class,
                'featureable_id' => 6,
                'is_marginal' => false
            ],
            [
                'id' => 11,
                'shape' => 'o·',
                'slug' => 'o·',
                'language_code' => 'PA',
                'featureable_type' => VowelFeatureSet::class,
                'featureable_id' => 7,
                'is_marginal' => false
            ],
            [
                'id' => 12,
                'shape' => 'a·',
                'slug' => 'a·',
                'language_code' => 'PA',
                'featureable_type' => VowelFeatureSet::class,
                'featureable_id' => 8,
                'is_marginal' => false
            ],
            [
                'id' => 21,
                'shape' => 'n',
                'slug' => 'n',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 11,
                'is_marginal' => false
            ],
            [
                'id' => 22,
                'shape' => 'w',
                'slug' => 'w',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 12,
                'is_marginal' => false
            ],
            [
                'id' => 23,
                'shape' => 'y',
                'slug' => 'y',
                'language_code' => 'PA',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 13,
                'is_marginal' => false
            ],
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

        DB::table('phonemes')->insert([
            [
                'id' => 26,
                'shape' => 'k',
                'slug' => 'k',
                'language_code' => 'PAGV',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 4
            ],
            [
                'id' => 27,
                'shape' => 'č',
                'slug' => 'č',
                'language_code' => 'Ar',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 9
            ],
            [
                'id' => 28,
                'shape' => 'h',
                'slug' => 'h',
                'language_code' => 'Ch',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 8,
            ],
            [
                'id' => 29,
                'shape' => 'p',
                'slug' => 'p',
                'language_code' => 'Ch',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 1
            ],
            [
                'id' => 30,
                'shape' => 'hp',
                'slug' => 'hp',
                'language_code' => 'Ch',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 10
            ],
            [
                'id' => 31,
                'shape' => '∅',
                'slug' => '∅',
                'language_code' => 'Ch',
                'featureable_type' => null,
                'featureable_id' => null
            ]
        ]);

        DB::table('phonemes')->insert([
            [
                'id' => 32,
                'shape' => 'N',
                'slug' => 'N',
                'language_code' => 'Mun',
                'featureable_type' => ConsonantFeatureSet::class,
                'featureable_id' => 15,
                'is_archiphoneme' => true
            ]
        ]);

        DB::table('phonemes')->insert([
            [
                'shape' => 'xk',
                'slug' => 'xk',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 2
            ],
            [
                'shape' => 'ʔt',
                'slug' => 'ʔt',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 3
            ],
            [
                'shape' => 'ʔč',
                'slug' => 'ʔč',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 4
            ],
            [
                'shape' => 'ʔs',
                'slug' => 'ʔs',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 5
            ],
            [
                'shape' => 'ʔš',
                'slug' => 'ʔš',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 6
            ],
            [
                'shape' => 'ʔθ',
                'slug' => 'ʔθ',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 7
            ],
            [
                'shape' => 'ʔr',
                'slug' => 'ʔr',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 8
            ],
            [
                'shape' => 'hp',
                'slug' => 'hp',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 9
            ],
            [
                'shape' => 'hk',
                'slug' => 'hk',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 10
            ],
            [
                'shape' => 'ht',
                'slug' => 'ht',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 11
            ],
            [
                'shape' => 'hč',
                'slug' => 'hč',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 12
            ],
            [
                'shape' => 'hs',
                'slug' => 'hs',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 13
            ],
            [
                'shape' => 'hš',
                'slug' => 'hš',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 14
            ],
            [
                'shape' => 'hθ',
                'slug' => 'hθ',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 15
            ],
            [
                'shape' => 'Hm',
                'slug' => 'Hm',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 17
            ],
            [
                'shape' => 'nk',
                'slug' => 'nk',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 18
            ],
            [
                'shape' => 'mp',
                'slug' => 'mp',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 19
            ],
            [
                'shape' => 'nt',
                'slug' => 'nt',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 20
            ],
            [
                'shape' => 'nč',
                'slug' => 'nč',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 21
            ],
            [
                'shape' => 'ns',
                'slug' => 'ns',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 22
            ],
            [
                'shape' => 'nš',
                'slug' => 'nš',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 23
            ],
            [
                'shape' => 'nθ',
                'slug' => 'nθ',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 24
            ],
            [
                'shape' => 'nr',
                'slug' => 'nr',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 25
            ],
            [
                'shape' => 'šk',
                'slug' => 'šk',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 26
            ],
            [
                'shape' => 'šp',
                'slug' => 'šp',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 27
            ],
            [
                'shape' => 'θk',
                'slug' => 'θk',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 29
            ],
            [
                'shape' => 'θp',
                'slug' => 'θp',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 30
            ],
            [
                'shape' => 'rk',
                'slug' => 'rk',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 31
            ],
        ]);

        DB::table('phonemes')->insert([
            [
                'shape' => 'hr',
                'slug' => 'hr',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 16,
                'is_marginal' => true
            ],
            [
                'shape' => 'št',
                'slug' => 'št',
                'language_code' => 'PA',
                'featureable_type' => ClusterFeatureSet::class,
                'featureable_id' => 28,
                'is_marginal' => true
            ]
        ]);

        DB::table('reflexes')->insert([
            [
                'phoneme_id' => 1,  // PA p
                'reflex_id' => 5    // C p
            ],
            [
                'phoneme_id' => 1,  // PA p
                'reflex_id' => 26   // PAGV k
            ],
            [
                'phoneme_id' => 15,  // PA θ
                'reflex_id' => 26   // PAGV k
            ],
            [
                'phoneme_id' => Phoneme::where('shape', 'hp')->where('language_code', 'PA')->first()->id,
                'reflex_id' => 26   // PAGV k
            ],
            [
                'phoneme_id' => 26,  // PAGV k
                'reflex_id' => 27    // Ar č
            ],

            [
                'phoneme_id' => 1,  // PA p
                'reflex_id' => 29    // Ch p
            ],
            [
                'phoneme_id' => 18,  // PA h
                'reflex_id' => 28    // Ch h
            ],
            [
                'phoneme_id' => Phoneme::where('shape', 'mp')->first()->id,
                'reflex_id' => 30    // Ch hp
            ],
            [
                'phoneme_id' => Phoneme::where('shape', 'hp')->where('language_code', 'PA')->first()->id,
                'reflex_id' => 30    // Ch hp
            ],
            [
                'phoneme_id' => 1,  // PA p
                'reflex_id' => 30    // Ch hp
            ],
            [
                'phoneme_id' => Phoneme::where('shape', 'hs')->where('language_code', 'PA')->first()->id,
                'reflex_id' => 28    // Ch h
            ],
            [
                'phoneme_id' => 1,  // PA p
                'reflex_id' => 31    // Ch ∅
            ],
            [
                'phoneme_id' => 25,  // PA N
                'reflex_id' => 32 // Mun N
            ],
        ]);
    }
}
