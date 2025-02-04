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
                'airplane_id' => 'A-500',/* $airplanes->random()->id, */
                'available' => true,
            ],
            [
                'departure_date' => Carbon::now()->addDays(2)->toDateTimeString(),
                'arrival_date' => Carbon::now()->addDays(2)->addHours(3)->toDateTimeString(),
                'origin' => 'Barcelona',
                'destination' => 'Roma',
                'airplane_id' => 'A-500',/* $airplanes->random()->id, */
                'available' => true,
            ],
            [
                'departure_date' => Carbon::now()->addDays(3)->toDateTimeString(),
                'arrival_date' => Carbon::now()->addDays(3)->addHours(6)->toDateTimeString(),
                'origin' => 'Berlín',
                'destination' => 'Londres',
                'airplane_id' => 'A-500',/* $airplanes->random()->id, */
                'available' => true,
            ],
            [
                'departure_date' => Carbon::now()->addDays(1)->toDateTimeString(),
                'arrival_date' => Carbon::now()->addDays(1)->addHours(5)->toDateTimeString(),
                'origin' => 'Madrid',
                'destination' => 'París',
                'airplane_id' => 'A-500',/* $airplanes->random()->id, */
                'available' => true,
            ],
            [
                'departure_date' => Carbon::now()->addDays(2)->toDateTimeString(),
                'arrival_date' => Carbon::now()->addDays(2)->addHours(3)->toDateTimeString(),
                'origin' => 'Barcelona',
                'destination' => 'Roma',
                'airplane_id' => 'A-500',/* $airplanes->random()->id, */
                'available' => true,
            ],
            [
                'departure_date' => Carbon::now()->addDays(3)->toDateTimeString(),
                'arrival_date' => Carbon::now()->addDays(3)->addHours(6)->toDateTimeString(),
                'origin' => 'Berlín',
                'destination' => 'Londres',
                'airplane_id' => 'A-500',/* $airplanes->random()->id, */
                'available' => true,
            ],
            [
                'departure_date' => Carbon::now()->addDays(1)->toDateTimeString(),
                'arrival_date' => Carbon::now()->addDays(1)->addHours(5)->toDateTimeString(),
                'origin' => 'Madrid',
                'destination' => 'París',
                'airplane_id' => 'A-500',/* $airplanes->random()->id, */
                'available' => true,
            ],
            [
                'departure_date' => Carbon::now()->addDays(2)->toDateTimeString(),
                'arrival_date' => Carbon::now()->addDays(2)->addHours(3)->toDateTimeString(),
                'origin' => 'Barcelona',
                'destination' => 'Roma',
                'airplane_id' => 'A-500',/* $airplanes->random()->id, */
                'available' => true,
            ],
            [
                'departure_date' => Carbon::now()->addDays(3)->toDateTimeString(),
                'arrival_date' => Carbon::now()->addDays(3)->addHours(6)->toDateTimeString(),
                'origin' => 'Berlín',
                'destination' => 'Londres',
                'airplane_id' => 'A-500',/* $airplanes->random()->id, */
                'available' => true,
            ],
            
        ];
        foreach ($flights as $flight) {
            Flight::create($flight);
        }
    }
}
