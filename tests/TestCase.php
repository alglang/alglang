<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\PermissionRegistrar;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();

        $this->withoutMix();
    }

    public function withPermissions()
    {
        $this->seed('PermissionSeeder');
        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }
}
