<?php

namespace Tests\Unit\Models;

use App\Models\VerbStructure;
use PHPUnit\Framework\TestCase;

class VerbStructureTest extends TestCase
{
    /*
    |--------------------------------------------------------------------------
    | matchesStructure
    |--------------------------------------------------------------------------
    |
    */

    protected function generateStructure(array $params = []): VerbStructure
    {
        return new VerbStructure(array_merge([
            'class_abv' => 'CLS',
            'order_name' => 'ORDER',
            'mode_name' => 'MODE',
            'subject_name' => 'SUBJECT',
            'primary_object_name' => 'PRIMARYOBJECT',
            'secondary_object_name' => 'PRIMARYOBJECT',
            'is_negative' => false,
            'is_diminutive' => false
        ], $params));
    }

    /** @test */
    public function structures_do_not_match_if_classes_differ()
    {
        $structure1 = $this->generateStructure(['class_abv' => 'FOO']);
        $structure2 = $this->generateStructure(['class_abv' => 'BAR']);

        $this->assertFalse($structure1->matchesStructure($structure2));
    }

    /** @test */
    public function structures_do_not_match_if_orders_differ()
    {
        $structure1 = $this->generateStructure(['order_name' => 'FOO']);
        $structure2 = $this->generateStructure(['order_name' => 'BAR']);

        $this->assertFalse($structure1->matchesStructure($structure2));
    }

    /** @test */
    public function structures_do_not_match_if_modes_differ()
    {
        $structure1 = $this->generateStructure(['mode_name' => 'FOO']);
        $structure2 = $this->generateStructure(['mode_name' => 'BAR']);

        $this->assertFalse($structure1->matchesStructure($structure2));
    }

    /** @test */
    public function structures_do_not_match_if_negativity_differs()
    {
        $structure1 = $this->generateStructure(['is_negative' => true]);
        $structure2 = $this->generateStructure(['is_negative' => false]);

        $this->assertFalse($structure1->matchesStructure($structure2));
    }

    /** @test */
    public function structures_do_not_match_if_diminutivity_differs()
    {
        $structure1 = $this->generateStructure(['is_diminutive' => true]);
        $structure2 = $this->generateStructure(['is_diminutive' => false]);

        $this->assertFalse($structure1->matchesStructure($structure2));
    }

    /** @test */
    public function structures_do_not_match_if_subject_differs()
    {
        $structure1 = $this->generateStructure(['subject_name' => 'FOO']);
        $structure2 = $this->generateStructure(['subject_name' => 'BAR']);

        $this->assertFalse($structure1->matchesStructure($structure2));
    }

    /** @test */
    public function structures_do_not_match_if_primary_object_differs()
    {
        $structure1 = $this->generateStructure(['primary_object_name' => 'FOO']);
        $structure2 = $this->generateStructure(['primary_object_name' => 'BAR']);

        $this->assertFalse($structure1->matchesStructure($structure2));
    }

    /** @test */
    public function structures_do_not_match_if_secondary_object_differs()
    {
        $structure1 = $this->generateStructure(['secondary_object_name' => 'FOO']);
        $structure2 = $this->generateStructure(['secondary_object_name' => 'BAR']);

        $this->assertFalse($structure1->matchesStructure($structure2));
    }

    /** @test */
    public function structures_match_if_subject_is_a_wildcard()
    {
        $structure1 = $this->generateStructure(['subject_name' => '?']);
        $structure2 = $this->generateStructure(['subject_name' => 'BAR']);

        $this->assertTrue($structure1->matchesStructure($structure2));
    }

    /** @test */
    public function structures_match_if_primary_object_is_a_wildcard()
    {
        $structure1 = $this->generateStructure(['primary_object_name' => '?']);
        $structure2 = $this->generateStructure(['primary_object_name' => 'BAR']);

        $this->assertTrue($structure1->matchesStructure($structure2));
    }

