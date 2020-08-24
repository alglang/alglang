<?php

namespace Tests\Unit;

use App\Feature;
use App\Language;
use App\VerbClass;
use App\VerbForm;
use App\VerbMode;
use App\VerbOrder;
use App\VerbSearch;
use App\VerbStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerbSearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_orders_forms_by_language()
    {
        $form2 = factory(VerbForm::class)->create([
            'language_code' => factory(Language::class)->create(['name' => 'Test Language 2']),
            'shape' => 'V-foo'
        ]);
        $form1 = factory(VerbForm::class)->create([
            'language_code' => factory(Language::class)->create(['name' => 'Test Language 1']),
            'shape' => 'V-bar'
        ]);

        $results = VerbSearch::search([
            'languages' => [$form1->language_code, $form2->language_code]
        ]);

        $this->assertEquals(['Test Language 1', 'Test Language 2'], $results->pluck('language.name')->toArray());
        $this->assertEquals(['V-bar', 'V-foo'], $results->pluck('shape')->toArray());
    }

    /** @test */
    public function it_filters_search_results_by_language()
    {
        $language = factory(Language::class)->create();
        factory(VerbForm::class)->create([
            'language_code' => $language,
            'shape' => 'V-foo'
        ]);
        factory(VerbForm::class)->create([
            'language_code' => factory(Language::class)->create(),
            'shape' => 'V-bar'
        ]);

        $forms = VerbSearch::search(['languages' => [$language->code]]);

        $this->assertCount(1, $forms);
        $this->assertEquals('V-foo', $forms[0]->shape);
    }

    /** @test */
    public function it_filters_search_results_by_multiple_languages()
    {
        $language1 = factory(Language::class)->create();
        $language2 = factory(Language::class)->create();
        factory(VerbForm::class)->create([
            'language_code' => $language1,
            'shape' => 'V-foo'
        ]);
        factory(VerbForm::class)->create([
            'language_code' => $language2,
            'shape' => 'V-baz'
        ]);
        factory(VerbForm::class)->create([
            'language_code' => factory(Language::class)->create(),
            'shape' => 'V-bar'
        ]);

        $forms = VerbSearch::search([
            'languages' => [$language1->code, $language2->code]
        ]);

        $this->assertCount(2, $forms);
        $this->assertEquals(['V-foo', 'V-baz'], $forms->pluck('shape')->toArray());
    }

    /** @test */
    public function it_filters_search_results_by_mode()
    {
        $mode = factory(VerbMode::class)->create();
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'mode_name' => $mode
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'mode_name' => factory(VerbMode::class)->create()
            ])
        ]);

        $forms = VerbSearch::search(['modes' => [$mode->name]]);

        $this->assertCount(1, $forms);
        $this->assertEquals('V-foo', $forms[0]->shape);
    }

    /** @test */
    public function it_filters_search_results_by_multiple_modes()
    {
        $mode1 = factory(VerbMode::class)->create();
        $mode2 = factory(VerbMode::class)->create();
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'mode_name' => $mode1
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-baz',
            'structure_id' => factory(VerbStructure::class)->create([
                'mode_name' => $mode2
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'mode_name' => factory(VerbMode::class)->create()
            ])
        ]);

        $forms = VerbSearch::search([
            'modes' => [$mode1->name, $mode2->name]
        ]);

        $this->assertCount(2, $forms);
        $this->assertEquals(['V-foo', 'V-baz'], $forms->pluck('shape')->toArray());
    }

    /** @test */
    public function it_filters_search_results_by_order()
    {
        $order = factory(VerbOrder::class)->create();
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'order_name' => $order
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'order_name' => factory(VerbOrder::class)->create()
            ])
        ]);

        $forms = VerbSearch::search(['orders' => [$order->name]]);

        $this->assertCount(1, $forms);
        $this->assertEquals('V-foo', $forms[0]->shape);
    }

    /** @test */
    public function it_filters_search_results_by_multiple_orders()
    {
        $order1 = factory(VerbOrder::class)->create();
        $order2 = factory(VerbOrder::class)->create();
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'order_name' => $order1
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-baz',
            'structure_id' => factory(VerbStructure::class)->create([
                'order_name' => $order2
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'order_name' => factory(VerbOrder::class)->create()
            ])
        ]);

        $forms = VerbSearch::search([
            'orders' => [$order1->name, $order2->name]
        ]);

        $this->assertCount(2, $forms);
        $this->assertEquals(['V-foo', 'V-baz'], $forms->pluck('shape')->toArray());
    }

    /** @test */
    public function it_filters_search_results_by_class()
    {
        $class = factory(VerbClass::class)->create();
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'class_abv' => $class
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'class_abv' => factory(VerbClass::class)->create()
            ])
        ]);

        $forms = VerbSearch::search(['classes' => [$class->abv]]);

        $this->assertCount(1, $forms);
        $this->assertEquals('V-foo', $forms[0]->shape);
    }

    /** @test */
    public function it_filters_search_results_by_multiple_classes()
    {
        $class1 = factory(VerbClass::class)->create();
        $class2 = factory(VerbClass::class)->create();
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'class_abv' => $class1
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-baz',
            'structure_id' => factory(VerbStructure::class)->create([
                'class_abv' => $class2
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'class_abv' => factory(VerbClass::class)->create()
            ])
        ]);

        $forms = VerbSearch::search(['classes' => [
            $class1->abv, $class2->abv]
        ]);

        $this->assertCount(2, $forms);
        $this->assertEquals(['V-foo', 'V-baz'], $forms->pluck('shape')->toArray());
    }

    /** @test */
    public function it_filters_search_results_by_negativity()
    {
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'is_negative' => false
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'is_negative' => true
            ])
        ]);

        $forms = VerbSearch::search(['negative' => false]);

        $this->assertCount(1, $forms);
        $this->assertEquals('V-foo', $forms[0]->shape);
    }

    /** @test */
    public function it_filters_search_results_by_diminutivity()
    {
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'is_diminutive' => true
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'is_diminutive' => false
            ])
        ]);

        $forms = VerbSearch::search(['diminutive' => true]);

        $this->assertCount(1, $forms);
        $this->assertEquals('V-foo', $forms[0]->shape);
    }

    /** @test */
    public function it_filters_search_results_by_subject_person()
    {
        $feature = factory(Feature::class)->create(['person' => '1']);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'subject_name' => $feature
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'subject_name' => factory(Feature::class)->create([
                    'person' => '2'
                ])
            ])
        ]);

        $forms = VerbSearch::search(['subject_persons' => [$feature->person]]);

        $this->assertCount(1, $forms);
        $this->assertEquals('V-foo', $forms[0]->shape);
    }

    /** @test */
    public function it_filters_search_results_by_multiple_subject_persons()
    {
        $feature1 = factory(Feature::class)->create(['person' => '1']);
        $feature2 = factory(Feature::class)->create(['person' => '3']);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'subject_name' => $feature1
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-baz',
            'structure_id' => factory(VerbStructure::class)->create([
                'subject_name' => $feature2
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'subject_name' => factory(Feature::class)->create([
                    'person' => '2'
                ])
            ])
        ]);

        $forms = VerbSearch::search([
            'subject_persons' => [$feature1->person, $feature2->person]
        ]);

        $this->assertCount(2, $forms);
        $this->assertEquals(['V-foo', 'V-baz'], $forms->pluck('shape')->toArray());
    }

    /** @test */
    public function it_filters_search_results_by_subject_number()
    {
        $feature = factory(Feature::class)->create(['number' => 1]);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'subject_name' => $feature
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'subject_name' => factory(Feature::class)->create([
                    'number' => 2
                ])
            ])
        ]);

        $forms = VerbSearch::search(['subject_numbers' => [$feature->number]]);

        $this->assertCount(1, $forms);
        $this->assertEquals('V-foo', $forms[0]->shape);
    }

    /** @test */
    public function it_filters_search_results_by_multiple_subject_numbers()
    {
        $feature1 = factory(Feature::class)->create(['number' => 1]);
        $feature2 = factory(Feature::class)->create(['number' => 3]);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'subject_name' => $feature1
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-baz',
            'structure_id' => factory(VerbStructure::class)->create([
                'subject_name' => $feature2
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'subject_name' => factory(Feature::class)->create([
                    'number' => 2
                ])
            ])
        ]);

        $forms = VerbSearch::search([
            'subject_numbers' => [$feature1->number, $feature2->number]
        ]);

        $this->assertCount(2, $forms);
        $this->assertEquals(['V-foo', 'V-baz'], $forms->pluck('shape')->toArray());
    }

    /** @test */
    public function it_filters_search_results_by_subject_obviative_code()
    {
        $feature = factory(Feature::class)->create(['obviative_code' => 1]);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'subject_name' => $feature
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'subject_name' => factory(Feature::class)->create([
                    'obviative_code' => null
                ])
            ])
        ]);

        $forms = VerbSearch::search(['subject_obviative_codes' => [$feature->obviative_code]]);

        $this->assertCount(1, $forms);
        $this->assertEquals('V-foo', $forms[0]->shape);
    }

    /** @test */
    public function it_filters_search_results_by_multiple_subject_obviative_codes()
    {
        $feature1 = factory(Feature::class)->create(['obviative_code' => 1]);
        $feature2 = factory(Feature::class)->create(['obviative_code' => 2]);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'subject_name' => $feature1
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-baz',
            'structure_id' => factory(VerbStructure::class)->create([
                'subject_name' => $feature2
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'subject_name' => factory(Feature::class)->create([
                    'obviative_code' => null
                ])
            ])
        ]);

        $forms = VerbSearch::search([
            'subject_obviative_codes' => [$feature1->obviative_code, $feature2->obviative_code]
        ]);

        $this->assertCount(2, $forms);
        $this->assertEquals(['V-foo', 'V-baz'], $forms->pluck('shape')->toArray());
    }

    /** @test */
    public function it_can_exclude_verb_forms_with_primary_objects()
    {
        $feature = factory(Feature::class)->create(['person' => '1']);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'subject_name' => $feature
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'subject_name' => $feature,
                'primary_object_name' => $feature
            ])
        ]);

        $forms = VerbSearch::search([
            'subject_persons' => [$feature->person],
            'primary_object' => 0
        ]);

        $this->assertCount(1, $forms);
        $this->assertEquals('V-foo', $forms[0]->shape);
    }

    /** @test */
    public function it_filters_search_results_by_primary_object_person()
    {
        $feature = factory(Feature::class)->create(['person' => '1']);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'primary_object_name' => $feature
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'primary_object_name' => factory(Feature::class)->create([
                    'person' => '2'
                ])
            ])
        ]);

        $forms = VerbSearch::search(['primary_object_persons' => [$feature->person]]);

        $this->assertCount(1, $forms);
        $this->assertEquals('V-foo', $forms[0]->shape);
    }

    /** @test */
    public function it_filters_search_results_by_multiple_primary_object_persons()
    {
        $feature1 = factory(Feature::class)->create(['person' => '1']);
        $feature2 = factory(Feature::class)->create(['person' => '3']);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'primary_object_name' => $feature1
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-baz',
            'structure_id' => factory(VerbStructure::class)->create([
                'primary_object_name' => $feature2
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'primary_object_name' => factory(Feature::class)->create([
                    'person' => '2'
                ])
            ])
        ]);

        $forms = VerbSearch::search([
            'primary_object_persons' => [$feature1->person, $feature2->person]
        ]);

        $this->assertCount(2, $forms);
        $this->assertEquals(['V-foo', 'V-baz'], $forms->pluck('shape')->toArray());
    }

    /** @test */
    public function it_filters_search_results_by_primary_object_number()
    {
        $feature = factory(Feature::class)->create(['number' => 1]);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'primary_object_name' => $feature
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'primary_object_name' => factory(Feature::class)->create([
                    'number' => 2
                ])
            ])
        ]);

        $forms = VerbSearch::search(['primary_object_numbers' => [$feature->number]]);

        $this->assertCount(1, $forms);
        $this->assertEquals('V-foo', $forms[0]->shape);
    }

    /** @test */
    public function it_filters_search_results_by_multiple_primary_object_numbers()
    {
        $feature1 = factory(Feature::class)->create(['number' => 1]);
        $feature2 = factory(Feature::class)->create(['number' => 3]);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'primary_object_name' => $feature1
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-baz',
            'structure_id' => factory(VerbStructure::class)->create([
                'primary_object_name' => $feature2
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'primary_object_name' => factory(Feature::class)->create([
                    'number' => 2
                ])
            ])
        ]);

        $forms = VerbSearch::search([
            'primary_object_numbers' => [$feature1->number, $feature2->number]
        ]);

        $this->assertCount(2, $forms);
        $this->assertEquals(['V-foo', 'V-baz'], $forms->pluck('shape')->toArray());
    }

    /** @test */
    public function it_filters_search_results_by_primary_object_obviative_code()
    {
        $feature = factory(Feature::class)->create(['obviative_code' => 1]);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'primary_object_name' => $feature
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'primary_object_name' => factory(Feature::class)->create([
                    'obviative_code' => null
                ])
            ])
        ]);

        $forms = VerbSearch::search(['primary_object_obviative_codes' => [$feature->obviative_code]]);

        $this->assertCount(1, $forms);
        $this->assertEquals('V-foo', $forms[0]->shape);
    }

    /** @test */
    public function it_filters_search_results_by_multiple_primary_object_obviative_codes()
    {
        $feature1 = factory(Feature::class)->create(['obviative_code' => 1]);
        $feature2 = factory(Feature::class)->create(['obviative_code' => 2]);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'primary_object_name' => $feature1
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-baz',
            'structure_id' => factory(VerbStructure::class)->create([
                'primary_object_name' => $feature2
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'primary_object_name' => factory(Feature::class)->create([
                    'obviative_code' => null
                ])
            ])
        ]);

        $forms = VerbSearch::search([
            'primary_object_obviative_codes' => [$feature1->obviative_code, $feature2->obviative_code]
        ]);

        $this->assertCount(2, $forms);
        $this->assertEquals(['V-foo', 'V-baz'], $forms->pluck('shape')->toArray());
    }

    /** @test */
    public function it_can_exclude_verb_forms_with_secondary_objects()
    {
        $feature = factory(Feature::class)->create(['person' => '1']);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'subject_name' => $feature
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'subject_name' => $feature,
                'secondary_object_name' => $feature
            ])
        ]);

        $forms = VerbSearch::search([
            'subject_persons' => [$feature->person],
            'secondary_object' => 0
        ]);

        $this->assertCount(1, $forms);
        $this->assertEquals('V-foo', $forms[0]->shape);
    }

    /** @test */
    public function it_filters_search_results_by_secondary_object_person()
    {
        $feature = factory(Feature::class)->create(['person' => '1']);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'secondary_object_name' => $feature
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'secondary_object_name' => factory(Feature::class)->create([
                    'person' => '2'
                ])
            ])
        ]);

        $forms = VerbSearch::search(['secondary_object_persons' => [$feature->person]]);

        $this->assertCount(1, $forms);
        $this->assertEquals('V-foo', $forms[0]->shape);
    }

    /** @test */
    public function it_filters_search_results_by_multiple_secondary_object_persons()
    {
        $feature1 = factory(Feature::class)->create(['person' => '1']);
        $feature2 = factory(Feature::class)->create(['person' => '3']);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'secondary_object_name' => $feature1
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-baz',
            'structure_id' => factory(VerbStructure::class)->create([
                'secondary_object_name' => $feature2
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'secondary_object_name' => factory(Feature::class)->create([
                    'person' => '2'
                ])
            ])
        ]);

        $forms = VerbSearch::search([
            'secondary_object_persons' => [$feature1->person, $feature2->person]
        ]);

        $this->assertCount(2, $forms);
        $this->assertEquals(['V-foo', 'V-baz'], $forms->pluck('shape')->toArray());
    }

    /** @test */
    public function it_filters_search_results_by_secondary_object_number()
    {
        $feature = factory(Feature::class)->create(['number' => 1]);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'secondary_object_name' => $feature
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'secondary_object_name' => factory(Feature::class)->create([
                    'number' => 2
                ])
            ])
        ]);

        $forms = VerbSearch::search(['secondary_object_numbers' => [$feature->number]]);

        $this->assertCount(1, $forms);
        $this->assertEquals('V-foo', $forms[0]->shape);
    }

    /** @test */
    public function it_filters_search_results_by_multiple_secondary_object_numbers()
    {
        $feature1 = factory(Feature::class)->create(['number' => 1]);
        $feature2 = factory(Feature::class)->create(['number' => 3]);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'secondary_object_name' => $feature1
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-baz',
            'structure_id' => factory(VerbStructure::class)->create([
                'secondary_object_name' => $feature2
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'secondary_object_name' => factory(Feature::class)->create([
                    'number' => 2
                ])
            ])
        ]);

        $forms = VerbSearch::search([
            'secondary_object_numbers' => [$feature1->number, $feature2->number]
        ]);

        $this->assertCount(2, $forms);
        $this->assertEquals(['V-foo', 'V-baz'], $forms->pluck('shape')->toArray());
    }

    /** @test */
    public function it_filters_search_results_by_secondary_object_obviative_code()
    {
        $feature = factory(Feature::class)->create(['obviative_code' => 1]);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'secondary_object_name' => $feature
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'secondary_object_name' => factory(Feature::class)->create([
                    'obviative_code' => null
                ])
            ])
        ]);

        $forms = VerbSearch::search(['secondary_object_obviative_codes' => [$feature->obviative_code]]);

        $this->assertCount(1, $forms);
        $this->assertEquals('V-foo', $forms[0]->shape);
    }

    /** @test */
    public function it_filters_search_results_by_multiple_secondary_object_obviative_codes()
    {
        $feature1 = factory(Feature::class)->create(['obviative_code' => 1]);
        $feature2 = factory(Feature::class)->create(['obviative_code' => 2]);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => factory(VerbStructure::class)->create([
                'secondary_object_name' => $feature1
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-baz',
            'structure_id' => factory(VerbStructure::class)->create([
                'secondary_object_name' => $feature2
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => factory(VerbStructure::class)->create([
                'secondary_object_name' => factory(Feature::class)->create([
                    'obviative_code' => null
                ])
            ])
        ]);

        $forms = VerbSearch::search([
            'secondary_object_obviative_codes' => [$feature1->obviative_code, [$feature2->obviative_code]]
        ]);

        $this->assertCount(2, $forms);
        $this->assertEquals(['V-foo', 'V-baz'], $forms->pluck('shape')->toArray());
    }
}
