<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'type' => User::ADMIN,
            'email' => 'williamkillerca@hotmail.com',
            'password' => bcrypt('12345678'),
            'email_verified_at' => now(),
            'timezone' => 'America/Sao_Paulo',
        ]);

        User::factory()->create([
            'name' => 'Demo',
            'type' => User::USER,
            'email' => 'demo@exemplo.com',
            'password' => bcrypt('demo1234'),
            'email_verified_at' => now(),
            'timezone' => 'America/Sao_Paulo',
        ]);
    }
}
