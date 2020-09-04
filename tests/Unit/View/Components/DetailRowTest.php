<?php

namespace Tests\Unit\View\Components;

use Tests\TestCase;

class DetailRowTest extends TestCase
{
    /** @test */
    public function it_shows_its_label()
    {
        $view = $this->blade('<x-detail-row label="Foo bar" />');
        $view->assertSee('Foo bar');
    }

    /** @test */
    public function it_shows_its_aria_id()
    {
        $view = $this->blade('<x-detail-row label="Foo bar" />');
        $view->assertSee('foo-bar');
    }

    /** @test */
    public function it_shows_its_slot_content()
    {
        $view = $this->blade('<x-detail-row label="">Slot content</x-detail-row>');
        $view->assertSee('Slot content');
    }
}
