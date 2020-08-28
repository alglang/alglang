<?php

namespace Tests\Unit;

use App\Models\Example;
use App\Models\Form;
use App\Models\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamplePresenterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function its_formatted_shape_is_in_an_i_tag()
    {
        $example = new Example(['shape' => 'foobar']);
        $this->assertEquals('<i>foobar</i>', $example->formatted_shape);
    }

    /** @test */
    public function its_formatted_shape_includes_an_asterisk_if_its_language_is_reconstructed()
    {
        $example = factory(Example::class)->create([
            'shape' => 'foobar',
            'form_id' => factory(Form::class)->create([
                'language_code' => factory(Language::class)->create(['reconstructed' => true])
            ])
        ]);
        $this->assertEquals('<i>*foobar</i>', $example->formatted_shape);
    }
}
