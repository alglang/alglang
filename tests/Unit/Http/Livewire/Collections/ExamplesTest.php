<?php

namespace Tests\Unit\Http\Livewire\Collections;

use App\Http\Livewire\Collections\Examples;
use App\Models\Example;
use App\Models\Form;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ExamplesTest extends TestCase
{
    /** @var bool */
    public static $seeded = false;

    /** @var Form */
    protected $form;

    /** @var Example */
    protected $example1;

    /** @var Example */
    protected $example2;

    public function setUp(): void
    {
        parent::setUp();

        if (!static::$seeded) {
            // Ensure the database has been migrated
            Artisan::call('migrate');

            // Ensure sources were cleaned up from earlier runs
            DB::table('forms')->delete();
            DB::table('examples')->delete();

            $form = Form::factory()->create(['shape' => 'V-seed']);

            Example::factory()->create([
                'shape' => 'fooexample',
                'form_id' => $form
            ]);
            Example::factory()->create([
                'shape' => 'barexample',
                'form_id' => $form
            ]);
            Example::factory()->count(Examples::maxSizeFor('xl') + 1)->create([
                'form_id' => $form
            ]);
            static::$seeded = true;
        }

        $this->form = Form::where('shape', 'V-seed')->first();
        $this->example1 = Example::where('shape', 'fooexample')->first();
        $this->example2 = Example::where('shape', 'barexample')->first();
    }

    protected function assertExamplesSliceInView($view, $examples, $start, $end): void
    {
        $view->assertSeeHtml($examples[$start]->formatted_shape);
        $view->assertSeeHtml($examples[$end - 1]->formatted_shape);

        if ($start > 0) {
            $view->assertDontSeeHtml($examples[$start - 1]->formatted_shape);
        }

        if ($end < $examples->count()) {
            $view->assertDontSeeHtml($examples[$end]->formatted_shape);
        }
    }

    /** @test */
    public function it_shows_examples_of_a_form()
    {
        $view = $this->livewire(Examples::class, ['model' => $this->form]);

        $view->assertSeeHtml($this->example1->formatted_shape);
        $view->assertSeeHtml($this->example2->formatted_shape);
    }

    /** @test */
    public function it_filters_by_shape()
    {
        $view = $this->livewire(Examples::class, ['model' => $this->form]);

        $view->set('filter', 'fooexam');

        $view->assertSeeHtml($this->example1->formatted_shape);
        $view->assertDontSeeHtml($this->example2->formatted_shape);
    }

    /** @test */
    public function it_resets_the_page_when_a_filter_is_applied()
    {
        
        $view = $this->livewire(Examples::class, ['model' => $this->form, 'page' => 2]);

        $view->set('filter', 'fooexample');

        $view->assertSet('page', 0);
    }

    /** @test */
    public function it_shows_the_next_page()
    {
        $view = $this->livewire(Examples::class, [
            'model' => $this->form,
            'screenSize' => 'sm',
            'page' => 0
        ]);

        $view->call('nextPage');

        $view->assertSet('page', 1);
        $this->assertExamplesSliceInView(
            $view,
            $this->form->examples,
            Examples::maxSizeFor('sm'),
            Examples::maxSizeFor('sm') * 2
        );
    }

    /** @test */
    public function it_shows_the_previous_page()
    {
        $view = $this->livewire(Examples::class, [
            'model' => $this->form,
            'screenSize' => 'sm',
            'page' => 1
        ]);
        $view->call('prevPage');

        $view->assertSet('page', 0);
        $this->assertExamplesSliceInView(
            $view,
            $this->form->examples,
            0,
            Examples::maxSizeFor('sm')
        );
    }

    /** @test */
    public function it_does_not_show_the_next_page_if_there_are_no_more_pages()
    {
        $view = $this->livewire(Examples::class, [
            'model' => $this->form,
            'screenSize' => 'sm',
            'page' => 5
        ]);
        $view->call('nextPage');

        $view->assertSet('page', 5);
    }

    /** @test */
    public function the_filter_is_taken_into_account_when_deciding_if_there_are_more_pages()
    {
        $view = $this->livewire(Examples::class, [
            'model' => $this->form,
            'screenSize' => 'sm',
            'page' => 0
        ]);

        $view->set('filter', 'fooexample');
        $view->call('nextPage');

        $view->assertSet('page', 0);
    }

    /** @test */
    public function it_does_not_show_the_previous_page_if_there_are_no_more_pages()
    {
        $view = $this->livewire(Examples::class, [
            'model' => $this->form,
            'page' => 0
        ]);
        $view->call('prevPage');

        $view->assertSet('page', 0);
    }

    /** @test */
    public function it_adjusts_form_count_by_screen_size()
    {
        foreach (['sm', 'md', 'lg', 'xl'] as $size) {
            $view = $this->livewire(Examples::class, ['model' => $this->form, 'screenSize' => $size]);
            $this->assertExamplesSliceInView($view, $this->form->examples, 0, Examples::maxSizeFor($size));
        }
    }

    /** @test */
    public function it_resizes()
    {
        $view = $this->livewire(Examples::class, ['model' => $this->form, 'screenSize' => 'sm']);
        $this->assertExamplesSliceInView($view, $this->form->examples, 0, Examples::maxSizeFor('sm'));

        $view->emit('resize', 'md');

        $this->assertExamplesSliceInView($view, $this->form->examples, 0, Examples::maxSizeFor('md'));
    }
}
