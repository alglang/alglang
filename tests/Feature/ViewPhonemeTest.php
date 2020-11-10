<?php

namespace Tests\Feature;

use App\Models\ConsonantManner;
use App\Models\ConsonantPlace;
use App\Models\Language;
use App\Models\Phoneme;
use App\Models\VowelHeight;
use App\Models\VowelBackness;
use App\Models\VowelLength;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewPhonemeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_the_correct_view()
    {
        $phoneme = Phoneme::factory()->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertViewIs('phonemes.show');
        $response->assertViewHas('phoneme', $phoneme);
        $response->assertSee($phoneme->formatted_shape, false);
    }

    /** @test */
    public function it_matches_views_case_sensitively()
    {
        $language = Language::factory()->create();
        Phoneme::factory()->create([
            'slug' => 'n',
            'language_code' => $language
        ]);
        $phoneme = Phoneme::factory()->create([
            'slug' => 'N',
            'language_code' => $language
        ]);

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertViewHas('phoneme', $phoneme);
    }

    /** @test */
    public function it_displays_the_correct_type_for_a_vowel()
    {
        $phoneme = Phoneme::factory()->vowel()->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertSee('Vowel details');
    }

    /** @test */
    public function it_displays_the_correct_type_for_a_consonant()
    {
        $phoneme = Phoneme::factory()->consonant()->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertSee('Consonant details');
    }

    /** @test */
    public function it_displays_the_correct_type_for_a_cluster()
    {
        $phoneme = Phoneme::factory()->cluster()->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertSee('Cluster details');
    }

    /** @test */
    public function it_adds_archiphoneme_to_the_title_if_it_is_an_archiphoneme()
    {
        $phoneme = Phoneme::factory()->consonant()->create(['is_archiphoneme' => true]);

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertSee('Archiphoneme Consonant details');
    }

    /** @test */
    public function it_represents_marginality()
    {
        $phoneme = Phoneme::factory()->create([
            'shape' => 'xyz',
            'is_marginal' => true
        ]);

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertSee('(<i>xyz</i>)', false);
    }

    /** @test */
    public function it_shows_its_shape_if_it_has_one()
    {
        $phoneme = Phoneme::factory()->create(['shape' => 'xyz']);

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertSeeInOrder(['Orthographic transcription', '<i>xyz</i>'], false);
    }

    /** @test */
    public function it_does_not_show_an_orthographic_transcription_if_it_has_no_shape()
    {
        $phoneme = Phoneme::factory()->create(['shape' => null, 'ipa' => 'xyz']);

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertDontSee('Orthographic transcription');
    }

    /** @test */
    public function it_shows_its_ipa_shape_if_it_has_one()
    {
        $phoneme = Phoneme::factory()->create(['ipa' => 'xyz']);

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertSeeInOrder(['IPA transcription', "/<i>xyz</i>/"], false);
    }

    /** @test */
    public function it_does_not_show_an_ipa_transcription_if_it_does_not_have_one()
    {
        $phoneme = Phoneme::factory()->create(['shape' => 'xyz', 'ipa' => null]);

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertDontSee('IPA transcription');
    }

    /** @test */
    public function it_shows_its_language()
    {
        $phoneme = Phoneme::factory()->forLanguage(['name' => 'Test Language'])->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertSeeInOrder(['details', 'Test Language']);
    }

    /** @test */
    public function it_shows_vowel_features_if_it_is_a_vowel()
    {
        $phoneme = Phoneme::factory()->vowel([
            'height_name' => VowelHeight::factory()->create(['name' => 'high']),
            'backness_name' => VowelBackness::factory()->create(['name' => 'front']),
            'length_name' => VowelLength::factory()->create(['name' => 'short'])
        ])->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertSeeInOrder(['Height', 'high']);
        $response->assertSeeInOrder(['Backness', 'front']);
        $response->assertSeeInOrder(['Length', 'short']);
    }

    /** @test */
    public function it_handles_archiphoneme_vowels_that_have_height_assimilation()
    {
        $phoneme = Phoneme::factory()->vowel(['height_name' => null])->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertSeeInOrder(['Height', 'varies']);
    }

    /** @test */
    public function it_handles_archiphoneme_vowels_that_have_backness_assimilation()
    {
        $phoneme = Phoneme::factory()->vowel(['backness_name' => null])->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertSeeInOrder(['Backness', 'varies']);
    }

    /** @test */
    public function it_handles_archiphoneme_vowels_that_have_length_assimilation()
    {
        $phoneme = Phoneme::factory()->vowel(['length_name' => null])->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertSeeInOrder(['Length', 'varies']);
    }

    /** @test */
    public function it_shows_consonant_features_if_it_is_a_vowel()
    {
        $phoneme = Phoneme::factory()->consonant([
            'place_name' => ConsonantPlace::factory()->create(['name' => 'labial']),
            'manner_name' => ConsonantManner::factory()->create(['name' => 'stop'])
        ])->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertSeeInOrder(['Place', 'labial']);
        $response->assertSeeInOrder(['Manner', 'stop']);
    }

    /** @test */
    public function it_handles_archiphoneme_consonants_that_have_place_assimilation()
    {
        $phoneme = Phoneme::factory()->consonant(['place_name' => null])->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertSeeInOrder(['Place', 'varies']);
    }

    /** @test */
    public function it_handles_archiphoneme_consonants_that_have_manner_assimilation()
    {
        $phoneme = Phoneme::factory()->consonant(['manner_name' => null])->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertSeeInOrder(['Manner', 'varies']);
    }

    /** @test */
    public function it_shows_cluster_features_if_it_is_a_cluster()
    {
        $this->withoutExceptionHandling();
        $phoneme = Phoneme::factory()->cluster([
            'first_segment_id' => Phoneme::factory()->consonant()->create(['shape' => 'x']),
            'second_segment_id' => Phoneme::factory()->consonant()->create(['shape' => 'y'])
        ])->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertSeeInOrder(['First segment', 'x']);
        $response->assertSeeInOrder(['Second segment', 'y']);
    }

    /** @test */
    public function it_does_not_show_vowel_features_if_it_is_a_consonant()
    {
        $phoneme = Phoneme::factory()->consonant()->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertDontSee('Height');
        $response->assertDontSee('Backness');
        $response->assertDontSee('Length');
    }

    /** @test */
    public function it_does_not_show_vowel_features_if_it_is_a_cluster()
    {
        $phoneme = Phoneme::factory()->cluster()->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertDontSee('Height');
        $response->assertDontSee('Backness');
        $response->assertDontSee('Length');
    }

    /** @test */
    public function it_does_not_show_consonant_features_if_it_is_a_vowel()
    {
        $phoneme = Phoneme::factory()->vowel()->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertDontSee('Place');
        $response->assertDontSee('Manner');
    }

    /** @test */
    public function it_does_not_show_consonant_feature_if_it_is_a_cluster()
    {
        $phoneme = Phoneme::factory()->cluster()->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertDontSee('Place');
        $response->assertDontSee('Manner');
    }

    /** @test */
    public function it_does_not_show_cluster_features_if_it_is_a_vowel()
    {
        $phoneme = Phoneme::factory()->vowel()->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertDontSee('First segment');
        $response->assertDontSee('Second segment');
    }

    /** @test */
    public function it_does_not_show_cluster_features_if_it_is_a_consonant()
    {
        $phoneme = Phoneme::factory()->consonant()->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertDontSee('First segment');
        $response->assertDontSee('Second segment');
    }

    /** @test */
    public function it_shows_its_allophones()
    {
        $phoneme = Phoneme::factory()
            ->hasAllophones(['shape' => 'y'])
            ->create(['shape' => 'x']);

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertSee('Allophones');
        $response->assertSee('/x/ → [y]');
    }

    /** @test */
    public function it_does_not_show_allophones_if_it_has_none()
    {
        $phoneme = Phoneme::factory()->create();
        $this->assertCount(0, $phoneme->allophones);

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertDontSee('Allophones');
    }

    /** @test */
    public function it_shows_its_sources_if_it_has_any()
    {
        $phoneme = Phoneme::factory()->hasSources(2, ['author' => 'Doe'])->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertSee('Sources');
        $response->assertSee('Doe');
    }

    /** @test */
    public function it_shows_no_sources_if_it_has_none()
    {
        $phoneme = Phoneme::factory()->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertDontSee('Sources');
    }
}
