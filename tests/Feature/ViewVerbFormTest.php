<?php

namespace Tests\Feature;

use App\User;
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

            /* 'usage_notes' => 'Would you be in any way offended if I said that you were the visible personification of absolute perfection?', */
            /* 'private_notes' => ';jkals;jfkld;sjfkasd;jfklsafkl;jkaslf;' */
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('Test Language');
        $response->assertSee('V-test');
        $response->assertSee('TA');
        $response->assertSee('Conjunct');
        $response->assertSee('Indicative');
        $response->assertSee('3s');
        /* $response->assertSee('Would you be in any way offended if I said that you were the visible personification of absolute perfection?'); */
        /* $response->assertSee(';jkals;jfkld;sjfkasd;jfklsafkl;jkaslf;'); */
    }

    /** @test */
    public function a_verb_form_shows_its_historical_notes_if_it_has_them()
    {
        $verbForm = factory(VerbForm::class)->create([
            'historical_notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('Historical notes');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function a_verb_form_does_not_show_historical_notes_if_it_does_not_have_them()
    {
        $verbForm = factory(VerbForm::class)->create(['historical_notes' => null]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertDontSee('Historical notes');
    }

    /** @test */
    public function a_verb_form_shows_its_allomorphy_notes_if_it_has_them()
    {
        $verbForm = factory(VerbForm::class)->create([
            'allomorphy_notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('Allomorphy');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function a_verb_form_does_not_show_allomorphy_notes_if_it_does_not_have_them()
    {
        $verbForm = factory(VerbForm::class)->create(['allomorphy_notes' => null]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertDontSee('Allomorphy notes');
    }

    /** @test */
    public function a_verb_form_shows_its_usage_notes_if_it_has_them()
    {
        $verbForm = factory(VerbForm::class)->create([
            'usage_notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('Usage notes');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function a_verb_form_does_not_show_usage_notes_if_it_does_not_have_them()
    {
        $verbForm = factory(VerbForm::class)->create(['usage_notes' => null]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertDontSee('Usage notes');
    }

    /** @test */
    public function a_verb_form_shows_its_private_notes_if_it_has_them_and_the_user_has_permission()
    {
        $this->withPermissions();

        $user = factory(User::class)->create();
        $user->givePermissionTo('view private notes');

        $verbForm = factory(VerbForm::class)->create([
            'private_notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
        ]);

        $response = $this->actingAs($user)->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('Private notes');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function a_verb_form_does_not_show_private_notes_if_it_does_not_have_them()
    {
        $verbForm = factory(VerbForm::class)->create(['private_notes' => null]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertDontSee('Private notes');
    }

    /** @test */
    public function a_verb_form_does_not_show_private_note_if_the_user_does_not_have_permission()
    {
        $user = factory(User::class)->create();

        $verbForm = factory(VerbForm::class)->create([
            'private_notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
        ]);

        $response = $this->actingAs($user)->get($verbForm->url);

        $response->assertOk();
        $response->assertDontSee('Private notes');
        $response->assertDontSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }
}
