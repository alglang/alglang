<?php

namespace Tests\Unit;

use App\Models\Language;
use App\Models\NominalForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NominalFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_url()
    {
        $language = factory(Language::class)->create(['code' => 'PA']);
        $form = factory(NominalForm::class)->create([
            'shape' => 'N-test',
            'language_code' => $language->code
        ]);
        $this->assertEquals('/languages/PA/nominal-forms/N-test', $form->url);
    }
}
