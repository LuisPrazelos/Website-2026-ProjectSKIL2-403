<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        Package::updateOrCreate(
            ['name' => 'Standaard'],
            [
                'description' => 'Basispakket voor eenvoudige evenementen.',
                'price' => 0,
                'is_standard' => true,
            ]
        );

        Package::updateOrCreate(
            ['name' => 'Premium'],
            [
                'description' => 'Uitgebreid pakket met extra service en luxere afwerking.',
                'price' => 49.99,
                'is_standard' => false,
            ]
        );

        Package::factory()->count(3)->create();
    }
}
