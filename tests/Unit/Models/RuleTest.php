<?php

namespace Tests\Unit\Models;

use App\Models\Rule;
use PHPUnit\Framework\TestCase;

class RuleTest extends TestCase
{
    /** @test */
    public function it_has_a_url()
    {
        $rule = new Rule([
            'language_code' => 'PA',
            'abv' => 'RULE'
        ]);

        $this->assertEquals('/languages/PA/rules/RULE', $rule->url);
    }
}
