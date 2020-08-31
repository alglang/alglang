<?php

namespace Tests\Unit\View\Components;

use App\Models\Source;
use Tests\TestCase;

class SourceListTest extends TestCase
{
    /** @test */
    public function it_shows_all_sources()
    {
        $source = new Source([
            'author' => 'Foo',
            'year' => '1234'
        ]);

        $view = $this->blade('<x-source-list :sources="$sources" />', [
            'sources' => collect([$source])
        ]);

        $view->assertSee('Foo 1234');
    }

    /** @test */
    public function it_shows_extra_info()
    {
        $source = (object)[
            'short_citation' => '',
            'url' => '',
            'attribution' => (object)['extra_info' => 'lorem ipsum']
        ];

        $view = $this->blade('<x-source-list :sources="$sources" />', [
            'sources' => collect([$source])
        ]);

        $view->assertSee('lorem ipsum');
    }

    /** @test */
    public function it_does_not_try_to_show_extra_info_if_there_is_none()
    {
        $source = (object)[
            'short_citation' => '',
            'url' => '',
            'attribution' => (object)['extra_info' => null]
        ];

        $view = $this->blade('<x-source-list :sources="$sources" />', [
            'sources' => collect([$source])
        ]);

        $view->assertDontSee('()');
    }
}
