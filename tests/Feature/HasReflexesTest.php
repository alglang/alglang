<?php

namespace Tests\Feature;

use App\Models\Phoneme;
use App\Models\Reflex;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HasReflexesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_phoneme_has_parents_through_reflexes()
    {
        $parent = Phoneme::factory()->create();
        $child = Phoneme::factory()->create();
        $reflex = Reflex::factory()->create([
            'phoneme_id' => $parent,
            'reflex_id' => $child,
            'environment' => 'wherever'
        ]);

        $parents = $child->parents;

        $this->assertCount(1, $parents);
        $this->assertEquals($parent->id, $parents[0]->id);
        $this->assertEquals('wherever', $parents[0]->pivot->environment);
    }

    /** @test */
    public function a_phoneme_has_children_through_reflexes()
    {
        $parent = Phoneme::factory()->create();
        $child = Phoneme::factory()->create();
        $reflex = Reflex::factory()->create([
            'phoneme_id' => $parent,
            'reflex_id' => $child,
            'environment' => 'wherever'
        ]);

        $children = $parent->children;

        $this->assertCount(1, $children);
        $this->assertEquals($child->id, $children[0]->id);
        $this->assertEquals('wherever', $children[0]->pivot->environment);
    }

    /** @test */
    public function a_phoneme_can_find_its_parents_from_a_given_language()
    {
        $grandparent = Phoneme::factory()->create(['language_code' => 'PA']);
        $parent = Phoneme::factory()->create();
        $child = Phoneme::factory()->create();

        Reflex::factory()->create([
            'phoneme_id' => $grandparent,
            'reflex_id' => $parent,
        ]);
        Reflex::factory()->create([
            'phoneme_id' => $parent,
            'reflex_id' => $child,
        ]);

        $paParents = $child->parentsFromLanguage('PA');

        $this->assertCount(1, $paParents);
        $this->assertEquals($grandparent->id, $paParents[0]->id);
    }

    /** @test */
    public function a_phoneme_without_any_given_parents_returns_an_empty_collection()
    {
        $parent = Phoneme::factory()->create();
        $child = Phoneme::factory()->create();

        Reflex::factory()->create([
            'phoneme_id' => $parent,
            'reflex_id' => $child,
        ]);

        $paParents = $child->parentsFromLanguage('PA');

        $this->assertCount(0, $paParents);
    }

    /** @test */
    public function a_phoneme_can_find_its_children_from_a_given_language()
    {
        $grandparent = Phoneme::factory()->create();
        $parent = Phoneme::factory()->create();
        $child = Phoneme::factory()->create(['language_code' => 'TL']);

        Reflex::factory()->create([
            'phoneme_id' => $grandparent,
            'reflex_id' => $parent,
        ]);
        Reflex::factory()->create([
            'phoneme_id' => $parent,
            'reflex_id' => $child,
        ]);

        $children = $grandparent->childrenFromLanguage('TL');

        $this->assertCount(1, $children);
        $this->assertEquals($child->id, $children[0]->id);
    }

    /** @test */
    public function a_phoneme_can_find_its_proto_algonquian_parents()
    {
        $grandparent = Phoneme::factory()->create(['language_code' => 'PA']);
        $parent = Phoneme::factory()->create();
        $child = Phoneme::factory()->create();

        Reflex::factory()->create([
            'phoneme_id' => $grandparent,
            'reflex_id' => $parent,
        ]);
        Reflex::factory()->create([
            'phoneme_id' => $parent,
            'reflex_id' => $child,
        ]);

        $paParents = $child->pa_parents;

        $this->assertCount(1, $paParents);
        $this->assertEquals($grandparent->id, $paParents[0]->id);
    }
}
