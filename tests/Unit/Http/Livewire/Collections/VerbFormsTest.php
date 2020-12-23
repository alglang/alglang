<?php

namespace Tests\Unit\Http\Livewire\Collections;

use App\Http\Livewire\Collections\VerbForms;
use App\Models\Language;
use App\Models\VerbForm;
use App\Models\VerbStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Livewire\Testing\TestableLivewire;
use Tests\TestCase;

class VerbFormsTest extends TestCase
{
    use RefreshDatabase;

    protected function assertFormsSliceInView($view, $forms, $start, $end): void
    {
        $view->assertSeeHtml($forms[$start]->formatted_shape);
        $view->assertSeeHtml($forms[$end - 1]->formatted_shape);

        if ($start > 0) {
            $view->assertDontSeeHtml($forms[$start - 1]->formatted_shape);
        }

        if ($end < $forms->count()) {
            $view->assertDontSeeHtml($forms[$end]->formatted_shape);
        }
    }

    protected function navigateToVerbFormsComponent(array $attrs): TestableLivewire
    {
        $view = $this->livewire(VerbForms::class, $attrs);
        $view->emit('tabChanged', 'verb_forms');
        return $view;
    }

    /** @test */
    public function it_loads_when_the_tab_changes_to_verb_forms(): void
    {
        $language = Language::factory()->hasVerbForms(['shape' => 'V-foo'])->create();
        $view = $this->livewire(VerbForms::class, ['model' => $language]);
        $view->assertDontSee('V-foo');
        
        $view->emit('tabChanged', 'verb_forms');

        $view->assertSee('V-foo');
    }

    /** @test */
    public function it_shows_verb_forms_from_a_language()
    {
        $language = Language::factory()->hasVerbForms(2)->create();
        $view = $this->navigateToVerbFormsComponent(['model' => $language]);

        foreach ($language->forms as $form) {
            $view->assertSeeHtml($form->formatted_shape);
        }
    }

    /** @test */
    public function it_includes_gaps()
    {
        $language = Language::factory()->hasVerbGaps(1)->create();

        $view = $this->navigateToVerbFormsComponent(['model' => $language]);

        $view->assertSeeHtml('No form');
    }

    /** @test */
    public function it_filters_by_shape()
    {
        $language = Language::factory()->create();
        $form1 = VerbForm::factory()->create(['language_code' => $language, 'shape' => 'V-foo']);
        $form2 = VerbForm::factory()->create(['language_code' => $language, 'shape' => 'V-bar']);
        $view = $this->navigateToVerbFormsComponent(['model' => $language]);

        $view->set('filter', 'V-f');

        $view->assertSeeHtml($form1->formatted_shape);
        $view->assertDontSeeHtml($form2->formatted_shape);
    }

    /** @test */
    public function gaps_are_not_included_if_there_is_a_name_filter()
    {
        $language = Language::factory()->hasVerbGaps(1)->create();
        $view = $this->navigateToVerbFormsComponent(['model' => $language]);
        $view->assertSeeHtml('No form');

        $view->set('filter', 'N');

        $view->assertDontSeeHtml('No form');
    }

    /** @test */
    public function it_resets_the_page_when_a_filter_is_applied()
    {
        $language = Language::factory()->hasVerbForms(VerbForms::maxSizeFor('sm'))->create();
        VerbForm::factory()->create(['language_code' => $language, 'shape' => 'V-mxyzptlk']);
        $view = $this->navigateToVerbFormsComponent(['model' => $language, 'page' => 1]);

        $view->set('filter', 'V-mxyzpt');

        $view->assertSet('page', 0);
    }

    /** @test */
    public function it_shows_the_next_page()
    {
        $language = Language::factory()->hasVerbForms(VerbForms::maxSizeFor('sm')+1)->create();
        $view = $this->navigateToVerbFormsComponent([
            'model' => $language,
            'screenSize' => 'sm'
        ]);
        $view->assertSet('page', 0);

        $view->call('nextPage');

        $view->assertSet('page', 1);
        $this->assertFormsSliceInView(
            $view,
            $language->verbForms,
            VerbForms::maxSizeFor('sm'),
            $language->verbForms->count()
        );
    }

    /** @test */
    public function it_shows_the_previous_page()
    {
        $language = Language::factory()->hasVerbForms(VerbForms::maxSizeFor('sm')+1)->create();
        $view = $this->navigateToVerbFormsComponent([
            'model' => $language,
            'screenSize' => 'sm',
            'page' => 1
        ]);

        $view->call('prevPage');

        $view->assertSet('page', 0);
        $this->assertFormsSliceInView(
            $view,
            $language->verbForms,
            0,
            VerbForms::maxSizeFor('sm')
        );
    }

    /** @test */
    public function it_does_not_show_the_next_page_if_there_are_no_more_pages()
    {
        $language = Language::factory()->hasVerbForms(1)->create();
        $view = $this->navigateToVerbFormsComponent([
            'model' => $language,
            'screenSize' => 'sm',
            'page' => 0
        ]);

        $view->call('nextPage');

        $view->assertSet('page', 0);
    }

    /** @test */
    public function the_filter_is_taken_into_account_when_deciding_if_there_are_more_pages()
    {
        $language = Language::factory()->hasVerbForms(VerbForms::maxSizeFor('sm'))->create();
        VerbForm::factory()->create(['shape' => 'V-mxyzptlk', 'language_code' => $language]);
        $view = $this->navigateToVerbFormsComponent([
            'model' => $language,
            'screenSize' => 'sm',
        ]);
        $view->assertSet('hasMorePages', true);
        $view->assertSet('page', 0);

        $view->set('filter', 'mxyzptlk');
        $view->call('nextPage');

        $view->assertSet('hasMorePages', false);
        $view->assertSet('page', 0);
    }

    /** @test */
    public function it_does_not_show_the_previous_page_if_there_are_no_more_pages()
    {
        $language = Language::factory()->hasVerbForms(1)->create();
        $view = $this->navigateToVerbFormsComponent(['model' => $language]);
        $view->assertSet('page', 0);

        $view->call('prevPage');

        $view->assertSet('page', 0);
    }

    /**
     * @test
     * @group slow
     */
    public function it_adjusts_form_count_by_screen_size()
    {
        $language = Language::factory()->hasVerbForms(VerbForms::maxSizeFor('xl')+1)->create();
        foreach (['sm', 'md', 'lg', 'xl'] as $size) {
            $view = $this->navigateToVerbFormsComponent(['model' => $language, 'screenSize' => $size]);
            $this->assertFormsSliceInView($view, $language->verbForms, 0, VerbForms::maxSizeFor($size));
        }
    }

    /** @test */
    public function it_resizes()
    {
        $language = Language::factory()->hasVerbForms(VerbForms::maxSizeFor('md'))->create();
        $view = $this->navigateToVerbFormsComponent(['model' => $language, 'screenSize' => 'sm']);
        $this->assertFormsSliceInView($view, $language->verbForms, 0, VerbForms::maxSizeFor('sm'));

        $view->emit('resize', 'md');

        $this->assertFormsSliceInView($view, $language->verbForms, 0, VerbForms::maxSizeFor('md'));
    }
}
