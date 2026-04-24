<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = ['Nieuw', 'Goedgekeurd', 'Geweigerd', 'Geannuleerd', 'Afgerond'];

        foreach ($states as $state) {
            State::firstOrCreate(['name' => $state]);
        }
    }
}
