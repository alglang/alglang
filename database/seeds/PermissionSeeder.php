<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'create groups']);
        Permission::create(['name' => 'create languages']);
        Permission::create(['name' => 'edit languages']);
        Permission::create(['name' => 'view private notes']);

        Role::create(['name' => 'contributor'])
            ->givePermissionTo(
                'create groups',
                'create languages',
                'edit languages',
                'view private notes'
            );
    }
}
