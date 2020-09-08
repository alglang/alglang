<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();

        $this->withoutMix();
    }

    public function withPermissions(): void
    {
        $this->seed('PermissionSeeder');
        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }

    public function migrateTestTables(): void
    {
        $this->artisan('migrate', ['--path' => 'tests/database/migrations']);
    }
}
