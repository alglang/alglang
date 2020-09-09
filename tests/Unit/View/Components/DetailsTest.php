<?php

namespace Tests\Unit\View\Components;

use Tests\TestCase;

class DetailsTest extends TestCase
{
    /** @test */
    public function it_shows_its_title()
    {
        $view = $this->blade(<<<'BLADE'
            <x-details title="The Title" :pages="[]">
                @slot('header')@endslot
            </x-details>
        BLADE);

        $view->assertSee('The Title');
    }

    /** @test */
    public function it_shows_its_header()
    {
        $view = $this->blade(<<<'BLADE'
            <x-details title="" :pages="[]">
                @slot('header')
                    The Header
                @endslot
            </x-details>
        BLADE);

        $view->assertSee('The Header');
    }

    /** @test */
    public function it_shows_page_links()
    {
        $pages = [
            ['hash' => 'foo'],
            ['hash' => 'bar_baz']
        ];

        $view = $this->blade(
            <<<'BLADE'
                <x-details title="" :pages="$pages">
                    @slot('header')@endslot
                    @slot('foo')@endslot
                    @slot('bar_baz')@endslot
                </x-details>
            BLADE,
            compact('pages')
        );

        $view->assertSee('Foo', 'Bar baz');
    }

    /** @test */
    public function it_shows_tab_counts_if_they_exist()
    {
        $pages = [
            ['hash' => 'foo', 'count' => 440],
        ];

        $view = $this->blade(
            <<<'BLADE'
                <x-details title="" :pages="$pages">
                    @slot('header')@endslot
                    @slot('foo')@endslot
                </x-details>
            BLADE,
            compact('pages')
        );

        $view->assertSee(440);
    }

    /** @test */
    public function it_renders_slots_that_match_its_hashes()
    {
        $pages = [
            ['hash' => 'foo']
        ];

        $view = $this->blade(
            <<<'BLADE'
                <x-details title="" :pages="$pages">
                    @slot('header')@endslot
                    @slot('foo')
                        Hello
                    @endslot
                </x-details>
            BLADE,
            compact('pages')
        );

        $view->assertSee('Hello');
    }

    /** @test */
    public function it_does_not_render_slots_that_do_not_match_its_hashes()
    {
        $view = $this->blade(
            <<<'BLADE'
                <x-details title="" :pages="[]">
                    @slot('header')@endslot
                    @slot('bar')
                        Hello
                    @endslot
                </x-details>
            BLADE
        );

        $view->assertDontSee('Hello');
    }
}
