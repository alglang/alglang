<?php

namespace Tests\Unit\Http\Livewire\Collections;

use App\Models\Source;
use App\Traits\Sourceable;
use App\Contracts\HasSources;
use App\Http\Livewire\Collections\Sources;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SourcesTest extends TestCase
{
    /** @var bool */
    public static $seeded = false;

    public function setUp(): void
    {
        parent::setUp();

        if (!static::$seeded) {
            // Ensure the database has been migrated
            Artisan::call('migrate');

            // Ensure sources were cleaned up from earlier runs
            DB::table('sources')->delete();

            factory(Source::class, 225)->create();
            static::$seeded = true;
        }
    }

    protected function assertSourcesSliceInView($view, $sources, $start, $end): void
    {
        $view->assertSee($sources[$start]->shape);
        $view->assertSee($sources[$end - 1]->shape);

        if ($start > 0) {
            $view->assertDontSee($sources[$start - 1]->short_citation);
        }

        if ($end < $sources->count()) {
            $view->assertDontSee($sources[$end]->short_citation);
        }
    }

    /** @test */
    public function it_shows_all_sources()
    {
        $source1 = factory(Source::class)->create(['author' => 'Doe', 'year' => 1]);
        $source2 = factory(Source::class)->create(['author' => 'Doe', 'year' => 1]);

        $view = $this->livewire(Sources::class);

        $view->assertSee('Doe 1a');
        $view->assertSee('Doe 1b');
    }

    /** @test */
    public function it_shows_sources_that_belong_to_a_model()
    {
        $sourcedClass = new class extends Model implements HasSources {
            use Sourceable;
            public $table = 'sourced';
        };

        $source = factory(Source::class)->create(['author' => 'Foo']);
        factory(Source::class)->create(['author' => 'Bar']);
        $sourced = $sourcedClass->create();
        $sourced->addSource($source);

        $view = $this->livewire(Sources::class, ['model' => $sourced]);

        $view->assertSee('Foo');
        $view->assertDontSee('Bar');
    }

    /** @test */
    public function it_shows_the_next_page()
    {
        $view = $this->livewire(Sources::class, ['screenSize' => 'sm']);
        $view->call('nextPage');

        $this->assertSourcesSliceInView($view, Source::all(), 20, 40);
    }

    /** @test */
    public function it_shows_the_previous_page()
    {
        $view = $this->livewire(Sources::class, ['screenSize' => 'sm', 'page' => 1]);
        $view->call('prevPage');

        $this->assertSourcesSliceInView($view, Source::all(), 0, 20);
    }

    /** @test */
    public function it_does_not_show_the_next_page_if_there_are_no_more_pages()
    {
        $view = $this->livewire(Sources::class, ['screenSize' => 'sm', 'page' => 12]);
        $view->call('nextPage');

        $view->assertSet('page', 12);
        $this->assertSourcesSliceInView($view, Source::all(), 220, 225);
    }

    /** @test */
    public function it_does_not_show_the_previous_page_if_there_are_no_more_pages()
    {
        factory(Source::class, 25)->create();

        $view = $this->livewire(Sources::class, ['screenSize' => 'sm', 'page' => 0]);
        $view->call('prevPage');

        $view->assertSet('page', 0);
        $this->assertSourcesSliceInView($view, Source::all(), 0, 20);
    }

    /** @test */
    public function it_filters_with_a_text_query()
    {
        factory(Source::class)->create(['author' => 'Foo', 'year' => 2000]);
        factory(Source::class)->create(['author' => 'Bar', 'year' => 1999]);

        $view = $this->livewire(Sources::class);
        $view->set('filter', 'F');

        $view->assertSee('Foo 2000');
        $view->assertDontSee('Bar 1999');
    }

    public function it_filters_including_the_year()
    {
        factory(Source::class)->create(['author' => 'Foo', 'year' => 2000]);
        factory(Source::class)->create(['author' => 'Bar', 'year' => 1999]);

        $view = $this->livewire(Sources::class);
        $view->set('filter', 'o 2');

        $view->assertSee('Foo 2000');
        $view->assertDontSee('Bar 1999');
    }

    /** @test */
    public function it_shows_a_maximum_of_20_sources_on_a_small_screen()
    {
        $view = $this->livewire(Sources::class, ['screenSize' => 'sm']);
        $this->assertSourcesSliceInView($view, Source::all(), 0, 20);
    }

    /** @test */
    public function it_shows_a_maximum_of_100_sources_on_a_medium_screen()
    {
        $view = $this->livewire(Sources::class, ['screenSize' => 'md']);
        $this->assertSourcesSliceInView($view, Source::all(), 0, 100);
    }

    /** @test */
    public function it_shows_a_maximum_of_180_sources_on_a_large_screen()
    {
        $view = $this->livewire(Sources::class, ['screenSize' => 'lg']);
        $this->assertSourcesSliceInView($view, Source::all(), 0, 180);
    }

    /** @test */
    public function it_shows_a_maximum_of_224_sources_on_an_extra_large_screen()
    {
        $view = $this->livewire(Sources::class, ['screenSize' => 'xl']);
        $this->assertSourcesSliceInView($view, Source::all(), 0, 224);
    }

    /** @test */
    public function it_resizes()
    {
        $view = $this->livewire(Sources::class, ['screenSize' => 'sm']);
        $view->emit('resize', 'md');

        $this->assertSourcesSliceInView($view, Source::all(), 0, 100);
    }
}
