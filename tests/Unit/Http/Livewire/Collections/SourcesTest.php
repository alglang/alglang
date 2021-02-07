<?php

namespace Tests\Unit\Http\Livewire\Collections;

use App\Models\Source;
use App\Traits\Sourceable;
use App\Contracts\HasSources;
use App\Http\Livewire\Collections\Sources;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Livewire\Testing\TestableLivewire;
use Tests\TestCase;

class SourcesTest extends TestCase
{
    use RefreshDatabase;

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

    protected function navigateToSourcesComponent(array $attrs = []): TestableLivewire
    {
        $view = $this->livewire(Sources::class, $attrs);
        $view->emit('tabChanged', 'sources');
        return $view;
    }

    /** @test */
    public function it_loads_when_the_tab_changes_to_sources(): void
    {
        $source = Source::factory()->create(['author' => 'Doe']);
        $view = $this->livewire(Sources::class);
        $view->assertDontSee('Doe');

        $view->emit('tabChanged', 'sources');

        $view->assertSee('Doe');
    }

    /** @test */
    public function it_shows_all_sources()
    {
        $source1 = Source::factory()->create(['author' => 'Doe', 'year' => 1]);
        $source2 = Source::factory()->create(['author' => 'Doe', 'year' => 1]);

        $view = $this->navigateToSourcesComponent();

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

        $source = Source::factory()->create(['author' => 'Foo']);
        Source::factory()->create(['author' => 'Bar']);
        $sourced = $sourcedClass->create();
        $sourced->addSource($source);

        $view = $this->navigateToSourcesComponent(['model' => $sourced]);

        $view->assertSee('Foo');
        $view->assertDontSee('Bar');
    }

    /** @test */
    public function it_shows_the_next_page()
    {
        Source::factory()->count(Sources::maxSizeFor('sm')+1)->create();
        $view = $this->navigateToSourcesComponent(['screenSize' => 'sm']);

        $view->call('nextPage');

        $this->assertSourcesSliceInView(
            $view,
            Source::all(),
            Sources::maxSizeFor('sm'),
            Sources::maxSizeFor('sm') + 1
        );
    }

    /** @test */
    public function it_shows_the_previous_page()
    {
        Source::factory()->count(Sources::maxSizeFor('sm')+1)->create();
        $view = $this->navigateToSourcesComponent(['screenSize' => 'sm', 'page' => 1]);

        $view->call('prevPage');

        $view->assertSet('page', 0);
        $this->assertSourcesSliceInView($view, Source::all(), 0, Sources::maxSizeFor('sm'));
    }

    /** @test */
    public function it_does_not_show_the_next_page_if_there_are_no_more_pages()
    {
        Source::factory()->count(1)->create();
        $view = $this->navigateToSourcesComponent(['screenSize' => 'sm']);
        $view->assertSet('page', 0);

        $view->call('nextPage');

        $view->assertSet('page', 0);
        $this->assertSourcesSliceInView($view, Source::all(), 0, 1);
    }

    /** @test */
    public function it_does_not_show_the_previous_page_if_there_are_no_more_pages()
    {
        Source::factory()->count(1)->create();
        $view = $this->navigateToSourcesComponent(['screenSize' => 'sm']);
        $view->assertSet('page', 0);

        $view->call('prevPage');

        $view->assertSet('page', 0);
        $this->assertSourcesSliceInView($view, Source::all(), 0, 1);
    }

    /** @test */
    public function it_filters_with_a_text_query()
    {
        Source::factory()->create(['author' => 'Foo', 'year' => 2000]);
        Source::factory()->create(['author' => 'Bar', 'year' => 1999]);

        $view = $this->navigateToSourcesComponent();
        $view->set('filter', 'F');

        $view->assertSee('Foo 2000');
        $view->assertDontSee('Bar 1999');
    }

    public function it_filters_including_the_year()
    {
        Source::factory()->create(['author' => 'Foo', 'year' => 2000]);
        Source::factory()->create(['author' => 'Bar', 'year' => 1999]);

        $view = $this->navigateToSourcesComponent();
        $view->set('filter', 'o 2');

        $view->assertSee('Foo 2000');
        $view->assertDontSee('Bar 1999');
    }

    /**
     * @test
     * @group slow
     */
    public function it_adjusts_source_count_by_screen_size()
    {
        Source::factory()->count(Sources::maxSizeFor('xl')+1)->create();
        $sources = Source::all();

        foreach (['sm', 'md', 'lg', 'xl'] as $size) {
            $view = $this->navigateToSourcesComponent(['screenSize' => $size]);
            $this->assertSourcesSliceInView($view, $sources, 0, Sources::maxSizeFor($size));
        }
    }

    /** @test */
    public function it_resizes()
    {
        Source::factory()->count(Sources::maxSizeFor('md'))->create();
        $sources = Source::all();
        $view = $this->navigateToSourcesComponent(['screenSize' => 'sm']);
        $this->assertSourcesSliceInView($view, $sources, 0, Sources::maxSizeFor('sm'));

        $view->emit('resize', 'md');

        $this->assertSourcesSliceInView($view, $sources, 0, Sources::maxSizeFor('md'));
    }
}
