<?php

namespace Tests\Unit\Http\Livewire\Collections;

use App\Models\Language;
use App\Models\Morpheme;
use App\Models\Source;
use App\Http\Livewire\Collections\Morphemes;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $language = factory(Language::class)->create();
        $morpheme1 = factory(Morpheme::class)->create([
            'shape' => 'foo-',
            'language_code' => $language
        ]);
        $morpheme2 = factory(Morpheme::class)->create([
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
        $source = factory(Source::class)->create();
        $morpheme1 = factory(Morpheme::class)->create(['shape' => 'foo-']);
        $morpheme2 = factory(Morpheme::class)->create(['shape' => 'bar-']);

        $morpheme1->addSource($source);
        $morpheme2->addSource($source);

        $view = $this->livewire(Morphemes::class, ['model' => $source]);

        $view->assertSee('foo-');
        $view->assertSee('bar-');
    }

    /** @test */
    public function it_shows_the_next_page()
    {
        $language = factory(Language::class)->create();
        factory(Morpheme::class, 20)->create(['language_code' => $language]);

        $view = $this->livewire(Morphemes::class, [
            'model' => $language,
            'screenSize' => 'sm'
        ]);
        $view->call('nextPage');

        $this->assertMorphemesSliceInView($view, $language->morphemes, 10, 20);
    }

    /** @test */
    public function it_shows_the_previous_page()
    {
        $language = factory(Language::class)->create();
        factory(Morpheme::class, 20)->create(['language_code' => $language]);

        $view = $this->livewire(Morphemes::class, [
            'model' => $language,
            'screenSize' => 'sm',
            'page' => 1
        ]);
        $view->call('prevPage');

        $this->assertMorphemesSliceInView($view, $language->morphemes, 0, 10);
    }

    /** @test */
    public function it_does_not_show_the_next_page_if_there_are_no_more_pages()
    {
        $language = factory(Language::class)->create();
        factory(Morpheme::class, 15)->create(['language_code' => $language]);

        $view = $this->livewire(Morphemes::class, [
            'model' => $language,
            'screenSize' => 'sm',
            'page' => 1
        ]);
        $view->call('nextPage');

        $this->assertMorphemesSliceInView($view, $language->morphemes, 10, $language->morphemes->count());
    }

    /** @test */
    public function it_does_not_show_the_previous_page_if_there_are_no_more_pages()
    {
        $language = factory(Language::class)->create();
        factory(Morpheme::class, 20)->create(['language_code' => $language]);

        $view = $this->livewire(Morphemes::class, [
            'model' => $language,
            'screenSize' => 'sm'
        ]);
        $view->call('prevPage');

        $this->assertMorphemesSliceInView($view, $language->morphemes, 0, 10);
    }

    /** @test */
    public function it_shows_a_maximum_of_10_morphemes_on_a_small_screen()
    {
        $language = factory(Language::class)->create();
        factory(Morpheme::class, 11)->create(['language_code' => $language]);

        $view = $this->livewire(Morphemes::class, [
            'model' => $language,
            'screenSize' => 'sm'
        ]);

        $this->assertMorphemesSliceInView($view, $language->morphemes, 0, 10);
    }

    /** @test */
    public function it_shows_a_maximum_of_14_morphemes_on_a_medium_screen()
    {
        $language = factory(Language::class)->create();
        factory(Morpheme::class, 15)->create(['language_code' => $language]);

        $view = $this->livewire(Morphemes::class, [
            'model' => $language,
            'screenSize' => 'md'
        ]);

        $this->assertMorphemesSliceInView($view, $language->morphemes, 0, 14);
    }

    /** @test */
    public function it_shows_a_maximum_of_27_morphemes_on_a_large_screen()
    {
        $language = factory(Language::class)->create();
        factory(Morpheme::class, 28)->create(['language_code' => $language]);

        $view = $this->livewire(Morphemes::class, [
            'model' => $language,
            'screenSize' => 'lg'
        ]);

        $this->assertMorphemesSliceInView($view, $language->morphemes, 0, 27);
    }

    /** @test */
    public function it_shows_a_maximum_of_56_morphemes_on_an_extra_large_screen()
    {
        $language = factory(Language::class)->create();
        factory(Morpheme::class, 57)->create(['language_code' => $language]);

        $view = $this->livewire(Morphemes::class, [
            'model' => $language,
            'screenSize' => 'xl'
        ]);

        $this->assertMorphemesSliceInView($view, $language->morphemes, 0, 56);
    }

    /** @test */
    public function it_resizes()
    {
        $language = factory(Language::class)->create();
        factory(Morpheme::class, 15)->create(['language_code' => $language]);

        $view = $this->livewire(Morphemes::class, [
            'model' => $language,
            'screenSize' => 'sm'
        ]);
        $view->emit('resize', 'md');

        $this->assertMorphemesSliceInView($view, $language->morphemes, 0, 14);
    }
}