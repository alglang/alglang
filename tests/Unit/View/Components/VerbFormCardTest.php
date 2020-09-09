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
        $form = VerbForm::factory()->create(['shape' => 'V-foo']);

        $view = $this->blade('<x-verb-form-card :form="$form" />', compact('form'));

        $view->assertSee($form->formatted_shape, false);
    }

    /** @test */
    public function it_shows_the_feature_string()
    {
        $form = VerbForm::factory()->create([
            'structure_id' => VerbStructure::factory()->create([
                'subject_name' => Feature::factory()->create(['name' => '1s']),
                'head' => 'subject'
            ])
        ]);

        $view = $this->blade('<x-verb-form-card :form="$form" />', compact('form'));

        $view->assertSee('<u>1s</u>', false);
    }

    /** @test */
    public function it_shows_its_mode()
    {
        $form = VerbForm::factory()->create([
            'structure_id' => VerbStructure::factory()->create([
                'mode_name' => VerbMode::factory()->create(['name' => 'themode']),
            ])
        ]);

        $view = $this->blade('<x-verb-form-card :form="$form" />', compact('form'));

        $view->assertSee('themode');
    }

    /** @test */
    public function it_shows_its_order()
    {
        $form = VerbForm::factory()->create([
            'structure_id' => VerbStructure::factory()->create([
                'order_name' => VerbOrder::factory()->create(['name' => 'theorder']),
            ])
        ]);

        $view = $this->blade('<x-verb-form-card :form="$form" />', compact('form'));

        $view->assertSee('theorder');
    }

    /** @test */
    public function it_shows_its_class()
    {
        $form = VerbForm::factory()->create([
            'structure_id' => VerbStructure::factory()->create([
                'class_abv' => VerbClass::factory()->create(['abv' => 'theclass']),
            ])
        ]);

        $view = $this->blade('<x-verb-form-card :form="$form" />', compact('form'));

        $view->assertSee('theclass');
    }
}
