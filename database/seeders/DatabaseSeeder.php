<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Chamar outros seeders aqui
        $this->call([
            UsuarioSeeder::class,
        ]);

    }
}
