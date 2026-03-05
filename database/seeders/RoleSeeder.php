<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin and Customer roles in one factory call using a sequence
        Role::factory()
            ->count(2)
            ->sequence(
                ['name' => 'Customer'],
                ['name' => 'Admin']

            )
            ->create();
    }
}
