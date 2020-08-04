<?php

namespace Tests\Feature;

use App\Traits\Sourceable;
use App\Source;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SourceableTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Model
     */
    private $testModel;

    /** @test */
    public function sources_can_be_added()
    {
        $sourcedClass = new class extends Model {
            use Sourceable;

            public $table = 'sourced';
        };

        $this->artisan('migrate', ['--path' => 'tests/database/migrations']);

        $source = factory(Source::class)->create(['author' => 'Foo Bar']);
        $sourced = $sourcedClass->create();

        $sourced->addSource($source);

        $this->assertCount(1, $sourced->sources);
        $this->assertEquals('Foo Bar', $sourced->sources[0]->author);
    }
}
