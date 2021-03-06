<?php

namespace Tests\Unit\Presenters;

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
        $example = Example::factory()->create([
            'shape' => 'foobar',
            'form_id' => Form::factory()->create([
                'language_code' => Language::factory()->create(['reconstructed' => true])
            ])
        ]);
        $this->assertEquals('<i>*foobar</i>', $example->formatted_shape);
    }

    /** @test */
    public function its_formatted_phonemic_shape_is_in_an_i_tag()
    {
        $example = new Example(['phonemic_shape' => 'foobar']);
        $this->assertEquals('<i>foobar</i>', $example->formatted_phonemic_shape);
    }

    /** @test */
    public function its_formatted_phonemic_shape_includes_an_asterisk_if_its_language_is_reconstructed()
    {
        $example = Example::factory()->create([
            'phonemic_shape' => 'foobar',
            'form_id' => Form::factory()->create([
                'language_code' => Language::factory()->create(['reconstructed' => true])
            ])
        ]);
        $this->assertEquals('<i>*foobar</i>', $example->formatted_phonemic_shape);
    }
}
