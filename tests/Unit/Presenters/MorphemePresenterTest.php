<?php

namespace Tests\Unit\Presenters;

use App\Models\Language;
use App\Models\Morpheme;
use Tests\TestCase;

class MorphemePresenterTest extends TestCase
{
    /** @test */
    public function its_formatted_shape_is_in_an_i_tag()
    {
        $morpheme = new Morpheme(['shape' => '-foo']);
        $this->assertEquals('<i>-foo</i>', $morpheme->formatted_shape);
    }

    /** @test */
    public function its_formatted_shape_includes_an_asterisk_if_its_language_is_reconstructed()
    {
        $morpheme = new Morpheme([
            'shape' => '-foo',
            'language' => new Language(['reconstructed' => true])
        ]);
        $this->assertEquals('<i>*-foo</i>', $morpheme->formatted_shape);
    }
}
