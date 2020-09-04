<?php

namespace Tests\Unit\View\Components;

use App\Models\Gloss;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlossFieldTest extends TestCase
{
    /** @test */
    public function it_shows_gloss_abbrevations()
    {
        $glosses = [
            new Gloss(['abv' => 'G1']),
            new Gloss(['abv' => 'G2'])
        ];

        $view = $this->blade('<x-gloss-field :glosses="$glosses" />', compact('glosses'));

        $view->assertSee('G1');
        $view->assertSee('G2');
    }
}
