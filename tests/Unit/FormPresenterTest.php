<?php

namespace Tests\Unit;

use App\Models\Form;
use App\Models\Language;
use Tests\TestCase;

class FormPresenterTest extends TestCase
{
    /** @test */
    public function its_formatted_shape_is_in_an_i_tag()
    {
        $form = new Form(['shape' => 'foo']);
        $this->assertEquals('<i>foo</i>', $form->formatted_shape);
    }

    /** @test */
    public function it_wraps_capital_letters_in_its_formatted_shape_in_a_span()
    {
        $form = new Form(['shape' => 'V-foo']);
        $this->assertEquals('<i><span class="not-italic">V</span>-foo</i>', $form->formatted_shape);
    }

    /** @test */
    public function its_formatted_shape_includes_an_asterisk_if_its_language_is_reconstructed()
    {
        $form = new Form([
            'shape' => '-foo',
            'language' => new Language(['reconstructed' => true])
        ]);
        $this->assertEquals('<i>*-foo</i>', $form->formatted_shape);
    }

    /** @test */
    public function its_formatted_phonemic_shape_is_null_if_there_is_no_phonemic_shape()
    {
        $form = new Form(['phonemic_form' => null]);
        $this->assertNull($form->formatted_phonemic_shape);
    }

    /** @test */
    public function its_formatted_phonemic_shape_is_in_an_i_tag()
    {
        $form = new Form(['phonemic_shape' => 'foo']);
        $this->assertEquals('<i>foo</i>', $form->formatted_phonemic_shape);
    }

    /** @test */
    public function it_wraps_capital_letters_in_its_formatted_phonemic_shape_in_a_span()
    {
        $form = new Form(['phonemic_shape' => 'V-foo']);
        $this->assertEquals('<i><span class="not-italic">V</span>-foo</i>', $form->formatted_phonemic_shape);
    }

    /** @test */
    public function its_formatted_phonemic_shape_includes_an_asterisk_if_its_language_is_reconstructed()
    {
        $form = new Form([
            'phonemic_shape' => '-foo',
            'language' => new Language(['reconstructed' => true])
        ]);
        $this->assertEquals('<i>*-foo</i>', $form->formatted_phonemic_shape);
    }
}
