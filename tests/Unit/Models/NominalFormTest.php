<?php

namespace Tests\Unit\Models;

use App\Models\Language;
use App\Models\NominalForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NominalFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_url()
    {
        $language = Language::factory()->create(['code' => 'PA']);
        $form = NominalForm::factory()->create([
            'shape' => 'N-test',
            'language_code' => $language->code
        ]);
        $this->assertEquals('/languages/PA/nominal-forms/N-test', $form->url);
    }
}
