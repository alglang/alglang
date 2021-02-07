<?php

namespace Tests\Unit;

use App\Models\ClusterFeatureSet;
use App\Models\ConsonantFeatureSet;
use PHPUnit\Framework\TestCase;

class ClusterFeatureSetTest extends TestCase
{
    /** @test */
    public function its_shape_is_the_concatenation_of_its_segments_shapes(): void
    {
        $cluster = new ClusterFeatureSet([
            'firstSegment' => new ConsonantFeatureSet(['shape' => 'x']),
            'secondSegment' => new ConsonantFeatureSet(['shape' => 'y']),
        ]);

        $this->assertEquals('xy', $cluster->shape);
    }
}
