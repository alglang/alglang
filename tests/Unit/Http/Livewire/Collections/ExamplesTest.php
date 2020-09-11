<?php

namespace Tests\Unit\Http\Livewire\Collections;

use App\Http\Livewire\Collections\Examples;
use App\Models\Example;
use App\Models\Form;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ExamplesTest extends TestCase
{
    use RefreshDatabase;

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
        $form = Form::factory()->hasExamples(2)->create();

        $view = $this->livewire(Examples::class, ['model' => $form]);

        foreach ($form->examples as $example) {
            $view->assertSeeHtml($example->formatted_shape);
        }
    }

    /** @test */
    public function it_filters_by_shape()
    {
        $form = Form::factory()->create();
        $example1 = Example::factory()->create(['shape' => 'fooexample', 'form_id' => $form]);
        $example2 = Example::factory()->create(['shape' => 'barexample', 'form_id' => $form]);
        $view = $this->livewire(Examples::class, ['model' => $form]);

        $view->set('filter', 'fooexam');

        $view->assertSeeHtml($example1->formatted_shape);
        $view->assertDontSeeHtml($example2->formatted_shape);
    }

    /** @test */
    public function it_resets_the_page_when_a_filter_is_applied()
    {
        $form = Form::factory()->create();
        $view = $this->livewire(Examples::class, ['model' => $form, 'page' => 2]);

        $view->set('filter', 'fooexample');

        $view->assertSet('page', 0);
    }

    /** @test */
    public function it_shows_the_next_page()
    {
        $form = Form::factory()->hasExamples(Examples::maxSizeFor('sm') * 2)->create();
        $view = $this->livewire(Examples::class, [
            'model' => $form,
            'screenSize' => 'sm',
            'page' => 0
        ]);

        $view->call('nextPage');

        $view->assertSet('page', 1);
        $this->assertExamplesSliceInView(
            $view,
            $form->examples,
            Examples::maxSizeFor('sm'),
            Examples::maxSizeFor('sm') * 2
        );
    }

    /** @test */
    public function it_shows_the_previous_page()
    {
        $form = Form::factory()->hasExamples(Examples::maxSizeFor('sm') + 1)->create();
        $view = $this->livewire(Examples::class, [
            'model' => $form,
            'screenSize' => 'sm',
            'page' => 1
        ]);

        $view->call('prevPage');

        $view->assertSet('page', 0);
        $this->assertExamplesSliceInView(
            $view,
            $form->examples,
            0,
            Examples::maxSizeFor('sm')
        );
    }

    /** @test */
    public function it_does_not_show_the_next_page_if_there_are_no_more_pages()
    {
        $form = Form::factory()->hasExamples(Examples::maxSizeFor('sm'))->create();
        $view = $this->livewire(Examples::class, [
            'model' => $form,
            'screenSize' => 'sm'
        ]);
        $view->assertSet('page', 0);

        $view->call('nextPage');

        $view->assertSet('page', 0);
    }

    /** @test */
    public function the_filter_is_taken_into_account_when_deciding_if_there_are_more_pages()
    {
        $form = Form::factory()->hasExamples(Examples::maxSizeFor('sm'))->create();
        Example::factory()->create(['shape' => 'mxyzptlk', 'form_id' => $form]);
        $view = $this->livewire(Examples::class, [
            'model' => $form,
            'screenSize' => 'sm',
        ]);
        $view->assertSet('page', 0);
        $view->assertSet('hasMorePages', true);

        $view->set('filter', 'mxyzptlk');
        $view->call('nextPage');

        $view->assertSet('hasMorePages', false);
        $view->assertSet('page', 0);
    }

    /** @test */
    public function it_does_not_show_the_previous_page_if_there_are_no_more_pages()
    {
        $form = Form::factory()->hasExamples(1)->create();

        $view = $this->livewire(Examples::class, [
            'model' => $form,
            'page' => 0
        ]);
        $view->call('prevPage');

        $view->assertSet('page', 0);
    }

    /** @test */
    public function it_adjusts_form_count_by_screen_size()
    {
        $form = Form::factory()->hasExamples(Examples::maxSizeFor('xl') + 1)->create();

        foreach (['sm', 'md', 'lg', 'xl'] as $size) {
            $view = $this->livewire(Examples::class, ['model' => $form, 'screenSize' => $size]);
            $this->assertExamplesSliceInView($view, $form->examples, 0, Examples::maxSizeFor($size));
        }
    }

    /** @test */
    public function it_resizes()
    {
        $form = Form::factory()->hasExamples(Examples::maxSizeFor('md'))->create();
        $view = $this->livewire(Examples::class, ['model' => $form, 'screenSize' => 'sm']);
        $this->assertExamplesSliceInView($view, $form->examples, 0, Examples::maxSizeFor('sm'));

        $view->emit('resize', 'md');

        $this->assertExamplesSliceInView($view, $form->examples, 0, Examples::maxSizeFor('md'));
    }
}
