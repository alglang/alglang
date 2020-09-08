<?php

namespace Tests\Unit\Search;

use App\Models\Feature;
use App\Models\Language;
use App\Models\VerbClass;
use App\Models\VerbForm;
use App\Models\VerbMode;
use App\Models\VerbOrder;
use App\Models\VerbStructure;
use App\Search\VerbSearch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerbSearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_orders_forms_by_language()
    {
        $form2 = VerbForm::factory()->create([
            'language_code' => Language::factory()->create(['name' => 'Test Language 2']),
            'shape' => 'V-foo'
        ]);
        $form1 = VerbForm::factory()->create([
            'language_code' => Language::factory()->create(['name' => 'Test Language 1']),
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
        $language = Language::factory()->create();
        VerbForm::factory()->create([
            'language_code' => $language,
            'shape' => 'V-foo'
        ]);
        VerbForm::factory()->create([
            'language_code' => Language::factory()->create(),
            'shape' => 'V-bar'
        ]);

        $forms = VerbSearch::search(['languages' => [$language->code]]);

        $this->assertCount(1, $forms);
        $this->assertEquals('V-foo', $forms[0]->shape);
    }

    /** @test */
    public function it_filters_search_results_by_multiple_languages()
    {
        $language1 = Language::factory()->create();
        $language2 = Language::factory()->create();
        VerbForm::factory()->create([
            'language_code' => $language1,
            'shape' => 'V-foo'
        ]);
        VerbForm::factory()->create([
            'language_code' => $language2,
            'shape' => 'V-baz'
        ]);
        VerbForm::factory()->create([
            'language_code' => Language::factory()->create(),
            'shape' => 'V-bar'
        ]);

        $forms = VerbSearch::search([
            'languages' => [$language1->code, $language2->code]
        ]);

        $this->assertCount(2, $forms);
        $this->assertContains('V-foo', $forms->pluck('shape'));
        $this->assertContains('V-baz', $forms->pluck('shape'));
    }

    /** @test */
    public function it_filters_search_results_by_mode()
    {
        $mode = VerbMode::factory()->create();
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'mode_name' => $mode
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'mode_name' => VerbMode::factory()->create()
            ])
        ]);

        $forms = VerbSearch::search(['modes' => [$mode->name]]);

        $this->assertCount(1, $forms);
        $this->assertEquals('V-foo', $forms[0]->shape);
    }

    /** @test */
    public function it_filters_search_results_by_multiple_modes()
    {
        $mode1 = VerbMode::factory()->create();
        $mode2 = VerbMode::factory()->create();
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'mode_name' => $mode1
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-baz',
            'structure_id' => VerbStructure::factory()->create([
                'mode_name' => $mode2
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'mode_name' => VerbMode::factory()->create()
            ])
        ]);

        $forms = VerbSearch::search([
            'modes' => [$mode1->name, $mode2->name]
        ]);

        $this->assertCount(2, $forms);
        $this->assertContains('V-foo', $forms->pluck('shape'));
        $this->assertContains('V-baz', $forms->pluck('shape'));
    }

    /** @test */
    public function it_filters_search_results_by_order()
    {
        $order = VerbOrder::factory()->create();
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'order_name' => $order
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'order_name' => VerbOrder::factory()->create()
            ])
        ]);

        $forms = VerbSearch::search(['orders' => [$order->name]]);

        $this->assertCount(1, $forms);
        $this->assertEquals('V-foo', $forms[0]->shape);
    }

    /** @test */
    public function it_filters_search_results_by_multiple_orders()
    {
        $order1 = VerbOrder::factory()->create();
        $order2 = VerbOrder::factory()->create();
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'order_name' => $order1
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-baz',
            'structure_id' => VerbStructure::factory()->create([
                'order_name' => $order2
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'order_name' => VerbOrder::factory()->create()
            ])
        ]);

        $forms = VerbSearch::search([
            'orders' => [$order1->name, $order2->name]
        ]);

        $this->assertCount(2, $forms);
        $this->assertContains('V-foo', $forms->pluck('shape'));
        $this->assertContains('V-baz', $forms->pluck('shape'));
    }

    /** @test */
    public function it_filters_search_results_by_class()
    {
        $class = VerbClass::factory()->create();
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'class_abv' => $class
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'class_abv' => VerbClass::factory()->create()
            ])
        ]);

        $forms = VerbSearch::search(['classes' => [$class->abv]]);

        $this->assertCount(1, $forms);
        $this->assertEquals('V-foo', $forms[0]->shape);
    }

    /** @test */
    public function it_filters_search_results_by_multiple_classes()
    {
        $class1 = VerbClass::factory()->create();
        $class2 = VerbClass::factory()->create();
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'class_abv' => $class1
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-baz',
            'structure_id' => VerbStructure::factory()->create([
                'class_abv' => $class2
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'class_abv' => VerbClass::factory()->create()
            ])
        ]);

        $forms = VerbSearch::search(['classes' => [
            $class1->abv, $class2->abv]
        ]);

        $this->assertCount(2, $forms);
        $this->assertContains('V-foo', $forms->pluck('shape'));
        $this->assertContains('V-baz', $forms->pluck('shape'));
    }

    /** @test */
    public function it_filters_search_results_by_negativity()
    {
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'is_negative' => false
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
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
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'is_diminutive' => true
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
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
        $feature = Feature::factory()->create(['person' => '1']);
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'subject_name' => $feature
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'subject_name' => Feature::factory()->create([
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
        $feature1 = Feature::factory()->create(['person' => '1']);
        $feature2 = Feature::factory()->create(['person' => '3']);
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'subject_name' => $feature1
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-baz',
            'structure_id' => VerbStructure::factory()->create([
                'subject_name' => $feature2
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'subject_name' => Feature::factory()->create([
                    'person' => '2'
                ])
            ])
        ]);

        $forms = VerbSearch::search([
            'subject_persons' => [$feature1->person, $feature2->person]
        ]);

        $this->assertCount(2, $forms);
        $this->assertContains('V-foo', $forms->pluck('shape'));
        $this->assertContains('V-baz', $forms->pluck('shape'));
    }

    /** @test */
    public function it_filters_search_results_by_subject_number()
    {
        $feature = Feature::factory()->create(['number' => 1]);
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'subject_name' => $feature
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'subject_name' => Feature::factory()->create([
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
        $feature1 = Feature::factory()->create(['number' => 1]);
        $feature2 = Feature::factory()->create(['number' => 3]);
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'subject_name' => $feature1
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-baz',
            'structure_id' => VerbStructure::factory()->create([
                'subject_name' => $feature2
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'subject_name' => Feature::factory()->create([
                    'number' => 2
                ])
            ])
        ]);

        $forms = VerbSearch::search([
            'subject_numbers' => [$feature1->number, $feature2->number]
        ]);

        $this->assertCount(2, $forms);
        $this->assertContains('V-foo', $forms->pluck('shape'));
        $this->assertContains('V-baz', $forms->pluck('shape'));
    }

    /** @test */
    public function it_filters_search_results_by_subject_obviative_code()
    {
        $feature = Feature::factory()->create(['obviative_code' => 1]);
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'subject_name' => $feature
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'subject_name' => Feature::factory()->create([
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
        $feature1 = Feature::factory()->create(['obviative_code' => 1]);
        $feature2 = Feature::factory()->create(['obviative_code' => 2]);
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'subject_name' => $feature1
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-baz',
            'structure_id' => VerbStructure::factory()->create([
                'subject_name' => $feature2
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'subject_name' => Feature::factory()->create([
                    'obviative_code' => null
                ])
            ])
        ]);

        $forms = VerbSearch::search([
            'subject_obviative_codes' => [$feature1->obviative_code, $feature2->obviative_code]
        ]);

        $this->assertCount(2, $forms);
        $this->assertContains('V-foo', $forms->pluck('shape'));
        $this->assertContains('V-baz', $forms->pluck('shape'));
    }

    /** @test */
    public function it_can_exclude_verb_forms_with_primary_objects()
    {
        $feature = Feature::factory()->create(['person' => '1']);
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'subject_name' => $feature
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
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
        $feature = Feature::factory()->create(['person' => '1']);
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'primary_object_name' => $feature
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'primary_object_name' => Feature::factory()->create([
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
        $feature1 = Feature::factory()->create(['person' => '1']);
        $feature2 = Feature::factory()->create(['person' => '3']);
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'primary_object_name' => $feature1
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-baz',
            'structure_id' => VerbStructure::factory()->create([
                'primary_object_name' => $feature2
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'primary_object_name' => Feature::factory()->create([
                    'person' => '2'
                ])
            ])
        ]);

        $forms = VerbSearch::search([
            'primary_object_persons' => [$feature1->person, $feature2->person]
        ]);

        $this->assertCount(2, $forms);
        $this->assertContains('V-foo', $forms->pluck('shape'));
        $this->assertContains('V-baz', $forms->pluck('shape'));
    }

    /** @test */
    public function it_filters_search_results_by_primary_object_number()
    {
        $feature = Feature::factory()->create(['number' => 1]);
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'primary_object_name' => $feature
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'primary_object_name' => Feature::factory()->create([
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
        $feature1 = Feature::factory()->create(['number' => 1]);
        $feature2 = Feature::factory()->create(['number' => 3]);
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'primary_object_name' => $feature1
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-baz',
            'structure_id' => VerbStructure::factory()->create([
                'primary_object_name' => $feature2
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'primary_object_name' => Feature::factory()->create([
                    'number' => 2
                ])
            ])
        ]);

        $forms = VerbSearch::search([
            'primary_object_numbers' => [$feature1->number, $feature2->number]
        ]);

        $this->assertCount(2, $forms);
        $this->assertContains('V-foo', $forms->pluck('shape'));
        $this->assertContains('V-baz', $forms->pluck('shape'));
    }

    /** @test */
    public function it_filters_search_results_by_primary_object_obviative_code()
    {
        $feature = Feature::factory()->create(['obviative_code' => 1]);
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'primary_object_name' => $feature
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'primary_object_name' => Feature::factory()->create([
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
        $feature1 = Feature::factory()->create(['obviative_code' => 1]);
        $feature2 = Feature::factory()->create(['obviative_code' => 2]);
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'primary_object_name' => $feature1
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-baz',
            'structure_id' => VerbStructure::factory()->create([
                'primary_object_name' => $feature2
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'primary_object_name' => Feature::factory()->create([
                    'obviative_code' => null
                ])
            ])
        ]);

        $forms = VerbSearch::search([
            'primary_object_obviative_codes' => [$feature1->obviative_code, $feature2->obviative_code]
        ]);

        $this->assertCount(2, $forms);
        $this->assertContains('V-foo', $forms->pluck('shape'));
        $this->assertContains('V-baz', $forms->pluck('shape'));
    }

    /** @test */
    public function it_can_exclude_verb_forms_with_secondary_objects()
    {
        $feature = Feature::factory()->create(['person' => '1']);
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'subject_name' => $feature
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
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
        $feature = Feature::factory()->create(['person' => '1']);
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'secondary_object_name' => $feature
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'secondary_object_name' => Feature::factory()->create([
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
        $feature1 = Feature::factory()->create(['person' => '1']);
        $feature2 = Feature::factory()->create(['person' => '3']);
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'secondary_object_name' => $feature1
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-baz',
            'structure_id' => VerbStructure::factory()->create([
                'secondary_object_name' => $feature2
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'secondary_object_name' => Feature::factory()->create([
                    'person' => '2'
                ])
            ])
        ]);

        $forms = VerbSearch::search([
            'secondary_object_persons' => [$feature1->person, $feature2->person]
        ]);

        $this->assertCount(2, $forms);
        $this->assertContains('V-foo', $forms->pluck('shape'));
        $this->assertContains('V-baz', $forms->pluck('shape'));
    }

    /** @test */
    public function it_filters_search_results_by_secondary_object_number()
    {
        $feature = Feature::factory()->create(['number' => 1]);
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'secondary_object_name' => $feature
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'secondary_object_name' => Feature::factory()->create([
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
        $feature1 = Feature::factory()->create(['number' => 1]);
        $feature2 = Feature::factory()->create(['number' => 3]);
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'secondary_object_name' => $feature1
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-baz',
            'structure_id' => VerbStructure::factory()->create([
                'secondary_object_name' => $feature2
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'secondary_object_name' => Feature::factory()->create([
                    'number' => 2
                ])
            ])
        ]);

        $forms = VerbSearch::search([
            'secondary_object_numbers' => [$feature1->number, $feature2->number]
        ]);

        $this->assertCount(2, $forms);
        $this->assertContains('V-foo', $forms->pluck('shape'));
        $this->assertContains('V-baz', $forms->pluck('shape'));
    }

    /** @test */
    public function it_filters_search_results_by_secondary_object_obviative_code()
    {
        $feature = Feature::factory()->create(['obviative_code' => 1]);
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'secondary_object_name' => $feature
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'secondary_object_name' => Feature::factory()->create([
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
        $feature1 = Feature::factory()->create(['obviative_code' => 1]);
        $feature2 = Feature::factory()->create(['obviative_code' => 2]);
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => VerbStructure::factory()->create([
                'secondary_object_name' => $feature1
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-baz',
            'structure_id' => VerbStructure::factory()->create([
                'secondary_object_name' => $feature2
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => VerbStructure::factory()->create([
                'secondary_object_name' => Feature::factory()->create([
                    'obviative_code' => null
                ])
            ])
        ]);

        $forms = VerbSearch::search([
            'secondary_object_obviative_codes' => [$feature1->obviative_code, [$feature2->obviative_code]]
        ]);

        $this->assertCount(2, $forms);
        $this->assertContains('V-foo', $forms->pluck('shape'));
        $this->assertContains('V-baz', $forms->pluck('shape'));
    }
}
