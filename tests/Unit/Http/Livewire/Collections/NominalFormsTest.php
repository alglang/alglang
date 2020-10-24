<?php

namespace Tests\Unit\Http\Livewire\Collections;

use App\Http\Livewire\Collections\NominalForms;
use App\Models\Language;
use App\Models\NominalForm;
use App\Models\NominalStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class NominalFormsTest extends TestCase
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

    /** @test */
    public function it_shows_nominal_forms_from_a_language()
    {
        $language = Language::factory()->hasNominalForms(2)->create();
        $view = $this->livewire(NominalForms::class, ['model' => $language]);

        foreach ($language->forms as $form) {
            $view->assertSeeHtml($form->formatted_shape);
        }
    }

    /** @test */
    public function it_includes_gaps()
    {
        $language = Language::factory()->hasNominalGaps(1)->create();

        $view = $this->livewire(NominalForms::class, ['model' => $language]);

        $view->assertSeeHtml('No form');
    }

    /** @test */
    public function gaps_are_not_included_if_there_is_a_name_filter()
    {
        $language = Language::factory()->hasNominalGaps(1)->create();
        $view = $this->livewire(NominalForms::class, ['model' => $language]);
        $view->assertSeeHtml('No form');

        $view->set('filter', 'N');

        $view->assertDontSeeHtml('No form');
    }

    /** @test */
    public function it_filters_by_shape()
    {
        $language = Language::factory()->create();
        $form1 = NominalForm::factory()->create(['language_code' => $language, 'shape' => 'N-foo']);
        $form2 = NominalForm::factory()->create(['language_code' => $language, 'shape' => 'N-bar']);
        $view = $this->livewire(NominalForms::class, ['model' => $language]);

        $view->set('filter', 'N-f');

        $view->assertSeeHtml($form1->formatted_shape);
        $view->assertDontSeeHtml($form2->formatted_shape);
    }

    /** @test */
    public function it_resets_the_page_when_a_filter_is_applied()
    {
        $language = Language::factory()->hasNominalForms(NominalForms::maxSizeFor('sm'))->create();
        NominalForm::factory()->create(['language_code' => $language, 'shape' => 'N-mxyzptlk']);
        $view = $this->livewire(NominalForms::class, ['model' => $language, 'page' => 1]);

        $view->set('filter', 'N-mxyzpt');

        $view->assertSet('page', 0);
    }

    /** @test */
    public function it_shows_the_next_page()
    {
        $language = Language::factory()->hasNominalForms(NominalForms::maxSizeFor('sm')+1)->create();
        $view = $this->livewire(NominalForms::class, [
            'model' => $language,
            'screenSize' => 'sm'
        ]);
        $view->assertSet('page', 0);

        $view->call('nextPage');

        $view->assertSet('page', 1);
        $this->assertFormsSliceInView(
            $view,
            $language->nominalForms,
            NominalForms::maxSizeFor('sm'),
            $language->nominalForms->count()
        );
    }

    /** @test */
    public function it_shows_the_previous_page()
    {
        $language = Language::factory()->hasNominalForms(NominalForms::maxSizeFor('sm')+1)->create();
        $view = $this->livewire(NominalForms::class, [
            'model' => $language,
            'screenSize' => 'sm',
            'page' => 1
        ]);

        $view->call('prevPage');

        $view->assertSet('page', 0);
        $this->assertFormsSliceInView(
            $view,
            $language->nominalForms,
            0,
            NominalForms::maxSizeFor('sm')
        );
    }

    /** @test */
    public function it_does_not_show_the_next_page_if_there_are_no_more_pages()
    {
        $language = Language::factory()->hasNominalForms(1)->create();
        $view = $this->livewire(NominalForms::class, [
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
        $language = Language::factory()->hasNominalForms(NominalForms::maxSizeFor('sm'))->create();
        NominalForm::factory()->create(['shape' => 'N-mxyzptlk', 'language_code' => $language]);
        $view = $this->livewire(NominalForms::class, [
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
        $language = Language::factory()->hasNominalForms(1)->create();
        $view = $this->livewire(NominalForms::class, ['model' => $language]);
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
        $language = Language::factory()->hasNominalForms(NominalForms::maxSizeFor('xl')+1)->create();
        foreach (['sm', 'md', 'lg', 'xl'] as $size) {
            $view = $this->livewire(NominalForms::class, ['model' => $language, 'screenSize' => $size]);
            $this->assertFormsSliceInView($view, $language->nominalForms, 0, NominalForms::maxSizeFor($size));
        }
    }

    /** @test */
    public function it_resizes()
    {
        $language = Language::factory()->hasNominalForms(NominalForms::maxSizeFor('md'))->create();
        $view = $this->livewire(NominalForms::class, ['model' => $language, 'screenSize' => 'sm']);
        $this->assertFormsSliceInView($view, $language->nominalForms, 0, NominalForms::maxSizeFor('sm'));

        $view->emit('resize', 'md');

        $this->assertFormsSliceInView($view, $language->nominalForms, 0, NominalForms::maxSizeFor('md'));
    }
}
