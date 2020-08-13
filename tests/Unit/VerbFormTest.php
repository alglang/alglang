<?php

namespace Tests\Unit;

use App\Language;
use App\VerbForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerbFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_url()
    {
        $language = factory(Language::class)->create(['algo_code' => 'PA']);
        $form = factory(VerbForm::class)->create([
            'shape' => 'V-test',
            'language_id' => $language->id
        ]);
        $this->assertEquals('/languages/pa/verb-forms/V-test', $form->url);
    }
}
