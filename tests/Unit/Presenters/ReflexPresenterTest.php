<?php

namespace Tests\Unit\Presenters;

use App\Models\Language;
use App\Models\Phoneme;
use App\Models\Reflex;
use PHPUnit\Framework\TestCase;

class ReflexPresenterTest extends TestCase
{
    /** @test */
    public function it_has_a_formatted_name()
    {
        $reflex = new Reflex([
            'phoneme' => new Phoneme([
                'shape' => 'x',
                'language' => new Language(['name' => 'Parent Language'])
            ]),
            'reflex' => new Phoneme([
                'shape' => 'y',
                'language' => new Language(['name' => 'Child Language'])
            ])
        ]);

        $this->assertEquals(
            '<span><i>x</i> (Parent Language) &gt; <i>y</i> (Child Language)</span>',
            $reflex->formatted_name
        );
    }
}
