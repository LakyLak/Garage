<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehiclesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vehicle::truncate();

        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            DB::table('vehicles')->insert(
                [
                    'registration_number' => $faker->regexify('[A-Z]{2}[0-9]{4}[A-Z]{2}'),
                    'vehicle_type'        => $faker->numberBetween(1, 3),
                    'card'                => $faker->numberBetween(1, 3),
                    'created_at'          => $faker->dateTimeBetween('-3 days', 'now')->format('Y-m-d H:i:s')
                ]
            );
        }
    }
}
