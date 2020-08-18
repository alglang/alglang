<?php

namespace Tests\Unit;

use App\Language;
use App\NominalForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NominalFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_url()
    {
        $language = factory(Language::class)->create(['algo_code' => 'PA']);
        $form = factory(NominalForm::class)->create([
            'shape' => 'N-test',
            'language_id' => $language->id
        ]);
        $this->assertEquals('/languages/pa/nominal-forms/N-test', $form->url);
    }
}
