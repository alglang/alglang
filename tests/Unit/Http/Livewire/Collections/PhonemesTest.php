<?php

namespace Tests\Unit\Http\Livewire\Collections;

use App\Http\Livewire\Collections\Phonemes;
use App\Models\ConsonantManner;
use App\Models\ConsonantPlace;
use App\Models\Language;
use App\Models\Phoneme;
use App\Models\Reflex;
use App\Models\VowelBackness;
use App\Models\VowelHeight;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhonemesTest extends TestCase
{
    use RefreshDatabase;

    public Language $pa;
    public Phoneme $paConsonant;
    public Phoneme $paVowel;

    public function setUp(): void
    {
        parent::setUp();

        $this->pa = Language::factory()->create(['code' => 'PA']);
        $this->paConsonant = Phoneme::factory()->consonant()->create(['language_code' => $this->pa, 'shape' => 'parentx']);
        $this->paVowel = Phoneme::factory()->vowel()->create(['language_code' => $this->pa, 'shape' => 'parentu']);
    }

    /** @test */
    public function it_shows_vowels()
    {
        $language = Language::factory()->create();

        Phoneme::factory()->vowel()->create(['language_code' => $language, 'shape' => 'phonx']);

        $view = $this->livewire(Phonemes::class, ['model' => $language]);

        $view->assertSeeInOrder(['Vowel inventory', 'phonx']);
    }

    /** @test */
    public function it_does_not_show_the_vowel_section_when_there_are_no_vowels()
    {
        $language = Language::factory()->create();
        $this->assertEquals(0, $language->vowels()->count());

        $view = $this->livewire(Phonemes::class, ['model' => $language]);

        $view->assertDontSee('Vowel inventory');
    }

    /** @test */
    public function it_shows_consonants()
    {
        $language = Language::factory()->create();

        Phoneme::factory()->consonant()->create(['language_code' => $language, 'shape' => 'phonx']);

        $view = $this->livewire(Phonemes::class, ['model' => $language]);

        $view->assertSeeInOrder(['Consonant inventory', 'phonx']);
    }

    /** @test */
    public function it_does_not_show_the_consonant_section_when_there_are_no_consonants()
    {
        $language = Language::factory()->create();
        $this->assertEquals(0, $language->consonants()->count());

        $view = $this->livewire(Phonemes::class, ['model' => $language]);

        $view->assertDontSee('Consonant inventory');
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

    /** @test */
    public function it_shows_proto_algonquian_vowel_reflexes()
    {
        $language = Language::factory()->create();
        $phoneme = Phoneme::factory()->vowel()->create(['language_code' => $language, 'shape' => 'childu']);
        Reflex::factory()->create([
            'phoneme_id' => $this->paVowel,
            'reflex_id' => $phoneme
        ]);

        $view = $this->livewire(Phonemes::class, ['model' => $language]);

        $view->assertSeeInOrder(['Reflexes of Proto-Algonquian vowels', 'parentu', '>', 'childu'], false);
    }

    /** @test */
    public function it_shows_proto_algonquian_consonant_reflexes()
    {
        $language = Language::factory()->create();
        $phoneme = Phoneme::factory()->consonant()->create(['language_code' => $language, 'shape' => 'childx']);
        Reflex::factory()->create([
            'phoneme_id' => $this->paConsonant,
            'reflex_id' => $phoneme
        ]);

        $view = $this->livewire(Phonemes::class, ['model' => $language]);

        $view->assertSeeInOrder(['Reflexes of Proto-Algonquian consonants', 'parentx', '>', 'childx'], false);
    }

    /** @test */
    public function consonant_reflexes_can_be_clusters()
    {
        $language = Language::factory()->create();
        $cluster = Phoneme::factory()->cluster()->create(['language_code' => $language, 'shape' => 'childx']);
        Reflex::factory()->create([
            'phoneme_id' => $this->paConsonant,
            'reflex_id' => $cluster
        ]);

        $view = $this->livewire(Phonemes::class, ['model' => $language]);

        $view->assertSeeInOrder(['Reflexes of Proto-Algonquian consonants', 'parentx', '>', 'childx'], false);
    }

    /** @test */
    public function it_does_not_show_proto_algonquian_vowel_reflexes_if_it_has_none()
    {
        $language = Language::factory()->create();

        $view = $this->livewire(Phonemes::class, ['model' => $language]);

        $view->assertDontSee('Reflexes of Proto-Algonquian vowels');
    }

    /** @test */
    public function it_does_not_show_proto_algonquian_consonant_reflexes_if_it_has_none()
    {
        $language = Language::factory()->create();

        $view = $this->livewire(Phonemes::class, ['model' => $language]);

        $view->assertDontSee('Reflexes of Proto-Algonquian consonants');
    }

    /** @test */
    public function it_shows_missing_proto_algonquian_vowel_reflexes_if_it_has_at_least_one_reflex()
    {
        $language = Language::factory()->create();

        Reflex::factory()->create([
            'phoneme_id' => Phoneme::factory()->vowel()->create(['language_code' => 'PA']),
            'reflex_id' => Phoneme::factory()->vowel()->create(['language_code' => $language])
        ]);

        $view = $this->livewire(Phonemes::class, ['model' => $language]);

        $view->assertSeeInOrder(['Reflexes of Proto-Algonquian vowels', 'parentu', '>', '?'], false);
    }

    /** @test */
    public function it_shows_missing_proto_algonquian_consonant_reflexes_if_it_has_at_least_one_reflex()
    {
        $language = Language::factory()->create();

        Reflex::factory()->create([
            'phoneme_id' => Phoneme::factory()->consonant()->create(['language_code' => 'PA']),
            'reflex_id' => Phoneme::factory()->consonant()->create(['language_code' => $language])
        ]);

        $view = $this->livewire(Phonemes::class, ['model' => $language]);

        $view->assertSeeInOrder(['Reflexes of Proto-Algonquian consonants', 'parentx', '>', '?'], false);
    }

    /** @test */
    public function proto_algonquian_reflexes_are_not_shown_on_the_proto_algonquian_page()
    {
        $view = $this->livewire(Phonemes::class, ['model' => $this->pa]);

        $view->assertDontSee('Reflexes');
    }
}
