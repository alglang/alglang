<?php

namespace Tests\Unit\Http\Livewire\Collections;

use App\Http\Livewire\Collections\Phonemes;
use App\Models\ConsonantManner;
use App\Models\ConsonantPlace;
use App\Models\Language;
use App\Models\Phoneme;
use App\Models\VowelBackness;
use App\Models\VowelHeight;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhonemesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_phonemes()
    {
        $language = Language::factory()->create();

        Phoneme::factory()->create(['language_code' => $language, 'shape' => 'phonx']);
        Phoneme::factory()->create(['language_code' => $language, 'shape' => 'phony']);

        $view = $this->livewire(Phonemes::class, ['model' => $language]);

        $view->assertSee('phonx');
        $view->assertSee('phony');
    }

    /** @test */
    public function backnesses_are_displayed_in_order()
    {
        $language = Language::factory()->create();

        Phoneme::factory()->vowel([
            'backness_name' => VowelBackness::factory()->create([
                'name' => 'midfront',
                'order_key' => 2
            ]),
        ])->create(['language_code' => $language]);
        Phoneme::factory()->vowel([
            'backness_name' => VowelBackness::factory()->create([
                'name' => 'backest',
                'order_key' => 4
            ])
        ])->create(['language_code' => $language]);
        Phoneme::factory()->vowel([
            'backness_name' => VowelBackness::factory()->create([
                'name' => 'frontest',
                'order_key' => 1
            ])
        ])->create(['language_code' => $language]);
        Phoneme::factory()->vowel([
            'backness_name' => VowelBackness::factory()->create([
                'name' => 'midback',
                'order_key' => 3
            ])
        ])->create(['language_code' => $language]);

        $view = $this->livewire(Phonemes::class, ['model' => $language]);

        $view->assertSeeInOrder(['frontest', 'midfront', 'midback', 'backest']);
    }

    /** @test */
    public function heights_are_displayed_in_order()
    {
        $language = Language::factory()->create();

        Phoneme::factory()->vowel([
            'height_name' => VowelHeight::factory()->create([
                'name' => 'midhigh',
                'order_key' => 2
            ]),
        ])->create(['language_code' => $language]);
        Phoneme::factory()->vowel([
            'height_name' => VowelHeight::factory()->create([
                'name' => 'lowest',
                'order_key' => 4
            ])
        ])->create(['language_code' => $language]);
        Phoneme::factory()->vowel([
            'height_name' => VowelHeight::factory()->create([
                'name' => 'highest',
                'order_key' => 1
            ])
        ])->create(['language_code' => $language]);
        Phoneme::factory()->vowel([
            'height_name' => VowelHeight::factory()->create([
                'name' => 'midlow',
                'order_key' => 3
            ])
        ])->create(['language_code' => $language]);

        $view = $this->livewire(Phonemes::class, ['model' => $language]);

        $view->assertSeeInOrder(['highest', 'midhigh', 'midlow', 'lowest']);
    }

    /** @test */
    public function manners_are_displayed_in_order()
    {
        $language = Language::factory()->create();

        Phoneme::factory()->consonant([
            'manner_name' => ConsonantManner::factory()->create([
                'name' => 'MANNER1',
                'order_key' => 2
            ]),
        ])->create(['language_code' => $language]);
        Phoneme::factory()->consonant([
            'manner_name' => ConsonantManner::factory()->create([
                'name' => 'MANNER2',
                'order_key' => 4
            ])
        ])->create(['language_code' => $language]);
        Phoneme::factory()->consonant([
            'manner_name' => ConsonantManner::factory()->create([
                'name' => 'MANNER3',
                'order_key' => 1
            ])
        ])->create(['language_code' => $language]);
        Phoneme::factory()->consonant([
            'manner_name' => ConsonantManner::factory()->create([
                'name' => 'MANNER4',
                'order_key' => 3
            ])
        ])->create(['language_code' => $language]);

        $view = $this->livewire(Phonemes::class, ['model' => $language]);

        $view->assertSeeInOrder(['MANNER3', 'MANNER1', 'MANNER4', 'MANNER2']);
    }

    /** @test */
    public function places_are_displayed_in_order()
    {
        $language = Language::factory()->create();

        Phoneme::factory()->consonant([
            'place_name' => ConsonantPlace::factory()->create([
                'name' => 'PLACE1',
                'order_key' => 2
            ]),
        ])->create(['language_code' => $language]);
        Phoneme::factory()->consonant([
            'place_name' => ConsonantPlace::factory()->create([
                'name' => 'PLACE2',
                'order_key' => 4
            ])
        ])->create(['language_code' => $language]);
        Phoneme::factory()->consonant([
            'place_name' => ConsonantPlace::factory()->create([
                'name' => 'PLACE3',
                'order_key' => 1
            ])
        ])->create(['language_code' => $language]);
        Phoneme::factory()->consonant([
            'place_name' => ConsonantPlace::factory()->create([
                'name' => 'PLACE4',
                'order_key' => 3
            ])
        ])->create(['language_code' => $language]);

        $view = $this->livewire(Phonemes::class, ['model' => $language]);

        $view->assertSeeInOrder(['PLACE3', 'PLACE1', 'PLACE4', 'PLACE2']);
    }

    /** @test */
    public function it_shows_archiphonemes_if_there_are_any()
    {
        $language = Language::factory()->create();

        Phoneme::factory()->consonant()->create([
            'shape' => 'ARCH1',
            'language_code' => $language,
            'is_archiphoneme' => true
        ]);
        Phoneme::factory()->vowel()->create([
            'shape' => 'ARCH2',
            'language_code' => $language,
            'is_archiphoneme' => true
        ]);

        $view = $this->livewire(Phonemes::class, ['model' => $language]);

        $view->assertSeeInOrder(['Archiphonemes', 'ARCH1', 'ARCH2']);
    }

    /**
     * @test
     * @dataProvider archiphonemeFeaturesProvider
     */
    public function it_shows_archiphoneme_features($factory, string $target)
    {
        $language = Language::factory()->create();

        $factory->create([
            'shape' => 'ARCHY',
            'language_code' => $language,
            'is_archiphoneme' => true
        ]);

        $view = $this->livewire(Phonemes::class, ['model' => $language]);

        $view->assertSeeInOrder(['Archiphonemes', $target, 'ARCHY']);
    }

    public function archiphonemeFeaturesProvider(): array
    {
        return [
            [
                Phoneme::factory()->consonant(['manner_name' => null, 'place_name' => 'FOO_PLACE']),
                'FOO_PLACE'
            ],
            [
                Phoneme::factory()->consonant(['manner_name' => 'FOO_MANNER', 'place_name' => null]),
                'FOO_MANNER'
            ],
            [
                Phoneme::factory()->vowel(['backness_name' => null, 'height_name' => 'FOO_HEIGHT']),
                'FOO_HEIGHT'
            ],
            [
                Phoneme::factory()->vowel(['backness_name' => 'FOO_BACKNESS', 'height_name' => null]),
                'FOO_BACKNESS'
            ]
        ];
    }

    /** @test */
    public function it_doesnt_show_archiphonemes_if_there_arent_any()
    {
        $language = Language::factory()->create();

        $view = $this->livewire(Phonemes::class, ['model' => $language]);

        $view->assertDontSee('Archiphonemes');
    }
}
