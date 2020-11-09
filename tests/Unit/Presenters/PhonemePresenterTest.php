<?php

namespace Tests\Unit\Presenters;

use App\Models\Language;
use App\Models\Phoneme;
use Tests\TestCase;

class PhonemePresenterTest extends TestCase
{
    /** @test */
    public function it_wraps_its_formatted_shape_in_i_tags()
    {
        $phoneme = new Phoneme(['shape' => 'x']);
        $this->assertEquals('<i>x</i>', $phoneme->formatted_shape);
    }

    /** @test */
    public function it_marks_its_formatted_shape_as_reconstructed_if_its_language_is_reconstructed()
    {
        $phoneme = new Phoneme([
            'shape' => 'x',
            'language' => new Language(['reconstructed' => true])
        ]);
        $this->assertEquals('<i>*x</i>', $phoneme->formatted_shape);
    }

    /** @test */
    public function it_uses_its_ipa_if_it_has_no_shape()
    {
        $phoneme = new Phoneme(['shape' => null, 'ipa' => 'y']);
        $this->assertEquals('<i>y</i>', $phoneme->formatted_shape);
    }

    /** @test */
    public function it_puts_parentheses_around_marginal_phonemes()
    {
        $phoneme = new Phoneme([
            'shape' => 'x',
            'is_marginal' => true,
            'language' => new Language(['reconstructed' => true])
        ]);

        $this->assertEquals('(<i>*x</i>)', $phoneme->formatted_shape);
    }
}
