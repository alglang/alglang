<?php

namespace Tests\Feature;

use App\Models\Feature;
use App\Models\Language;
use App\Models\VerbForm;
use App\Models\VerbClass;
use App\Models\VerbMode;
use App\Models\VerbOrder;
use App\Models\VerbStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchVerbParadigmsTest extends TestCase
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

        $this->mode = VerbMode::factory()->create(['name' => 'factory mode']);
        $this->order = VerbOrder::factory()->create(['name' => 'factory order']);
        $this->class = VerbClass::factory()->create(['abv' => 'fc']);
        $this->subject = Feature::factory()->create(['name' => 'X', 'person' => 'X']);
    }

    protected function generateStructure(array $fields = []): VerbStructure
    {
        return VerbStructure::factory()->create(array_merge([
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
        $response = $this->get(route('search.verbs.paradigm-results', [
            'structures' => [$this->generateQuery()]
		]));

        $response->assertOk();
        $response->assertViewIs('search.verbs.paradigm-results');
    }

    /** @test */
    public function it_filters_search_results_by_language()
    {
        $language = Language::factory()->create();
        VerbForm::factory()->create([
            'language_code' => $language,
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure()
        ]);
        VerbForm::factory()->create([
            'language_code' => Language::factory()->create(),
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure()
        ]);

        $response = $this->get(route('search.verbs.paradigm-results', [
            'languages' => [$language->code]
		]));

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
    }

    /** @test */
    public function it_filters_search_results_by_order()
    {
        $order = VerbOrder::factory()->create();
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'order_name' => $order
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'order_name' => VerbOrder::factory()->create()
            ])
        ]);

        $response = $this->get(route('search.verbs.paradigm-results', [
            'orders' => [$order->name]
		]));

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
    }

    /** @test */
    public function it_filters_search_results_by_class()
    {
        $class = VerbClass::factory()->create();
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'class_abv' => $class
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'class_abv' => VerbClass::factory()->create()
            ])
        ]);

        $response = $this->get(route('search.verbs.paradigm-results', [
			'classes' => [$class->abv]
		]));

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
    }

    /** @test */
    public function it_filters_search_results_by_mode()
    {
        $mode = VerbMode::factory()->create();
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'mode_name' => $mode
            ])
        ]);
        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'mode_name' => VerbMode::factory()->create()
            ])
        ]);

        $response = $this->get(route('search.verbs.paradigm-results', [
			'modes' => [$mode->name]
		]));

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
    }

    /** @test */
    public function it_filters_search_results_by_negativity(): void
    {
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'is_negative' => true
            ])
        ]);

        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'is_negative' => false
            ])
        ]);

        $response = $this->get(route('search.verbs.paradigm-results', [
            'negative' => true
        ]));

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
    }

    /** @test */
    public function it_filters_search_results_by_affirmativity(): void
    {
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'is_negative' => true
            ])
        ]);

        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'is_negative' => false
            ])
        ]);

        $response = $this->get(route('search.verbs.paradigm-results', [
            'negative' => false
        ]));

        $response->assertOk();
        $response->assertSee('V-bar');
        $response->assertDontSee('V-foo');
    }

    /** @test */
    public function it_can_search_without_specifying_polarity(): void
    {
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'is_negative' => true
            ])
        ]);

        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'is_negative' => false
            ])
        ]);

        $response = $this->get(route('search.verbs.paradigm-results'));

        $response->assertOk();
        $response->assertSee('V-bar');
        $response->assertSee('V-foo');
    }

    /** @test */
    public function it_filters_search_results_by_diminutivity(): void
    {
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'is_diminutive' => true
            ])
        ]);

        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'is_diminutive' => false
            ])
        ]);

        $response = $this->get(route('search.verbs.paradigm-results', [
            'diminutive' => true
        ]));

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
    }

    /** @test */
    public function it_filters_search_results_by_non_diminutivity(): void
    {
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'is_diminutive' => true
            ])
        ]);

        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'is_diminutive' => false
            ])
        ]);

        $response = $this->get(route('search.verbs.paradigm-results', [
            'diminutive' => false
        ]));

        $response->assertOk();
        $response->assertSee('V-bar');
        $response->assertDontSee('V-foo');
    }

    /** @test */
    public function it_can_search_without_specifying_diminutivity(): void
    {
        VerbForm::factory()->create([
            'shape' => 'V-foo',
            'structure_id' => $this->generateStructure([
                'is_diminutive' => true
            ])
        ]);

        VerbForm::factory()->create([
            'shape' => 'V-bar',
            'structure_id' => $this->generateStructure([
                'is_diminutive' => false
            ])
        ]);

        $response = $this->get(route('search.verbs.paradigm-results'));

        $response->assertOk();
        $response->assertSee('V-bar');
        $response->assertSee('V-foo');
    }
}
