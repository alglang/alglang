<?php

namespace Tests\Unit\Models;

use App\Models\Reflex;
use App\Models\Phoneme;
use PHPUnit\Framework\TestCase;

class ReflexTest extends TestCase
{
    /** @test */
    public function it_has_a_url()
    {
        $reflex = new Reflex([
            'phoneme' => new Phoneme(['language_code' => 'PA', 'slug' => 'x']),
            'reflex' => new Phoneme(['slug' => 'y'])
        ]);

        $this->assertEquals('/languages/PA/phonemes/x/reflexes/y', $reflex->url);
    }
}
