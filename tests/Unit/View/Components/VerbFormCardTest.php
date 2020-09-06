<?php

namespace Tests\Unit\View\Components;

use App\Models\Feature;
use App\Models\VerbClass;
use App\Models\VerbForm;
use App\Models\VerbMode;
use App\Models\VerbOrder;
use App\Models\VerbStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerbFormCardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_a_formatted_shape()
    {
        $form = factory(VerbForm::class)->create(['shape' => 'V-foo']);

        $view = $this->blade('<x-verb-form-card :form="$form" />', compact('form'));

        $view->assertSee($form->formatted_shape, false);
    }

    /** @test */
    public function it_shows_the_feature_string()
    {
        $form = factory(VerbForm::class)->create([
            'structure_id' => factory(VerbStructure::class)->create([
                'subject_name' => factory(Feature::class)->create(['name' => '1s']),
                'head' => 'subject'
            ])
        ]);

        $view = $this->blade('<x-verb-form-card :form="$form" />', compact('form'));

        $view->assertSee('<u>1s</u>', false);
    }

    /** @test */
    public function it_shows_its_mode()
    {
        $form = factory(VerbForm::class)->create([
            'structure_id' => factory(VerbStructure::class)->create([
                'mode_name' => factory(VerbMode::class)->create(['name' => 'themode']),
            ])
        ]);

        $view = $this->blade('<x-verb-form-card :form="$form" />', compact('form'));

        $view->assertSee('themode');
    }

    /** @test */
    public function it_shows_its_order()
    {
        $form = factory(VerbForm::class)->create([
            'structure_id' => factory(VerbStructure::class)->create([
                'order_name' => factory(VerbOrder::class)->create(['name' => 'theorder']),
            ])
        ]);

        $view = $this->blade('<x-verb-form-card :form="$form" />', compact('form'));

        $view->assertSee('theorder');
    }

    /** @test */
    public function it_shows_its_class()
    {
        $form = factory(VerbForm::class)->create([
            'structure_id' => factory(VerbStructure::class)->create([
                'class_abv' => factory(VerbClass::class)->create(['abv' => 'theclass']),
            ])
        ]);

        $view = $this->blade('<x-verb-form-card :form="$form" />', compact('form'));

        $view->assertSee('theclass');
    }
}
