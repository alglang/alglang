<?php

namespace Tests\Feature;

use App\Example;
use App\VerbForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchExamplesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_verb_form_examples()
    {
        $form = factory(VerbForm::class)->create(['shape' => 'V-bar']);
        $example = factory(Example::class)->create([
            'shape' => 'foobar',
            'form_id' => $form->id,
            'translation' => '<p>Lorem ipsum dolor</p>'
        ]);

        $response = $this->get("/api/examples?form_id=$form->id");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                [
                    'shape' => 'foobar',
                    'url' => $example->url,
                    'form' => ['shape' => 'V-bar'],
                    'translation' => '<p>Lorem ipsum dolor</p>'
                ]
            ]
        ]);
    }

    /** @test */
    public function examples_are_filtered_by_verb_form()
    {
        $form1 = factory(VerbForm::class)->create(['shape' => 'V-bar']);
        $example1 = factory(Example::class)->create([
            'shape' => 'foobar',
            'form_id' => $form1->id,
            'translation' => '<p>Lorem ipsum dolor</p>'
        ]);
        $form2 = factory(VerbForm::class)->create(['shape' => 'V-x']);
        $example2 = factory(Example::class)->create([
            'shape' => 'foox',
            'form_id' => $form2->id,
            'translation' => '<p>kl;fjklsdf</p>'
        ]);

        $response = $this->get("/api/examples?form_id=$form1->id");

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJson([
            'data' => [
                [
                    'shape' => 'foobar',
                    'form' => ['shape' => 'V-bar'],
                    'translation' => '<p>Lorem ipsum dolor</p>'
                ]
            ]
        ]);
    }

    /** @test */
    public function it_paginates_verb_form_examples()
    {
        $form = factory(VerbForm::class)->create();
        factory(Example::class, 11)->create(['form_id' => $form->id]);

        $response = $this->get("/api/examples?form_id=$form->id");

        $response->assertOk();
        $response->assertJsonCount(10, 'data');

        $nextResponse = $this->get($response->decodeResponseJson()['links']['next']);
        $nextResponse->assertOk();
        $nextResponse->assertJsonCount(1, 'data');
    }
}
