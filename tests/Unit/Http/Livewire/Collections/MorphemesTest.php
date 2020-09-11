<?php

namespace Tests\Unit\Http\Livewire\Collections;

use App\Models\Language;
use App\Models\Morpheme;
use App\Models\Source;
use App\Http\Livewire\Collections\Morphemes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MorphemesTest extends TestCase
{
    use RefreshDatabase;

    protected function assertMorphemesSliceInView($view, $morphemes, $start, $end): void
    {
        $view->assertSee($morphemes[$start]->shape);
        $view->assertSee($morphemes[$end - 1]->shape);

        if ($start > 0) {
            $view->assertDontSee($morphemes[$start - 1]->shape);
        }

        if ($end < $morphemes->count()) {
            $view->assertDontSee($morphemes[$end]->shape);
        }
    }

    /** @test */
    public function it_shows_morphemes_from_a_language()
    {
        $language = Language::factory()->create();
        $morpheme1 = Morpheme::factory()->create([
            'shape' => 'foo-',
            'language_code' => $language
        ]);
        $morpheme2 = Morpheme::factory()->create([
            'shape' => 'bar-',
            'language_code' => $language
        ]);

        $view = $this->livewire(Morphemes::class, ['model' => $language]);

        $view->assertSee('foo-');
        $view->assertSee('bar-');
    }

    /** @test */
    public function it_shows_morphemes_from_a_source()
    {
        $source = Source::factory()->create();
        $morpheme1 = Morpheme::factory()->create(['shape' => 'foo-']);
        $morpheme2 = Morpheme::factory()->create(['shape' => 'bar-']);

        $morpheme1->addSource($source);
        $morpheme2->addSource($source);

        $view = $this->livewire(Morphemes::class, ['model' => $source]);

        $view->assertSee('foo-');
        $view->assertSee('bar-');
    }

    /** @test */
    public function it_shows_the_next_page()
    {
        $language = Language::factory()->hasMorphemes(Morphemes::maxSizeFor('sm') * 2)->create();
        $view = $this->livewire(Morphemes::class, [
            'model' => $language,
            'screenSize' => 'sm'
        ]);

        $view->call('nextPage');

        $this->assertMorphemesSliceInView(
            $view,
            $language->morphemes,
            Morphemes::maxSizeFor('sm'),
            Morphemes::maxSizeFor('sm') * 2
        );
    }

    /** @test */
    public function it_shows_the_previous_page()
    {
        $language = Language::factory()->hasMorphemes(Morphemes::maxSizeFor('sm') + 1)->create();

        $view = $this->livewire(Morphemes::class, [
            'model' => $language,
            'screenSize' => 'sm',
            'page' => 1
        ]);
        $view->call('prevPage');

        $this->assertMorphemesSliceInView($view, $language->morphemes, 0, Morphemes::maxSizeFor('sm'));
    }

    /** @test */
    public function it_does_not_show_the_next_page_if_there_are_no_more_pages()
    {
        $language = Language::factory()->hasMorphemes(1)->create();
        $view = $this->livewire(Morphemes::class, [
            'model' => $language,
            'screenSize' => 'sm',
        ]);
        $view->assertSet('page', 0);
        
        $view->call('nextPage');

        $view->assertSet('page', 0);
    }

    /** @test */
    public function it_does_not_show_the_previous_page_if_there_are_no_more_pages()
    {
        $language = Language::factory()->hasMorphemes(1)->create();

        $view = $this->livewire(Morphemes::class, [
            'model' => $language,
            'screenSize' => 'sm'
        ]);
        $view->assertSet('page', 0);

        $view->call('prevPage');

        $view->assertSet('page', 0);
    }

    /** @test */
    public function it_adjusts_morpheme_count_by_screen_size()
    {
        $language = Language::factory()->hasMorphemes(Morphemes::maxSizeFor('xl') + 1)->create();

        foreach (['sm', 'md', 'lg', 'xl'] as $size) {
            $view = $this->livewire(Morphemes::class, ['model' => $language, 'screenSize' => $size]);
            $this->assertMorphemesSliceInView($view, $language->morphemes, 0, Morphemes::maxSizeFor($size));
        }
    }

    /** @test */
    public function it_resizes()
    {
        $language = Language::factory()->hasMorphemes(Morphemes::maxSizeFor('md'))->create();

        $view = $this->livewire(Morphemes::class, [
            'model' => $language,
            'screenSize' => 'sm'
        ]);
        $view->emit('resize', 'md');

        $this->assertMorphemesSliceInView($view, $language->morphemes, 0, Morphemes::maxSizeFor('md'));
    }
}
