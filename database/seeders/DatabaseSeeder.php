<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => 'admin1234',
            'is_admin' => true,
        ]);

        \App\Models\User::factory()->create([
            'name' => 'User',
            'email' => 'user@mail.com',
            'password' => 'user1234',
            'is_admin' => false,
        ]);
    }
}
