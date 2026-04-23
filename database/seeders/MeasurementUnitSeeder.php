<?php

namespace Database\Seeders;

use App\Models\MeasurementUnit;
use Illuminate\Database\Seeder;

class MeasurementUnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = ['gram', 'kg', 'ml', 'liter', 'stuks'];

        foreach ($units as $unit) {
            MeasurementUnit::firstOrCreate(['name' => $unit]);
        }
    }
}
