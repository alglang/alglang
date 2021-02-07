<?php

namespace Tests\Unit\View\Components;

use App\View\Components\PreviewLink;
use Tests\TestCase;

class PreviewLinkTest extends TestCase
{
    /** @test */
    public function it_renders_a_link_if_the_model_has_a_url()
    {
        $mockModel = (object)[
            'url' => '/foo'
        ];

        $view = $this->blade('<x-preview-link :model="$mockModel">Bar</x-preview-link>', [
            'mockModel' => $mockModel
        ]);

        $view->assertSee('href="/foo"', false);
        $view->assertSee('Bar');
    }

    /** @test */
    public function it_does_not_render_a_link_if_the_model_does_not_have_a_url()
    {
        $mockModel = (object)['url' => ''];

        $view = $this->blade('<x-preview-link :model="$mockModel">Bar</x-preview-link>', compact('mockModel'));

        $view->assertDontSee('<a', false);
    }

    /** @test */
    public function it_forwards_classes()
    {
        $mockModel = (object)[
            'url' => '/foo'
        ];

        $view = $this->blade('<x-preview-link :model="$mockModel" class="m-1 p-2">Bar</x-preview-link>', [
            'mockModel' => $mockModel
        ]);

        $view->assertSee('class="m-1 p-2"', false);
    }

    /** @test */
    public function it_includes_a_preview_if_the_model_has_a_preview()
    {
        $mockModel = (object)[
            'url' => '/foo',
            'preview' => '<p>This is the preview</p>'
        ];

        $view = $this->blade('<x-preview-link :model="$mockModel">Bar</x-preview-link>', [
            'mockModel' => $mockModel
        ]);

        $view->assertSee('This is the preview');
    }
}
