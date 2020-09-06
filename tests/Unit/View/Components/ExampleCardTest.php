<?php

namespace Tests\Unit\View\Components;

use App\Models\Example;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExampleCardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_its_formatted_shape()
    {
        $example = factory(Example::class)->create();

        $view = $this->blade('<x-example-card :example="$example" />', compact('example'));

        $view->assertSee($example->formatted_shape, false);
    }
}
