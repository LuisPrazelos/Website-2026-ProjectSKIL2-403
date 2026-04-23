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
                'price_history' => [
                    ['date' => '2025-10-01', 'price' => 0],
                    ['date' => '2025-11-01', 'price' => 0],
                    ['date' => '2025-12-01', 'price' => 0],
                    ['date' => '2026-01-01', 'price' => 0],
                    ['date' => '2026-02-01', 'price' => 0],
                    ['date' => '2026-03-01', 'price' => 0],
                    ['date' => '2026-04-01', 'price' => 0],
                ],
            ]
        );

        Package::updateOrCreate(
            ['name' => 'Premium'],
            [
                'description' => 'Uitgebreid pakket met extra service en luxere afwerking.',
                'price' => 49.99,
                'is_standard' => false,
                'price_history' => [
                    ['date' => '2025-10-01', 'price' => 34.99],
                    ['date' => '2025-11-01', 'price' => 34.99],
                    ['date' => '2025-12-01', 'price' => 39.99],
                    ['date' => '2026-01-01', 'price' => 39.99],
                    ['date' => '2026-02-01', 'price' => 44.99],
                    ['date' => '2026-03-01', 'price' => 44.99],
                    ['date' => '2026-04-01', 'price' => 49.99],
                ],
            ]
        );

        Package::updateOrCreate(
            ['name' => 'Luxe'],
            [
                'description' => 'Het ultieme pakket met persoonlijke begeleiding en exclusieve decoratie.',
                'price' => 99.99,
                'is_standard' => false,
                'price_history' => [
                    ['date' => '2025-10-01', 'price' => 69.99],
                    ['date' => '2025-11-01', 'price' => 74.99],
                    ['date' => '2025-12-01', 'price' => 79.99],
                    ['date' => '2026-01-01', 'price' => 84.99],
                    ['date' => '2026-02-01', 'price' => 89.99],
                    ['date' => '2026-03-01', 'price' => 94.99],
                    ['date' => '2026-04-01', 'price' => 99.99],
                ],
            ]
        );

        Package::updateOrCreate(
            ['name' => 'Zakelijk'],
            [
                'description' => 'Speciaal pakket voor zakelijke evenementen en bedrijfsfeesten.',
                'price' => 74.99,
                'is_standard' => false,
                'price_history' => [
                    ['date' => '2025-10-01', 'price' => 59.99],
                    ['date' => '2025-11-01', 'price' => 59.99],
                    ['date' => '2025-12-01', 'price' => 64.99],
                    ['date' => '2026-01-01', 'price' => 64.99],
                    ['date' => '2026-02-01', 'price' => 69.99],
                    ['date' => '2026-03-01', 'price' => 74.99],
                    ['date' => '2026-04-01', 'price' => 74.99],
                ],
            ]
        );
    }
}
