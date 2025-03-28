<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Flight;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       /*  $airplanes = Airplane::all();

        if ($airplanes->isEmpty()) {
            $this->command->info('No hay aviones disponibles. Primero crea algunos aviones.');
            return;
        } */
        $flights = [
            [
                'departure_date' => Carbon::now()->addDays(1)->toDateTimeString(),
                'arrival_date' => Carbon::now()->addDays(1)->addHours(5)->toDateTimeString(),
                'origin' => 'Madrid',
                'destination' => 'París',
                'plane_id' => '1',/* $airplanes->random()->id, */
                'available' => true,
            ],
            [
                'departure_date' => '2025-03-27 00:00:00',
                'arrival_date' => '2025-03-27 01:00:00',
                'origin' => 'Barcelona',
                'destination' => 'Roma',
                'plane_id' => '2',/* $airplanes->random()->id, */
                'available' => true,
            ],
            [
                'departure_date' =>'2025-03-26 00:00:00',
                'arrival_date' => '2025-03-26 01:00:00',
                'origin' => 'Berlín',
                'destination' => 'Londres',
                'plane_id' => '3',/* $airplanes->random()->id, */
                'available' => true,
            ],
            [
                'departure_date' => Carbon::now(3)->addDays(2)->toDateTimeString(),
                'arrival_date' => Carbon::now(4)->addDays(2)->addHours(3)->toDateTimeString(),
                'origin' => 'Madrid',
                'destination' => 'París',
                'plane_id' => '1',/* $airplanes->random()->id, */
                'available' => true,
            ],
            [
                'departure_date' => Carbon::now(2)->addDays(2)->toDateTimeString(),
                'arrival_date' => Carbon::now(2)->addDays(2)->addHours(3)->toDateTimeString(),
                'origin' => 'Barcelona',
                'destination' => 'Roma',
                'plane_id' => '2',/* $airplanes->random()->id, */
                'available' => true,
            ],
            [
                'departure_date' => Carbon::now(4)->addDays(3)->toDateTimeString(),
                'arrival_date' => Carbon::now(4)->addDays(3)->addHours(6)->toDateTimeString(),
                'origin' => 'Berlín',
                'destination' => 'Londres',
                'plane_id' => '3',/* $airplanes->random()->id, */
                'available' => true,
            ],
            [
                'departure_date' => Carbon::now(3)->addDays(1)->toDateTimeString(),
                'arrival_date' => Carbon::now(3)->addDays(1)->addHours(5)->toDateTimeString(),
                'origin' => 'Madrid',
                'destination' => 'París',
                'plane_id' => '4',/* $airplanes->random()->id, */
                'available' => true,
            ],
            [
                'departure_date' => Carbon::now()->addDays(2)->toDateTimeString(),
                'arrival_date' => Carbon::now()->addDays(2)->addHours(3)->toDateTimeString(),
                'origin' => 'Barcelona',
                'destination' => 'Roma',
                'plane_id' => '2',/* $airplanes->random()->id, */
                'available' => true,
            ],
            [
                'departure_date' => Carbon::now(9)->addDays(3)->toDateTimeString(),
                'arrival_date' => Carbon::now(9)->addDays(3)->addHours(6)->toDateTimeString(),
                'origin' => 'Berlín',
                'destination' => 'Londres',
                'plane_id' => '5',/* $airplanes->random()->id, */
                'available' => true,
            ],
            
        ];
        foreach ($flights as $flight) {
            Flight::create($flight);
        }
    }
}
