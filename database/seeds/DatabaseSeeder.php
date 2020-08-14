<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionSeeder::class);

        $this->call(SourceSeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(MorphemeSeeder::class);
        $this->call(VerbSeeder::class);
        $this->call(NominalSeeder::class);
    }
}
