<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MigrationTest extends TestCase
{
    /** @test */
    public function it_migrates_and_seeds_without_errors(): void
    {
        $exceptionWasThrown = false;
        DB::beginTransaction();

        try {
            $this->artisan('migrate --seed');
        } catch (\PDOException $e) {
            $exceptionWasThrown = true;
            throw $e;
        } finally {
            DB::rollback();
        }

        $this->assertFalse($exceptionWasThrown);
    }
}
