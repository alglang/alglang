<?php

namespace Tests\Unit;

use App\Language;
use App\VerbFeature;
use App\VerbForm;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VerbFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_url()
    {
        $language = factory(Language::class)->create(['algo_code' => 'PA']);
        $verbForm = factory(VerbForm::class)->create([
            'shape' => 'V-test',
            'language_id' => $language->id
        ]);
        $this->assertEquals('/languages/pa/verb-forms/v-test', $verbForm->url);
    }

    /** @test */
    public function language_is_always_eager_loaded()
    {
        factory(VerbForm::class)->create();
        $verbForm = VerbForm::first();

        $this->assertTrue($verbForm->relationLoaded('language'));
    }

    /** @test */
    public function it_renders_its_subject_as_its_argument_string_when_there_are_no_other_features()
    {
        $verbForm = factory(VerbForm::class)->make([
            'subject_id' => factory(VerbFeature::class)->create(['name' => '3s'])->id
        ]);

        $this->assertEquals('3s', $verbForm->argument_string);
    }

    /** @test */
    public function it_renders_its_primary_object_with_an_arrow_in_its_argument_string()
    {
        $verbForm = factory(VerbForm::class)->make([
            'subject_id' => factory(VerbFeature::class)->create(['name' => '3s'])->id,
            'primary_object_id' => factory(VerbFeature::class)->create(['name' => '1p'])->id
        ]);

        $this->assertEquals('3s→1p', $verbForm->argument_string);
    }

    /** @test */
    public function it_renders_its_secondary_object_with_a_plus_in_its_argument_string()
    {
        $verbForm = factory(VerbForm::class)->make([
            'subject_id' => factory(VerbFeature::class)->create(['name' => '3s'])->id,
            'secondary_object_id' => factory(VerbFeature::class)->create(['name' => '1p'])->id
        ]);

        $this->assertEquals('3s+1p', $verbForm->argument_string);
    }
}
