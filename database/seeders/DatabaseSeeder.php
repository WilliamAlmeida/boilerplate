<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Clientes;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PaisesSeeder::class);
        $this->call(EstadosSeeder::class);
        $this->call(CidadesSeeder::class);

        $this->call(VendedoresSeeder::class);

        $this->call(UsersSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);

        $this->call(ClientesSeeder::class);
    }
}
