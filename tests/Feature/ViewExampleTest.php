<?php

namespace Tests\Feature;

use App\Example;
use App\Language;
use App\Morpheme;
use App\Source;
use App\User;
use App\VerbForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewExampleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_example_can_be_viewed()
    {
        $example = factory(Example::class)->create([
            'shape' => 'foobar',
            'translation' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam',
            'form_id' => factory(VerbForm::class)->create(['shape' => 'V-bar'])->id
        ]);

        $response = $this->get($example->url);

        $response->assertOk();
        $response->assertViewIs('examples.show');
        $response->assertViewHas('example', $example);
        $response->assertSee('foobar');
        $response->assertSee('V-bar');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function an_example_shows_its_morphemes()
    {
        $language = factory(Language::class)->create();
        $stem = factory(Morpheme::class)->create([
            'language_id' => $language->id,
            'shape' => 'foo-'
        ]);
        $form = factory(VerbForm::class)->create([
            'language_id' => $language->id,
            'morpheme_structure' => "{$language->vStem->id}-bar"
        ]);
        $example = factory(Example::class)->create([
            'form_id' => $form->id,
            'stem_id' => $stem->id
        ]);

        $response = $this->get($example->url);
        $response->assertSee('foo');
        $response->assertSee('bar');
    }

    /** @test */
    public function an_example_shows_its_notes_if_it_has_any()
    {
        $example = factory(Example::class)->create([
            'notes' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
        ]);

        $response = $this->get($example->url);

        $response->assertOk();
        $response->assertSee('Notes');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function an_example_does_not_show_notes_if_it_has_none()
    {
        $example = factory(Example::class)->create(['notes' => null]);
        $response = $this->get($example->url);

        $response->assertOk();
        $response->assertDontSee('Notes');
    }

    /** @test */
    public function an_example_shows_its_private_notes_if_it_has_them_and_the_user_has_permission()
    {
        $this->withPermissions();

        $user = factory(User::class)->create();
        $user->assignRole('contributor');
        $example = factory(Example::class)->create([
            'private_notes' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
        ]);

        $response = $this->actingAs($user)->get($example->url);

        $response->assertOk();
        $response->assertSee('Private notes');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function an_example_does_not_show_private_notes_if_it_has_none()
    {
        $this->withPermissions();

        $user = factory(User::class)->create();
        $user->assignRole('contributor');
        $example = factory(Example::class)->create(['private_notes' => null]);

        $response = $this->actingAs($user)->get($example->url);

        $response->assertOk();
        $response->assertDontSee('Private notes');
    }

    /** @test */
    public function an_example_does_not_show_private_notes_if_the_user_does_not_have_permission()
    {
        $user = factory(User::class)->create();
        $example = factory(Example::class)->create([
            'private_notes' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
        ]);

        $response = $this->actingAs($user)->get($example->url);

        $response->assertOk();
        $response->assertDontSee('Private notes');
        $response->assertDontSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function sources_appear_if_the_example_has_sources()
    {
        $example = factory(Example::class)->create();
        $source = factory(Source::class)->create(['author' => 'Foo Bar']);
        $example->addSource($source);

        $response = $this->get($example->url);
        $response->assertOk();

        $response->assertSee('Sources');
        $response->assertSee('Foo Bar');
    }

    /** @test */
    public function sources_do_not_appear_if_the_example_has_no_sources()
    {
        $example = factory(Example::class)->create();

        $response = $this->get($example->url);
        $response->assertOk();

        $response->assertDontSee('Sources');
    }
}
