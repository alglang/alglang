<?php

namespace Tests\Feature;

use App\Language;
use App\VerbForm;
use App\VerbClass;
use App\VerbOrder;
use App\VerbMode;
use App\VerbFeature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewVerbFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_verb_form_can_be_viewed()
    {
        $language = factory(Language::class)->create(['name' => 'Test Language']);
        $class = factory(VerbClass::class)->create(['abv' => 'TA']);
        $order = factory(VerbOrder::class)->create(['name' => 'Conjunct']);
        $mode = factory(VerbMode::class)->create(['name' => 'Indicative']);
        $subject = factory(VerbFeature::class)->create(['name' => '3s']);
        $verbForm = VerbForm::create([
            'shape' => 'V-test',
            'language_id' => $language->id,

            'class_id' => $class->id,
            'order_id' => $order->id,
            'mode_id' => $mode->id,
            'subject_id' => $subject->id,

            'historical_notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam',
            'allomorphy_notes' => 'the quick brown fox jumps over the lazy dog',
            'usage_notes' => 'Would you be in any way offended if I said that you were the visible personification of absolute perfection?',
            'private_notes' => ';jkals;jfkld;sjfkasd;jfklsafkl;jkaslf;'
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('Test Language');
        $response->assertSee('V-test');
        $response->assertSee('TA');
        $response->assertSee('Conjunct');
        $response->assertSee('Indicative');
        $response->assertSee('3s');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
        $response->assertSee('the quick brown fox jumps over the lazy dog');
        $response->assertSee('Would you be in any way offended if I said that you were the visible personification of absolute perfection?');
        $response->assertSee(';jkals;jfkld;sjfkasd;jfklsafkl;jkaslf;');
    }
}
