<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\TypeVehicle::create([
            'name' => 'Oficial',
            'rate' => 0.0
        ]);

        \App\Models\TypeVehicle::create([
            'name' => 'Residente',
            'rate' => 0.05
        ]);

        \App\Models\TypeVehicle::create([
            'name' => 'No Residente',
            'rate' => 0.5
        ]);

        \App\Models\Employee::create([
            'first_name' => 'Milton',
            'last_name' => 'Girón'
        ]);

        \App\Models\Employee::create([
            'first_name' => 'María José',
            'last_name' => 'Werner'
        ]);

        \App\Models\Employee::create([
            'first_name' => 'Roberto',
            'last_name' => 'López'
        ]);
    }
}
