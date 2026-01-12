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
    // Users
    DB::table('users')->insert([
            [
                'naam' => 'Admin',
                'email' => 'admin@brooklyn.nl',
                'wachtwoord' => Hash::make('admin123'),
                'telefoon' => '0612345678',
                'type' => 3,
                'geboorte_datum' => '1990-01-01',
                'geslacht' => 'M',
                'adres' => 'Adminstraat 1',
                'auto_preference' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'naam' => 'Leerling',
                'email' => 'leerling@brooklyn.nl',
                'wachtwoord' => Hash::make('leerling123'),
                'telefoon' => '0612345679',
                'type' => 1,
                'geboorte_datum' => '2005-05-05',
                'geslacht' => 'V',
                'adres' => 'Leerlinglaan 2',
                'auto_preference' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'naam' => 'Instructeur',
                'email' => 'instructeur@brooklyn.nl',
                'wachtwoord' => Hash::make('instructeur123'),
                'telefoon' => '0612345680',
                'type' => 2,
                'geboorte_datum' => '1980-10-10',
                'geslacht' => 'M',
                'adres' => 'Instructeurweg 3',
                'auto_preference' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

    // Autos
    DB::table('autos')->insert([
            [
                'kenteken' => 'AB-123-C',
                'merk' => 'Volkswagen',
                'type' => 1,
                'beschikbaar' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kenteken' => 'XY-987-Z',
                'merk' => 'Toyota',
                'type' => 2,
                'beschikbaar' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

    // Strippenkaarten
    DB::table('strippenkaarten')->insert([
            [
                'leerling_id' => 2,
                'tegoed' => 10,
                'verval_datum' => Carbon::now()->addMonths(6),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

    // Kortingen
    DB::table('kortingen')->insert([
            [
                'leerling_id' => 2,
                'percentage' => 10,
                'reason' => 'Introductiekorting',
                'is_used' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