    /** @test */
    public function structures_match_if_secondary_object_is_a_wildcard()
    {
        $structure1 = $this->generateStructure(['secondary_object_name' => '?']);
        $structure2 = $this->generateStructure(['secondary_object_name' => 'BAR']);

        $this->assertTrue($structure1->matchesStructure($structure2));
    }

    /** @test */
    public function structures_match_if_is_negative_is_null()
    {
        $structure1 = $this->generateStructure(['is_negative' => null]);
        $structure2 = $this->generateStructure(['is_negative' => 1]);

        $this->assertTrue($structure1->matchesStructure($structure2));
    }

    /** @test */
    public function structures_match_if_is_diminutive_is_null()
    {
        $structure1 = $this->generateStructure(['is_diminutive' => null]);
        $structure2 = $this->generateStructure(['is_diminutive' => 0]);

        $this->assertTrue($structure1->matchesStructure($structure2));
    }

    /*
    |--------------------------------------------------------------------------
    | fromSearchQuery
    |--------------------------------------------------------------------------
    |
    */

    /** @test */
    public function it_can_instantiate_its_class_from_a_search_query()
    {
        $structure = VerbStructure::fromSearchQuery(['classes' => ['TA']]);
        $this->assertEquals('TA', $structure->class_abv);
    }

    /** @test */
    public function it_can_instantiate_its_order_from_a_search_query()
    {
        $structure = VerbStructure::fromSearchQuery(['orders' => ['Conjunct']]);
        $this->assertEquals('Conjunct', $structure->order_name);
    }

    /** @test */
    public function it_can_instantiate_its_mode_from_a_search_query()
    {
        $structure = VerbStructure::fromSearchQuery(['modes' => ['Indicative']]);
        $this->assertEquals('Indicative', $structure->mode_name);
    }

    /** @test */
    public function it_can_generate_a_wildcard_subject_from_a_search_query()
    {
        $structure = VerbStructure::fromSearchQuery([]);
        $this->assertEquals('?', $structure->subject_name);
    }

    /** @test */
    public function it_can_generate_a_wildcard_primary_object_from_a_search_query()
    {
        $structure = VerbStructure::fromSearchQuery([]);
        $this->assertEquals('?', $structure->primary_object_name);
    }

    /** @test */
    public function it_can_generate_a_wildcard_secondary_object_from_a_search_query()
    {
        $structure = VerbStructure::fromSearchQuery([]);
        $this->assertEquals('?', $structure->secondary_object_name);
    }

    /** @test */
    public function it_can_set_its_subject_to_null_from_a_search_query()
    {
        $structure = VerbStructure::fromSearchQuery(['subject' => false]);
        $this->assertNull($structure->subject_name);
    }

    /** @test */
    public function it_can_set_its_primary_object_to_null_from_a_search_query()
    {
        $structure = VerbStructure::fromSearchQuery(['primary_object' => false]);
        $this->assertNull($structure->primary_object_name);
    }

    /** @test */
    public function it_can_set_its_secondary_object_to_null_from_a_search_query()
    {
        $structure = VerbStructure::fromSearchQuery(['secondary_object' => false]);
        $this->assertNull($structure->secondary_object_name);
    }

    /** @test */
    public function it_can_infer_a_subject_from_a_search_query()
    {
        $structure = VerbStructure::fromSearchQuery([
            'subject_persons' => ['1'],
            'subject_numbers' => [1]
        ]);
        $this->assertEquals('1s', $structure->subject_name);
    }

    /** @test */
    public function it_can_infer_a_primary_object_from_a_search_query()
    {
        $structure = VerbStructure::fromSearchQuery([
            'primary_object_persons' => ['21'],
            'primary_object_numbers' => [3]
        ]);
        $this->assertEquals('21', $structure->primary_object_name);
    }

    /** @test */
    public function it_can_infer_a_secondary_object_from_a_search_query()
    {
        $structure = VerbStructure::fromSearchQuery([
            'secondary_object_persons' => ['3'],
            'secondary_object_numbers' => [2],
            'secondary_object_obviative_codes' => [1]
        ]);
        $this->assertEquals('3d\'', $structure->secondary_object_name);
    }
}
