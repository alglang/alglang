<?php

namespace Tests\Unit\Traits;

use App\Traits\HasParent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HasParentTest extends TestCase
{
    private $class;

    public function setUp(): void
    {
        parent::setUp();

        $this->class = new class extends Model {
            use HasParent;

            public $table = 'parented';
            protected $guarded = [];
        };

        $this->migrateTestTables();
    }

    /** @test */
    public function it_retrieves_its_parent()
    {
        $parent = $this->class->create(['id' => 1, 'name' => 'Foo']);
        $child = $this->class->create(['parent_id' => 1]); 

        $this->assertEquals('Foo', $child->parent->name);
    }

    /** @test */
    public function it_retrieves_its_children()
    {
        $parent = $this->class->create(['id' => 1]);
        $child1 = $this->class->create(['parent_id' => 1, 'name' => 'Foo']); 
        $child2 = $this->class->create(['parent_id' => 1, 'name' => 'Bar']); 

        $children = $parent->children;

        $this->assertCount(2, $children);
        $this->assertEquals(['Foo', 'Bar'], $children->pluck('name')->toArray());
    }

    /** @test */
    public function it_retrieves_its_ancestors()
    {
        $parent1 = $this->class->create(['id' => 1, 'name' => 'Foo']);
        $parent2 = $this->class->create(['id' => 2, 'parent_id' => 1, 'name' => 'Bar']);
        $child = $this->class->create(['parent_id' => 2]);

        $ancestors = $child->ancestors;

        $this->assertCount(2, $ancestors);
        $this->assertEquals(['Bar', 'Foo'], $ancestors->pluck('name')->toArray());
    }

    /** @test */
    public function it_retrieves_its_descendants()
    {
        $parent = $this->class->create(['id' => 1]);
        $child1 = $this->class->create(['id' => 2, 'parent_id' => 1, 'name' => 'Foo']); 
        $child2 = $this->class->create(['parent_id' => 2, 'name' => 'Bar']); 

        $descendants = $parent->descendants;

        $this->assertCount(2, $descendants);
        $this->assertEquals(['Foo', 'Bar'], $descendants->pluck('name')->toArray());
    }
}
