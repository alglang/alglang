<?php

namespace Tests\Feature;

use App\Models\Feature;
use App\Models\Language;
use App\Models\VerbClass;
use App\Models\VerbForm;
use App\Models\VerbMode;
use App\Models\VerbOrder;
use App\Models\VerbStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchVerbFormsTest extends TestCase
{
    use RefreshDatabase;

    /** @var VerbMode */
    private $mode;
    /** @var VerbOrder */
    private $order;
    /** @var VerbClass */
    private $class;
    /** @var Feature */
    private $subject;

    public function setUp(): void
    {
        parent::setUp();

        $this->mode = factory(VerbMode::class)->create(['name' => 'factory mode']);
        $this->order = factory(VerbOrder::class)->create(['name' => 'factory order']);
        $this->class = factory(VerbClass::class)->create(['abv' => 'fc']);
        $this->subject = factory(Feature::class)->create(['name' => 'X', 'person' => 'X']);
    }

    protected function generateStructure(array $fields = []): VerbStructure
    {
        return factory(VerbStructure::class)->create(array_merge([
            'mode_name' => $this->mode,
            'order_name' => $this->order,
            'class_abv' => $this->class,
            'subject_name' => $this->subject
        ], $fields));
    }

    protected function generateQuery(array $fields = []): array
    {
        return array_merge([
            'modes' => ['factory mode'],
            'orders' => ['factory order'],
            'classes' => ['fc'],
            'subject_persons' => ['X']
        ], $fields);
    }

    /** @test */
    public function it_returns_the_correct_view()
    {
        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [$this->generateQuery()]
		]));

        $response->assertOk();
        $response->assertViewIs('search.verbs.form-results');
    }

    /** @test */
    public function it_shows_the_structure_queries()
    {
        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                [
                    'subject_persons' => ['X'],
                    'classes' => ['TA'],
                    'orders' => ['Conjunct'],
                    'modes' => ['Indicative']
                ]
            ]
        ]));

        $response->assertOk();
        $response->assertSeeInOrder(['X', 'TA', 'Conjunct', 'Indicative']);
    }

    /** @test */
    public function it_returns_a_302_if_it_has_no_structures()
    {
        $response = $this->get(route('search.verbs.form-results'), [
            'languages' => ['foo']
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('structures');
    }

    /** @test */
    public function it_orders_forms_by_language()
    {
        $language2 = factory(Language::class)->create(['name' => 'Test Language 2']);
        $language1 = factory(Language::class)->create(['name' => 'Test Language 1']);

        factory(VerbForm::class)->create([
            'language_code' => $language2,
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure()
        ]);
        factory(VerbForm::class)->create([
            'language_code' => $language1,
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure()
        ]);

        $response = $this->get(route('search.verbs.form-results', [
            'languages' => [$language2->code, $language1->code],
            'structures' => [$this->generateQuery()]
        ]));

        $response->assertOk();
        $response->assertSeeInOrder(['Test Language 1', 'Test Language 2']);
        $response->assertSeeInOrder(['V-bar', 'V-foo']);
    }

    /** @test */
    public function it_filters_search_results_by_language()
    {
        $language = factory(Language::class)->create();
        factory(VerbForm::class)->create([
            'language_code' => $language,
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure()
        ]);
        factory(VerbForm::class)->create([
            'language_code' => factory(Language::class)->create(),
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure()
        ]);

        $response = $this->get(route('search.verbs.form-results', [
            'languages' => [$language->code],
            'structures' => [$this->generateQuery()]
		]));

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
    }

    /** @test */
    public function it_filters_search_results_by_mode()
    {
        $mode = factory(VerbMode::class)->create();
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'mode_name' => $mode
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'mode_name' => factory(VerbMode::class)->create()
            ])
        ]);

        $response = $this->get(route('search.verbs.form-results', [
			'structures' => [
				$this->generateQuery(['modes' => [$mode->name]])
			]
		]));

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
    }

    /** @test */
    public function only_one_mode_is_allowed_per_structure()
    {
        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery(['modes' => ['foo', 'bar']])
            ]
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('structures.*.modes');
    }

    /** @test */
    public function mode_must_be_included()
    {
        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery(['modes' => null])
            ]
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('structures.*.modes');
    }

    /** @test */
    public function it_filters_search_results_by_order()
    {
        $order = factory(VerbOrder::class)->create();
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'order_name' => $order
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'order_name' => factory(VerbOrder::class)->create()
            ])
        ]);

        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery(['orders' => [$order->name]])
            ]
		]));

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
    }

    /** @test */
    public function only_one_order_is_allowed_per_structure()
    {
        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery(['orders' => ['foo', 'bar']])
            ]
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('structures.*.orders');
    }

    /** @test */
    public function order_must_be_included()
    {
        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery(['orders' => null])
            ]
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('structures.*.orders');
    }

    /** @test */
    public function it_filters_search_results_by_class()
    {
        $class = factory(VerbClass::class)->create();
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'class_abv' => $class
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'class_abv' => factory(VerbClass::class)->create()
            ])
        ]);

        $response = $this->get(route('search.verbs.form-results', [
			'structures' => [
				$this->generateQuery(['classes' => [$class->abv]])
			]
		]));

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
    }

    /** @test */
    public function only_one_class_is_allowed_per_structure()
    {
        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery(['classes' => ['foo', 'bar']])
            ]
        ]));

        $response->assertStatus(302);
    }

    /** @test */
    public function class_must_be_included()
    {
        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery(['classes' => null])
            ]
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('structures.*.classes');
    }

    /** @test */
    public function it_filters_search_results_by_negativity()
    {
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'is_negative' => false
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'is_negative' => true
            ])
        ]);

        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery(['negative' => false])
            ]
        ]));

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
    }

    /** @test */
    public function it_filters_search_results_by_diminutivity()
    {
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'is_diminutive' => true
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'is_diminutive' => false
            ])
        ]);

        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery(['diminutive' => true])
            ]
        ]));

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
            'structure_id' => $this->generateStructure([
                'subject_name' => $feature
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'subject_name' => factory(Feature::class)->create([
                    'person' => '2'
                ])
            ])
        ]);

        $response = $this->get(route('search.verbs.form-results', [
			'structures' => [
				$this->generateQuery(['subject_persons' => [$feature->person]])
			]
		]));

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
    }

    /** @test */
    public function only_one_subject_person_is_allowed_per_structure()
    {
        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery(['subject_persons' => ['foo', 'bar']])
            ]
        ]));

        $response->assertStatus(302);
    }

    /** @test */
    public function subject_person_must_be_included()
    {
        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery(['subject_persons' => null])
            ]
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('structures.*.subject_persons');
    }

    /** @test */
    public function it_filters_search_results_by_subject_number()
    {
        $feature = factory(Feature::class)->create([
            'person' => $this->subject->person,
            'number' => 1
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'subject_name' => $feature
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'subject_name' => factory(Feature::class)->create([
                    'person' => $this->subject->person,
                    'number' => 2
                ])
            ])
        ]);

        $response = $this->get(route('search.verbs.form-results', [
			'structures' => [
				$this->generateQuery(['subject_numbers' => [$feature->number]])
			]
		]));

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
    }

    /** @test */
    public function only_one_subject_number_is_allowed_per_structure()
    {
        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery(['subject_numbers' => ['foo', 'bar']])
            ]
        ]));

        $response->assertStatus(302);
    }

    /** @test */
    public function it_filters_search_results_by_subject_obviative_code()
    {
        $feature = factory(Feature::class)->create([
            'person' => $this->subject->person,
            'obviative_code' => 1
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'subject_name' => $feature
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'subject_name' => factory(Feature::class)->create([
                    'person' => $this->subject->person,
                    'obviative_code' => null
                ])
            ])
        ]);

        $response = $this->get(route('search.verbs.form-results', [
			'structures' => [
				$this->generateQuery(['subject_obviative_codes' => [$feature->obviative_code]])
			]
		]));

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
    }

    /** @test */
    public function only_one_subject_obviative_code_is_allowed_per_structure()
    {
        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery(['subject_obviative_codes' => ['foo', 'bar']])
            ]
        ]));

        $response->assertStatus(302);
    }

    /** @test */
    public function it_can_exclude_verb_forms_with_primary_objects()
    {
        $feature = factory(Feature::class)->create(['person' => '1']);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'subject_name' => $feature
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'subject_name' => $feature,
                'primary_object_name' => $feature
            ])
        ]);

        $response = $this->get(route('search.verbs.form-results', [
			'structures' => [
                $this->generateQuery([
                    'subject_persons' => [$feature->person],
                    'primary_object' => false
                ])
			]
		]));

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
            'structure_id' => $this->generateStructure([
                'primary_object_name' => $feature
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'primary_object_name' => factory(Feature::class)->create([
                    'person' => '2'
                ])
            ])
        ]);

        $response = $this->get(route('search.verbs.form-results', [
			'structures' => [
				$this->generateQuery(['primary_object_persons' => [$feature->person]])
			]
		]));

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
    }

    /** @test */
    public function only_one_primary_object_person_is_allowed_per_structure()
    {
        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery(['primary_object_persons' => ['foo', 'bar']])
            ]
        ]));

        $response->assertStatus(302);
    }

    /** @test */
    public function it_filters_search_results_by_primary_object_number()
    {
        $feature = factory(Feature::class)->create(['number' => 1]);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'primary_object_name' => $feature
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'primary_object_name' => factory(Feature::class)->create([
                    'number' => 2
                ])
            ])
        ]);

        $response = $this->get(route('search.verbs.form-results', [
			'structures' => [
				$this->generateQuery(['primary_object_numbers' => [$feature->number]])
			]
		]));

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
    }

    /** @test */
    public function only_one_primary_object_number_is_allowed_per_structure()
    {
        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery(['primary_object_numbers' => ['foo', 'bar']])
            ]
        ]));

        $response->assertStatus(302);
    }

    /** @test */
    public function it_filters_search_results_by_primary_object_obviative_code()
    {
        $feature = factory(Feature::class)->create(['obviative_code' => 1]);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'primary_object_name' => $feature
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'primary_object_name' => factory(Feature::class)->create([
                    'obviative_code' => null
                ])
            ])
        ]);

        $response = $this->get(route('search.verbs.form-results', [
			'structures' => [
				$this->generateQuery(['primary_object_obviative_codes' => [$feature->obviative_code]])
			]
		]));

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
    }

    /** @test */
    public function only_one_primary_object_obviative_code_is_allowed_per_structure()
    {
        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery(['primary_object_obviative_codes' => ['foo', 'bar']])
            ]
        ]));

        $response->assertStatus(302);
    }

    /** @test */
    public function it_can_exclude_verb_forms_with_secondary_objects()
    {
        $feature = factory(Feature::class)->create(['person' => '1']);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'subject_name' => $feature
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'subject_name' => $feature,
                'secondary_object_name' => $feature
            ])
        ]);

        $response = $this->get(route('search.verbs.form-results', [
			'structures' => [
                $this->generateQuery([
                    'subject_persons' => [$feature->person],
                    'secondary_object' => false
                ])
			]
		]));

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
            'structure_id' => $this->generateStructure([
                'secondary_object_name' => $feature
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'secondary_object_name' => factory(Feature::class)->create([
                    'person' => '2'
                ])
            ])
        ]);

        $response = $this->get(route('search.verbs.form-results', [
			'structures' => [
				$this->generateQuery(['secondary_object_persons' => [$feature->person]])
			]
		]));

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
    }

    /** @test */
    public function only_one_secondary_object_person_is_allowed_per_structure()
    {
        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery(['secondary_object_persons' => ['foo', 'bar']])
            ]
        ]));

        $response->assertStatus(302);
    }

    /** @test */
    public function it_filters_search_results_by_secondary_object_number()
    {
        $feature = factory(Feature::class)->create(['number' => 1]);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'secondary_object_name' => $feature
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'secondary_object_name' => factory(Feature::class)->create([
                    'number' => 2
                ])
            ])
        ]);

        $response = $this->get(route('search.verbs.form-results', [
			'structures' => [
				$this->generateQuery(['secondary_object_numbers' => [$feature->number]])
			]
		]));

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
    }

    /** @test */
    public function only_one_secondary_object_number_is_allowed_per_structure()
    {
        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery(['secondary_object_numbers' => ['foo', 'bar']])
            ]
        ]));

        $response->assertStatus(302);
    }

    /** @test */
    public function it_filters_search_results_by_secondary_object_obviative_code()
    {
        $feature = factory(Feature::class)->create(['obviative_code' => 1]);
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'secondary_object_name' => $feature
            ])
        ]);
        factory(VerbForm::class)->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'secondary_object_name' => factory(Feature::class)->create([
                    'obviative_code' => null
                ])
            ])
        ]);

        $response = $this->get(route('search.verbs.form-results', [
			'structures' => [
				$this->generateQuery(['secondary_object_obviative_codes' => [$feature->obviative_code]])
			]
		]));

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
    }

    /** @test */
    public function only_one_secondary_object_obviative_code_is_allowed_per_structure()
    {
        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery(['secondary_object_obviative_codes' => ['foo', 'bar']])
            ]
        ]));

        $response->assertStatus(302);
    }

    /** @test */
    public function it_shows_forms_feature_strings_if_the_subject_differs_from_the_query()
    {
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'subject_name' => factory(Feature::class)->create([
                    'name' => 'bar',
                    'person' => '1',
                    'number' => 1
                ])
            ])
        ]);

        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery([
                    'subject_persons' => ['1'],
                    'subject_numbers' => null,
                    'primary_object' => false,
                    'secondary_object' => false,
                ])
            ]
        ]));

        $response->assertOk();
        $response->assertSeeInOrder(['V-foo', '(bar)']);
    }

    /** @test */
    public function it_shows_forms_feature_strings_if_the_primary_object_differs_from_the_query()
    {
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'subject_name' => $this->subject,
                'primary_object_name' => factory(Feature::class)->create([
                    'name' => 'baz',
                    'person' => '2',
                    'number' => 2
                ])
            ])
        ]);

        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery([
                    'subject_persons' => [$this->subject->person],
                    'subject_numbers' => [$this->subject->number],
                    'primary_object_persons' => ['2'],
                    'primary_object_numbers' => null,
                    'secondary_object' => false,
                ])
            ]
        ]));

        $response->assertOk();
        $response->assertSeeInOrder(['V-foo', "({$this->subject->name}→baz)"]);
    }

    /** @test */
    public function it_shows_forms_feature_strings_if_the_secondary_object_differs_from_the_query()
    {
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'subject_name' => $this->subject,
                'secondary_object_name' => factory(Feature::class)->create([
                    'name' => 'baz',
                    'person' => '2',
                    'number' => 2
                ])
            ])
        ]);

        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery([
                    'subject_persons' => [$this->subject->person],
                    'subject_numbers' => [$this->subject->number],
                    'primary_object' => false,
                    'secondary_object_persons' => ['2'],
                    'secondary_object_numbers' => null,
                ])
            ]
        ]));

        $response->assertOk();
        $response->assertSeeInOrder(['V-foo', "({$this->subject->name}+baz)"]);
    }

    /** @test */
    public function it_does_not_show_forms_feature_strings_if_they_only_differ_from_the_query_by_primary_object_wildcard()
    {
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'subject_name' => factory(Feature::class)->create([
                    'name' => '1s',
                    'person' => '1',
                    'number' => 1
                ])
            ])
        ]);

        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery([
                    'subject_persons' => ['1'],
                    'subject_numbers' => [1],
                    'primary_object' => null,
                    'secondary_object' => false
                ])
            ]
        ]));

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('(1s)');
    }

    /** @test */
    public function it_does_not_show_forms_feature_strings_if_they_only_differ_from_the_query_by_secondary_object_wildcard()
    {
        factory(VerbForm::class)->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'subject_name' => factory(Feature::class)->create([
                    'name' => '1s',
                    'person' => '1',
                    'number' => 1
                ])
            ])
        ]);

        $response = $this->get(route('search.verbs.form-results', [
            'structures' => [
                $this->generateQuery([
                    'subject_persons' => ['1'],
                    'subject_numbers' => [1],
                    'primary_object' => false,
                    'secondary_object' => null
                ])
            ]
        ]));

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('(1s)');
    }
}
