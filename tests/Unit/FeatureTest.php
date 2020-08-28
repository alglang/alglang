<?php

namespace Tests\Unit;

use App\Models\Feature;
use Tests\TestCase;

class FeatureTest extends TestCase
{
    /** @test */
    public function it_can_be_inferred_from_a_person()
    {
        $feature = new Feature([
            'person' => '1',
            'number' => null,
            'obviative_code' => null
        ]);
        $this->assertEquals('1', $feature->name);
    }

    /** @test */
    public function it_can_be_inferred_from_a_person_and_a_number()
    {
        $feature = new Feature([
            'person' => '1',
            'number' => 2,
            'obviative_code' => null
        ]);
        $this->assertEquals('1d', $feature->name);
    }

    /** @test */
    public function it_can_be_inferred_from_a_person_and_an_obviative_code()
    {
        $feature = new Feature([
            'person' => '3',
            'number' => null,
            'obviative_code' => 2
        ]);
        $this->assertEquals('3"', $feature->name);
    }

    /** @test */
    public function it_does_not_infer_if_it_has_a_specified_name()
    {
        $feature = new Feature([
            'name' => 'X',
            'person' => '1'
        ]);
        $this->assertEquals('X', $feature->name);
    }

    /** @test */
    public function it_handles_21_correctly()
    {
        $feature = new Feature([
            'person' => '21',
            'number' => 3
        ]);
        $this->assertEquals('21', $feature->name);
    }
}
