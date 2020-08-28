<?php

namespace Tests\Unit;

use App\Models\Source;
use App\Traits\Sourceable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_all_sources()
    {
        $sources = factory(Source::class, 1)->make([
            'author' => 'Foo',
            'year' => '1234'
        ]);

        $view = $this->blade('<x-source-list :sources="$sources" />', [
            'sources' => $sources
        ]);

        $view->assertSee('Foo 1234');
    }

    /** @test */
    public function it_shows_extra_info()
    {
        $sourcedClass = new class extends Model {
            use Sourceable;

            public $table = 'sourced';
        };

        $this->migrateTestTables();

        $sourced = $sourcedClass->create();
        $source = factory(Source::class)->create();
        $sourced->addSource($source, ['extra_info' => 'lorem ipsum']);

        $view = $this->blade('<x-source-list :sources="$sources" />', [
            'sources' => $sourced->sources
        ]);

        $view->assertSee('lorem ipsum');
    }

    /** @test */
    public function it_does_not_try_to_show_extra_info_if_there_is_none()
    {
        $sourcedClass = new class extends Model {
            use Sourceable;

            public $table = 'sourced';
        };

        $this->migrateTestTables();

        $sourced = $sourcedClass->create();
        $source = factory(Source::class)->create();
        $sourced->addSource($source);

        $view = $this->blade('<x-source-list :sources="$sources" />', [
            'sources' => $sourced->sources
        ]);

        $view->assertDontSee('()');
    }
}
