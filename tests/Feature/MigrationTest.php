<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MigrationTest extends TestCase
{
    /** @test */
    public function it_migrates_and_seeds_without_errors()
    {
        $exceptionWasThrown = false;

        try {
            $this->artisan('migrate:fresh --seed');
        } catch (\PDOException $e) {
            $exceptionWasThrown = true;
            throw $e;
        }

        $this->assertFalse($exceptionWasThrown);
    }
}
