<?php

namespace Database\Seeders;

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
        //\App\Models\User::factory(1000)->create();
        \App\Models\Familia::factory(1000)->create();

        $this->call([
            UserSeeder::class,
            escolaridadeSeeder::class,
            estadocivilSeeder::class,
            bairroSeeder::class,
            etapaSeeder::class,
            tagSeeder::class,
            MenuSeeder::class,
            PermissionSeeder::class,
        ]);

    }
}
