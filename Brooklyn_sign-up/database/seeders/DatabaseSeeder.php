<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
    $faker = \Faker\Factory::create();

    // Users
    for ($i = 0; $i < 10; $i++) {
        \App\Models\User::create([
            'naam' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'password' => Hash::make($faker->password),
            'telefoon' => $faker->phoneNumber,
            'type' => $faker->numberBetween(1, 3),
            'geboorte_datum' => $faker->date('Y-m-d', '2010-01-01'),
            'geslacht' => $faker->randomElement(['M', 'V']),
            'adres' => $faker->address,
            'auto_preference' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    // Autos
    for ($i = 0; $i < 5; $i++) {
        \App\Models\Auto::create([
            'kenteken' => strtoupper($faker->bothify('??-###-?')),
            'merk' => $faker->company,
            'type' => $faker->numberBetween(1, 3),
            'beschikbaar' => $faker->boolean,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    // Strippenkaarten
    for ($i = 0; $i < 5; $i++) {
        \App\Models\Strippenkaart::create([
            'leerling_id' => $faker->numberBetween(1, 10),
            'tegoed' => $faker->numberBetween(1, 20),
            'verval_datum' => Carbon::now()->addMonths($faker->numberBetween(1, 12)),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    // Kortingen
    for ($i = 0; $i < 5; $i++) {
        \App\Models\Korting::create([
            'leerling_id' => $faker->numberBetween(1, 10),
            'percentage' => $faker->numberBetween(5, 50),
            'reason' => $faker->sentence,
            'is_used' => $faker->boolean,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
    }
}
