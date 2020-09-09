<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\Str;
use Livewire\Livewire;
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

    protected function livewire(string $class, array $data = [])
    {
        return Livewire::test($class, $data);
    }
}
