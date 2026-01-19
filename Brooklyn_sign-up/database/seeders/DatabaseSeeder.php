<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Auto;
use App\Models\Strippenkaart;
use App\Models\Korting;
use App\Models\RoosterItem;
use App\Models\Verslag;
use App\Models\Review;
use App\Models\ReviewFlag;
use App\Models\AutoGebruik;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
    $faker = \Faker\Factory::create('nl_NL');\

        // AUTOS
        $autos = [];
        for ($i = 0; $i < 5; $i++) {
            $autos[] = Auto::create([
                'kenteken' => strtoupper($faker->bothify('??-###-?')),
                'merk' => $faker->company,
                'type' => $faker->numberBetween(1, 3),
                'beschikbaar' => $faker->boolean,
                'foto' => $faker->imageUrl(640, 480, 'cars', true),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // USERS
        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $straat = $faker->streetName;
            $huisnummer = $faker->buildingNumber;
            $postcode = strtoupper($faker->postcode);
            $stad = $faker->city;
            $adres = "$straat -=- $huisnummer -=- $postcode -=- $stad";
            $users[] = User::create([
                'naam' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make($faker->password),
                'telefoon' => $faker->phoneNumber,
                'type' => $faker->numberBetween(1, 3),
                'geboorte_datum' => Carbon::parse($faker->date('Y-m-d', '2010-01-01'))->format('d/m/y'),
                'geslacht' => $faker->randomElement(['Man', 'Vrouw']),
                'adres' => $adres,
                'auto_preference' => optional($faker->optional()->randomElement($autos))->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // Add 4 extra users with specific names, types, and a given hashed password
        $extraUsers = [
            ['naam' => 'admin', 'type' => 3],
            ['naam' => 'instructeir', 'type' => 2],
            ['naam' => 'leerling', 'type' => 1],
            ['naam' => 'guest', 'type' => 0],
        ];
        foreach ($extraUsers as $extra) {
            $straat = $faker->streetName;
            $huisnummer = $faker->buildingNumber;
            $postcode = strtoupper($faker->postcode);
            $stad = $faker->city;
            $adres = "ergensweg $straat -=- $huisnummer -=- $postcode -=- ergensstad $stad";
            $users[] = User::create([
                'naam' => $extra['naam'],
                'email' => $faker->unique()->safeEmail,
                'password' => '$2y$12$DD/1nEiqpUa3kyw1jlqypu9Z.BEzJo.5RKJnxDoFFwBVqkGY/Ie/y',
                'telefoon' => $faker->phoneNumber,
                'type' => $extra['type'],
                'geboorte_datum' => Carbon::parse($faker->date('Y-m-d', '2010-01-01'))->format('d/m/y'),
                'geslacht' => $faker->randomElement(['Man', 'Vrouw']),
                'adres' => $adres,
                'auto_preference' => optional($faker->optional()->randomElement($autos))->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // STRIPPENKAART
        for ($i = 0; $i < 5; $i++) {
            Strippenkaart::create([
                'leerling_id' => $faker->randomElement($users)->id,
                'tegoed' => $faker->numberBetween(1, 20),
                'verval_datum' => Carbon::now()->addMonths($faker->numberBetween(1, 12))->format('d/m/y H:i:s'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // KORTINGEN
        for ($i = 0; $i < 5; $i++) {
            Korting::create([
                'leerling_id' => $faker->randomElement($users)->id,
                'percentage' => $faker->numberBetween(5, 50),
                'reason' => $faker->sentence,
                'is_used' => $faker->boolean,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // ROOSTER ITEMS
        $roosterItems = [];
        for ($i = 0; $i < 10; $i++) {
            $roosterItems[] = RoosterItem::create([
                'leerling_id' => $faker->randomElement($users)->id,
                'instructeur_id' => $faker->randomElement($users)->id,
                'datum_en_tijd' => $faker->dateTimeBetween('-1 month', '+1 month')->format('d/m/y H:i:s'),
                'auto' => $faker->randomElement($autos)->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // VERSLAGEN
        foreach ($roosterItems as $item) {
            Verslag::create([
                'rooster_item_id' => $item->id,
                'verslag' => $faker->paragraph,
                'datum_gemaakt' => Carbon::parse($faker->date('Y-m-d', '-1 month'))->format('d/m/y H:i:s'),
                'datum_aangepast' => Carbon::parse($faker->date('Y-m-d'))->format('d/m/y H:i:s'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // // REVIEWS
        // $reviews = [];
        // foreach ($roosterItems as $item) {
        //     $reviews[] = Review::create([
        //         'rooster_item_id' => $item->id,
        //         'rating' => $faker->numberBetween(1, 5),
        //         'comment' => $faker->sentence,
        //         'status' => $faker->numberBetween(0, 2),
        //         'leerling_id' => $item->leerling_id,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ]);
        // }

        // // REVIEW FLAGS (only ~1/3 of reviews get flagged)
        // $flaggedReviews = $faker->randomElements($reviews, (int) ceil(count($reviews) / 3));
        // foreach ($flaggedReviews as $review) {
        //     ReviewFlag::create([
        //         'review_id' => $review->id,
        //         'reason' => $faker->sentence,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ]);
        // }

        // AUTO GEBRUIK
        foreach ($autos as $auto) {
            for ($i = 0; $i < $faker->numberBetween(1, 3); $i++) {
                AutoGebruik::create([
                    'auto_id' => $auto->id,
                    'start_gebruik' => $faker->dateTimeBetween('-2 months', '-1 month')->format('d/m/y H:i:s'),
                    'eind_gebruik' => $faker->dateTimeBetween('-1 month', 'now')->format('d/m/y H:i:s'),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
