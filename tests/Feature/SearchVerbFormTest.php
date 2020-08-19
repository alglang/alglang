<?php

namespace Tests\Feature;

use App\Feature;
use App\Language;
use App\VerbForm;
use App\VerbClass;
use App\VerbMode;
use App\VerbOrder;
use App\VerbStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchVerbFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_the_correct_view()
    {
        $language = factory(Language::class)->create();

        $response = $this->get("/search/verbs/forms?languages[]=$language->id");

        $response->assertOk();
        $response->assertViewIs('search.verbs.forms');
    }

    /** @test */
    public function it_filters_search_results_by_language()
    {
        $language = factory(Language::class)->create();
        factory(VerbForm::class)->create([
            'language_id' => $language,
            'shape' => 'V-foo'
        ]);
        factory(VerbForm::class)->create([
            'language_id' => factory(Language::class)->create(),
            'shape' => 'V-bar'
        ]);

        $response = $this->get("/search/verbs/forms?languages[]=$language->id");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
    }

    /** @test */
    public function it_filters_search_results_by_multiple_languages()
    {
        $language1 = factory(Language::class)->create();
        $language2 = factory(Language::class)->create();
        factory(VerbForm::class)->create([
            'language_id' => $language1,
            'shape' => 'V-foo'
        ]);
        factory(VerbForm::class)->create([
            'language_id' => $language2,
            'shape' => 'V-baz'
        ]);
        factory(VerbForm::class)->create([
            'language_id' => factory(Language::class)->create(),
            'shape' => 'V-bar'
        ]);

        $response = $this->get("/search/verbs/forms?languages[]=$language1->id&languages[]=$language2->id");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertSee('V-baz');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?modes[]=$mode->name");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?modes[]=$mode1->name&modes[]=$mode2->name");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertSee('V-baz');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?orders[]=$order->name");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?orders[]=$order1->name&orders[]=$order2->name");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertSee('V-baz');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?classes[]=$class->abv");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?classes[]=$class1->abv&classes[]=$class2->abv");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertSee('V-baz');
        $response->assertDontSee('V-bar');
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

        $response = $this->get('/search/verbs/forms?negative=0');

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
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

        $response = $this->get('/search/verbs/forms?diminutive=1');

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?subject_persons[]=$feature->person");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?subject_persons[]=$feature1->person&subject_persons[]=$feature2->person");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertSee('V-baz');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?subject_numbers[]=$feature->number");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?subject_numbers[]=$feature1->number&subject_numbers[]=$feature2->number");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertSee('V-baz');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?subject_obviative_codes[]=$feature->obviative_code");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?subject_obviative_codes[]=$feature1->obviative_code&subject_obviative_codes[]=$feature2->obviative_code");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertSee('V-baz');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?subject_persons[]=$feature->person&primary_object=0");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?primary_object_persons[]=$feature->person");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?primary_object_persons[]=$feature1->person&primary_object_persons[]=$feature2->person");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertSee('V-baz');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?primary_object_numbers[]=$feature->number");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?primary_object_numbers[]=$feature1->number&primary_object_numbers[]=$feature2->number");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertSee('V-baz');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?primary_object_obviative_codes[]=$feature->obviative_code");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?primary_object_obviative_codes[]=$feature1->obviative_code&primary_object_obviative_codes[]=$feature2->obviative_code");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertSee('V-baz');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?subject_persons[]=$feature->person&secondary_object=0");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?secondary_object_persons[]=$feature->person");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?secondary_object_persons[]=$feature1->person&secondary_object_persons[]=$feature2->person");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertSee('V-baz');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?secondary_object_numbers[]=$feature->number");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?secondary_object_numbers[]=$feature1->number&secondary_object_numbers[]=$feature2->number");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertSee('V-baz');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?secondary_object_obviative_codes[]=$feature->obviative_code");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
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

        $response = $this->get("/search/verbs/forms?secondary_object_obviative_codes[]=$feature1->obviative_code&secondary_object_obviative_codes[]=$feature2->obviative_code");

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertSee('V-baz');
        $response->assertDontSee('V-bar');
    }
}
