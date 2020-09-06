<?php

namespace Tests\Unit\Http\Livewire\Collections;

use App\Http\Livewire\Collections\VerbForms;
use App\Models\Language;
use App\Models\VerbForm;
use App\Models\VerbStructure;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class VerbFormsTest extends TestCase
{
    /** @var bool */
    public static $seeded = false;

    /** @var Language */
    protected $language;

    /** @var VerbForm */
    protected $form1;

    /** @var VerbForm */
    protected $form2;

    public function setUp(): void
    {
        parent::setUp();

        if (!static::$seeded) {
            // Ensure the database has been migrated
            Artisan::call('migrate');

            // Ensure sources were cleaned up from earlier runs
            DB::table('languages')->delete();
            DB::table('forms')->delete();

            $language = factory(Language::class)->create(['name' => 'Seed language']);
            $structure = factory(VerbStructure::class)->create();

            factory(VerbForm::class)->create([
                'shape' => 'V-foo',
                'language_code' => $language,
                'structure_id' => $structure
            ]);
            factory(VerbForm::class)->create([
                'shape' => 'V-bar',
                'language_code' => $language,
                'structure_id' => $structure
            ]);
            factory(VerbForm::class, VerbForms::maxSizeFor('xl') + 1)->create([
                'language_code' => $language,
                'structure_id' => $structure
            ]);
            static::$seeded = true;
        }

        $this->language = Language::where('name', 'Seed language')->first();
        $this->form1 = VerbForm::where('shape', 'V-foo')->first();
        $this->form2 = VerbForm::where('shape', 'V-bar')->first();
    }

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
    public function it_shows_verb_forms_from_a_language()
    {
        $view = $this->livewire(VerbForms::class, ['model' => $this->language]);

        $view->assertSeeHtml($this->form1->formatted_shape);
        $view->assertSeeHtml($this->form2->formatted_shape);
    }

    /** @test */
    public function it_filters_by_shape()
    {
        $view = $this->livewire(VerbForms::class, ['model' => $this->language]);

        $view->set('filter', 'V-f');

        $view->assertSeeHtml($this->form1->formatted_shape);
        $view->assertDontSeeHtml($this->form2->formatted_shape);
    }

    /** @test */
    public function it_resets_the_page_when_a_filter_is_applied()
    {
        
        $view = $this->livewire(VerbForms::class, ['model' => $this->language, 'page' => 2]);

        $view->set('filter', 'V-f');

        $view->assertSet('page', 0);
    }

    /** @test */
    public function it_shows_the_next_page()
    {
        $view = $this->livewire(VerbForms::class, [
            'model' => $this->language,
            'screenSize' => 'sm'
        ]);
        $view->call('nextPage');

        $view->assertSet('page', 1);
        $this->assertFormsSliceInView(
            $view,
            $this->language->verbForms,
            VerbForms::maxSizeFor('sm'),
            VerbForms::maxSizeFor('sm') * 2
        );
    }

    /** @test */
    public function it_shows_the_previous_page()
    {
        $view = $this->livewire(VerbForms::class, [
            'model' => $this->language,
            'screenSize' => 'sm',
            'page' => 1
        ]);
        $view->call('prevPage');

        $view->assertSet('page', 0);
        $this->assertFormsSliceInView(
            $view,
            $this->language->verbForms,
            0,
            VerbForms::maxSizeFor('sm')
        );
    }

    /** @test */
    public function it_does_not_show_the_next_page_if_there_are_no_more_pages()
    {
        $view = $this->livewire(VerbForms::class, [
            'model' => $this->language,
            'screenSize' => 'sm',
            'page' => 5
        ]);
        $view->call('nextPage');

        $view->assertSet('page', 5);
    }

    /** @test */
    public function the_filter_is_taken_into_account_when_deciding_if_there_are_more_pages()
    {
        $view = $this->livewire(VerbForms::class, [
            'model' => $this->language,
            'screenSize' => 'sm',
            'page' => 0
        ]);

        $view->set('filter', 'V-foo');
        $view->call('nextPage');

        $view->assertSet('page', 0);
    }

    /** @test */
    public function it_does_not_show_the_previous_page_if_there_are_no_more_pages()
    {
        $view = $this->livewire(VerbForms::class, [
            'model' => $this->language,
            'page' => 0
        ]);
        $view->call('prevPage');

        $view->assertSet('page', 0);
    }

    /** @test */
    public function it_adjusts_form_count_by_screen_size()
    {
        foreach (['sm', 'md', 'lg', 'xl'] as $size) {
            $view = $this->livewire(VerbForms::class, ['model' => $this->language, 'screenSize' => $size]);
            $this->assertFormsSliceInView($view, $this->language->verbForms, 0, VerbForms::maxSizeFor($size));
        }
    }

    /** @test */
    public function it_resizes()
    {
        $view = $this->livewire(VerbForms::class, ['model' => $this->language, 'screenSize' => 'sm']);
        $this->assertFormsSliceInView($view, $this->language->verbForms, 0, VerbForms::maxSizeFor('sm'));

        $view->emit('resize', 'md');

        $this->assertFormsSliceInView($view, $this->language->verbForms, 0, VerbForms::maxSizeFor('md'));
    }
}
