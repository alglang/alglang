<?php

namespace Tests\Unit;

use App\Language;
use App\VerbForm;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestVerbForm extends TestCase
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
}
