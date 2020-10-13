<?php

namespace Tests\Unit\Models;

use App\Models\FormGap;
use App\Models\NominalStructure;
use App\Models\VerbStructure;
use PHPUnit\Framework\TestCase;

class FormGapTest extends TestCase
{
    /** @test */
    public function verb_gaps_have_a_url()
    {
        $gap = new FormGap([
            'id' => 440,
            'language_code' => 'TL',
            'structure_type' => VerbStructure::class
        ]);

        $this->assertEquals('/languages/TL/verb-forms/gaps/440', $gap->url);
    }

    /** @test */
    public function nominal_gaps_have_a_url()
    {
        $gap = new FormGap([
            'id' => 440,
            'language_code' => 'TL',
            'structure_type' => NominalStructure::class
        ]);

        $this->assertEquals('/languages/TL/nominal-forms/gaps/440', $gap->url);
    }
}
