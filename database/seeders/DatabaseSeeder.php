<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call all seeders
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            PrioritySeeder::class,
            StatusSeeder::class,
            TagSeeder::class,
            ProjectSeeder::class,
            RolePermissionSeeder::class,
        ]);
    }
}
