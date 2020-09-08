<?php

namespace Tests\Feature;

use App\Models\Example;
use App\Models\Form;
use App\Models\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchExamplesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_verb_form_examples()
    {
        $form = Form::factory()->create(['shape' => 'V-bar']);
        $example = Example::factory()->create([
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
    public function it_fetches_source_examples()
    {
        $this->withoutExceptionHandling();
        $source = Source::factory()->create();
        $example = Example::factory()->create([
            'shape' => 'foobar',
            'form_id' => Form::factory()->create(['shape' => 'V-bar'])->id,
            'translation' => '<p>Lorem ipsum dolor</p>'
        ]);
        $example->addSource($source);

        $response = $this->get("/api/examples?source_id=$source->id");

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
    public function it_responds_with_a_400_if_a_suitable_key_is_not_provided()
    {
        $response = $this->get('/api/examples');
        $response->assertStatus(400);
    }

    /** @test */
    public function examples_are_filtered_by_verb_form()
    {
        $form1 = Form::factory()->create(['shape' => 'V-bar']);
        $example1 = Example::factory()->create([
            'shape' => 'foobar',
            'form_id' => $form1->id,
            'translation' => '<p>Lorem ipsum dolor</p>'
        ]);
        $form2 = Form::factory()->create(['shape' => 'V-x']);
        $example2 = Example::factory()->create([
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
    public function examples_are_filtered_by_source()
    {
        $source1 = Source::factory()->create();
        $source2 = Source::factory()->create();
        $example1 = Example::factory()->create(['shape' => 'foobar']);
        $example2 = Example::factory()->create(['shape' => 'foobaz',]);
        $example1->addSource($source1);
        $example2->addSource($source2);

        $response = $this->get("/api/examples?source_id=$source1->id");

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJson([
            'data' => [
                ['shape' => 'foobar']
            ]
        ]);
    }

    /** @test */
    public function examples_are_filtered_by_source_and_form()
    {
        $form1 = Form::factory()->create();
        $form2 = Form::factory()->create();
        $source1 = Source::factory()->create();
        $source2 = Source::factory()->create();
        $example1 = Example::factory()->create([
            'form_id' => $form1->id,
            'shape' => 'foobar'
        ]);
        $example2 = Example::factory()->create([
            'form_id' => $form1->id,
            'shape' => 'foobaz'
        ]);
        $example3 = Example::factory()->create([
            'form_id' => $form2->id,
            'shape' => 'foobang'
        ]);
        $example1->addSource($source1);
        $example2->addSource($source2);
        $example3->addSource($source1);

        $response = $this->get("/api/examples?source_id=$source1->id&form_id=$form1->id");

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJson([
            'data' => [
                ['shape' => 'foobar']
            ]
        ]);
    }

    /** @test */
    public function it_paginates_examples()
    {
        $form = Form::factory()->create();
        Example::factory()->count(3)->create(['form_id' => $form->id]);

        $response = $this->get("/api/examples?form_id=$form->id&per_page=2");

        $response->assertOk();
        $response->assertJsonCount(2, 'data');

        $nextResponse = $this->get($response->decodeResponseJson()['links']['next']);
        $nextResponse->assertOk();
        $nextResponse->assertJsonCount(1, 'data');
    }
}
