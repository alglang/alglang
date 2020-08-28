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
        $response = $this->get(route('search.verbs.paradigm-results', [
            'structures' => [$this->generateQuery()]
		]));

        $response->assertOk();
        $response->assertViewIs('search.verbs.paradigm-results');
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

        $response = $this->get(route('search.verbs.paradigm-results', [
			'modes' => [$mode->name]
		]));

        $response->assertOk();
        $response->assertSee('V-foo');
        $response->assertDontSee('V-bar');
    }
}
