<?php

use App\Models\MeasurementUnit;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $kgUnitId = MeasurementUnit::firstOrCreate(['name' => 'kg'])->id;

        DB::table('ingredients')->update([
            'measurement_unit_id' => $kgUnitId,
        ]);
    }

    public function down(): void
    {
        $gramUnitId = MeasurementUnit::firstOrCreate(['name' => 'gram'])->id;

        DB::table('ingredients')->update([
            'measurement_unit_id' => $gramUnitId,
        ]);
    }
};
