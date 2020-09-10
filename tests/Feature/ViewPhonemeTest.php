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
    public function it_shows_its_shape_if_it_has_one()
    {
        $phoneme = Phoneme::factory()->create(['shape' => 'xyz']);

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertSeeInOrder(['Orthographic transcription', 'xyz']);
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
        $response->assertSeeInOrder(['IPA transcription', '/xyz/']);
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
        $response->assertSeeInOrder(['Phoneme details', 'Test Language']);
    }

    /** @test */
    public function it_shows_its_type()
    {
        $phoneme = Phoneme::factory()->vowel()->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertSee('vowel');
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
    public function it_does_not_show_consonant_features_if_it_is_a_vowel()
    {
        $phoneme = Phoneme::factory()->vowel()->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertDontSee('Place');
        $response->assertDontSee('Manner');
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
