<?php

namespace Tests\Feature;

use App\Models\Feature;
use App\Models\Language;
use App\Models\NominalForm;
use App\Models\NominalParadigm;
use App\Models\NominalStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class NominalOrderByFeaturesTest extends TestCase
{
    use RefreshDatabase;

    /** @var Language */
    protected $language;

    public function setUp(): void
    {
        parent::setUp();

        $this->language = factory(Language::class)->create();
    }

    /** @test */
    public function pronominal_local_features_sort_before_nonlocal_features()
    {
        $unsortedFeatures = [
            ['pronominal' => ['person' => '3', 'number' => 1, 'obviative_code' => null]],
            ['pronominal' => ['person' => '1', 'number' => 1, 'obviative_code' => null]]
        ];
        $this->createNominalForms($unsortedFeatures);

        $forms = NominalForm::orderByFeatures()->get();

        $targets = [
            ['pronominal' => ['person' => '1', 'number' => 1, 'obviative_code' => null]],
            ['pronominal' => ['person' => '3', 'number' => 1, 'obviative_code' => null]],
        ];

        $this->assertFormsHaveFeatures($targets, $forms);
    }

    /** @test */
    public function pronominal_local_features_sort_by_number_then_person()
    {
        $unsortedFeatures = [
            ['pronominal' => ['person' => '2', 'number' => 1, 'obviative_code' => null]],
            ['pronominal' => ['person' => '2', 'number' => 2, 'obviative_code' => null]],
            ['pronominal' => ['person' => '1', 'number' => 1, 'obviative_code' => null]],
            ['pronominal' => ['person' => '1', 'number' => 2, 'obviative_code' => null]],
        ];
        $this->createNominalForms($unsortedFeatures);

        $forms = NominalForm::orderByFeatures()->get();

        $targets = [
            ['pronominal' => ['person' => '1', 'number' => 1, 'obviative_code' => null]],
            ['pronominal' => ['person' => '2', 'number' => 1, 'obviative_code' => null]],
            ['pronominal' => ['person' => '1', 'number' => 2, 'obviative_code' => null]],
            ['pronominal' => ['person' => '2', 'number' => 2, 'obviative_code' => null]],
        ];

        $this->assertFormsHaveFeatures($targets, $forms);
    }

    /** @test */
    public function pronominal_nonlocal_features_sort_by_person_then_number()
    {
        $unsortedFeatures = [
            ['pronominal' => ['person' => '0', 'number' => 2, 'obviative_code' => null]],
            ['pronominal' => ['person' => '3', 'number' => 1, 'obviative_code' => null]],
            ['pronominal' => ['person' => '3', 'number' => 2, 'obviative_code' => null]],
            ['pronominal' => ['person' => '0', 'number' => 1, 'obviative_code' => null]],
        ];
        $this->createNominalForms($unsortedFeatures);

        $forms = NominalForm::orderByFeatures()->get();

        $targets = [
            ['pronominal' => ['person' => '3', 'number' => 1, 'obviative_code' => null]],
            ['pronominal' => ['person' => '3', 'number' => 2, 'obviative_code' => null]],
            ['pronominal' => ['person' => '0', 'number' => 1, 'obviative_code' => null]],
            ['pronominal' => ['person' => '0', 'number' => 2, 'obviative_code' => null]],
        ];

        $this->assertFormsHaveFeatures($targets, $forms);
    }

    /** @test */
    public function pronominal_features_are_sorted_by_obviativity_after_person_and_number()
    {
        $unsortedFeatures = [
            ['pronominal' => ['person' => '1', 'number' => 1, 'obviative_code' => 3]],
            ['pronominal' => ['person' => '1', 'number' => 1, 'obviative_code' => 2]],
            ['pronominal' => ['person' => '1', 'number' => 1, 'obviative_code' => 1]]
        ];
        $this->createNominalForms($unsortedFeatures);

        $forms = NominalForm::orderByFeatures()->get();

        $targets = [
            ['pronominal' => ['person' => '1', 'number' => 1, 'obviative_code' => 1]],
            ['pronominal' => ['person' => '1', 'number' => 1, 'obviative_code' => 2]],
            ['pronominal' => ['person' => '1', 'number' => 1, 'obviative_code' => 3]]
        ];

        $this->assertFormsHaveFeatures($targets, $forms);
    }

    /** @test */
    public function nominal_local_features_sort_before_nonlocal_features()
    {
        $unsortedFeatures = [
            ['nominal' => ['person' => '3', 'number' => 1, 'obviative_code' => null]],
            ['nominal' => ['person' => '1', 'number' => 1, 'obviative_code' => null]]
        ];
        $this->createNominalForms($unsortedFeatures);

        $forms = NominalForm::orderByFeatures()->get();

        $targets = [
            ['nominal' => ['person' => '1', 'number' => 1, 'obviative_code' => null]],
            ['nominal' => ['person' => '3', 'number' => 1, 'obviative_code' => null]],
        ];

        $this->assertFormsHaveFeatures($targets, $forms);
    }

    /** @test */
    public function nominal_local_features_sort_by_number_then_person()
    {
        $unsortedFeatures = [
            ['nominal' => ['person' => '2', 'number' => 1, 'obviative_code' => null]],
            ['nominal' => ['person' => '2', 'number' => 2, 'obviative_code' => null]],
            ['nominal' => ['person' => '1', 'number' => 1, 'obviative_code' => null]],
            ['nominal' => ['person' => '1', 'number' => 2, 'obviative_code' => null]],
        ];
        $this->createNominalForms($unsortedFeatures);

        $forms = NominalForm::orderByFeatures()->get();

        $targets = [
            ['nominal' => ['person' => '1', 'number' => 1, 'obviative_code' => null]],
            ['nominal' => ['person' => '2', 'number' => 1, 'obviative_code' => null]],
            ['nominal' => ['person' => '1', 'number' => 2, 'obviative_code' => null]],
            ['nominal' => ['person' => '2', 'number' => 2, 'obviative_code' => null]],
        ];

        $this->assertFormsHaveFeatures($targets, $forms);
    }

    /** @test */
    public function nominal_nonlocal_features_sort_by_person_then_number()
    {
        $unsortedFeatures = [
            ['nominal' => ['person' => '0', 'number' => 2, 'obviative_code' => null]],
            ['nominal' => ['person' => '3', 'number' => 1, 'obviative_code' => null]],
            ['nominal' => ['person' => '3', 'number' => 2, 'obviative_code' => null]],
            ['nominal' => ['person' => '0', 'number' => 1, 'obviative_code' => null]],
        ];
        $this->createNominalForms($unsortedFeatures);

        $forms = NominalForm::orderByFeatures()->get();

        $targets = [
            ['nominal' => ['person' => '3', 'number' => 1, 'obviative_code' => null]],
            ['nominal' => ['person' => '3', 'number' => 2, 'obviative_code' => null]],
            ['nominal' => ['person' => '0', 'number' => 1, 'obviative_code' => null]],
            ['nominal' => ['person' => '0', 'number' => 2, 'obviative_code' => null]],
        ];

        $this->assertFormsHaveFeatures($targets, $forms);
    }

    /** @test */
    public function nominal_features_are_sorted_by_obviativity_after_person_and_number()
    {
        $unsortedFeatures = [
            ['nominal' => ['person' => '1', 'number' => 1, 'obviative_code' => 3]],
            ['nominal' => ['person' => '1', 'number' => 1, 'obviative_code' => 2]],
            ['nominal' => ['person' => '1', 'number' => 1, 'obviative_code' => 1]]
        ];
        $this->createNominalForms($unsortedFeatures);

        $forms = NominalForm::orderByFeatures()->get();

        $targets = [
            ['nominal' => ['person' => '1', 'number' => 1, 'obviative_code' => 1]],
            ['nominal' => ['person' => '1', 'number' => 1, 'obviative_code' => 2]],
            ['nominal' => ['person' => '1', 'number' => 1, 'obviative_code' => 3]]
        ];

        $this->assertFormsHaveFeatures($targets, $forms);
    }

    /** @test */
    public function pronominal_features_are_sorted_before_nominal_features()
    {
        $unsortedFeatures = [
            [
                'pronominal' => ['person' => '1', 'number' => 1, 'obviative_code' => 2],
                'nominal' => ['person' => '1', 'number' => 1, 'obviative_code' => 1]
            ],
            [
                'pronominal' => ['person' => '1', 'number' => 1, 'obviative_code' => 1],
                'nominal' => ['person' => '1', 'number' => 1, 'obviative_code' => 2]
            ]
        ];
        $this->createNominalForms($unsortedFeatures);

        $forms = NominalForm::orderByFeatures()->get();

        $targets = [
            [
                'pronominal' => ['person' => '1', 'number' => 1, 'obviative_code' => 1],
                'nominal' => ['person' => '1', 'number' => 1, 'obviative_code' => 2]
            ],
            [
                'pronominal' => ['person' => '1', 'number' => 1, 'obviative_code' => 2],
                'nominal' => ['person' => '1', 'number' => 1, 'obviative_code' => 1]
            ]
        ];

        $this->assertFormsHaveFeatures($targets, $forms);
    }

    protected function createNominalForms(array $featureSets): void
    {
        foreach ($featureSets as $featureGroup) {
            factory(NominalForm::class)->create([
                'language_code' => $this->language->code,
                'structure_id' => factory(NominalStructure::class)->create([
                    'pronominal_feature_name' => isset($featureGroup['pronominal']) ? factory(Feature::class)->create($featureGroup['pronominal']) : null,
                    'nominal_feature_name' => isset($featureGroup['nominal']) ? factory(Feature::class)->create($featureGroup['nominal']) : null,
                    'paradigm_id' => factory(NominalParadigm::class)->create([
                        'language_code' => $this->language->code
                    ])
                ])
            ]);
        }
    }

    protected function assertFormsHaveFeatures(array $target, Collection $forms): void
    {
        $this->assertCount(count($target), $forms);

        $actual = $forms->map(function (NominalForm $form) {
            $pronominalFeatures = $form->structure->pronominalFeature;
            $nominalFeatures = $form->structure->nominalFeature;
            $features = [];

            if ($pronominalFeatures) {
                $features['pronominal'] = [
                    'person' => $pronominalFeatures->person,
                    'number' => $pronominalFeatures->number,
                    'obviative_code' => $pronominalFeatures->obviative_code
                ];
            }

            if ($nominalFeatures) {
                $features['nominal'] = [
                    'person' => $nominalFeatures->person,
                    'number' => $nominalFeatures->number,
                    'obviative_code' => $nominalFeatures->obviative_code
                ];
            }

            return $features;
        })->toArray();

        $this->assertEquals($target, $actual);
    }
}
