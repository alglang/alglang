<?php

namespace Tests\Unit;

use App\Models\Source;
use App\Traits\Sourceable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SourceableTest extends TestCase
{
    use RefreshDatabase;

    private $sourcedClass;

    public function setUp(): void
    {
        parent::setUp();

        $this->sourcedClass = new class extends Model {
            use Sourceable;

            public $table = 'sourced';
        };

        $this->migrateTestTables();
    }

    /** @test */
    public function sources_can_be_added()
    {
        $source = factory(Source::class)->create(['author' => 'Foo Bar']);
        $sourced = $this->sourcedClass->create();

        $sourced->addSource($source);

        $this->assertCount(1, $sourced->sources);
        $this->assertEquals('Foo Bar', $sourced->sources[0]->author);
    }

    /** @test */
    public function sources_can_be_added_with_extra_info()
    {
        $source = factory(Source::class)->create(['author' => 'Foo Bar']);
        $sourced = $this->sourcedClass->create();

        $sourced->addSource($source, ['extra_info' => 'lorem ipsum']);

        $this->assertEquals('lorem ipsum', $sourced->sources[0]->attribution->extra_info);
    }

    /** @test */
    public function sources_can_be_added_with_descriptions()
    {
        $source = factory(Source::class)->create();
        $sourced = $this->sourcedClass->create();

        $sourced->addSource($source, ['description' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr']);

        $this->assertEquals(
            'Lorem ipsum dolor sit amet, consetetur sadipscing elitr',
            $sourced->sources[0]->attribution->description
        );
    }
}
