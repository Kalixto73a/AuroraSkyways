<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Flight;
use Carbon\Carbon;

class UpdateFlightStatus extends Command
{
    protected $signature = 'flights:update-status';
    protected $description = 'Actualizar el estado y disponibilidad de los vuelos automáticamente';

    public function handle()
    {
        $flights = Flight::all();
        foreach ($flights as $flight) {
            $flight->updateStatus();
        }

        $this->info('Estados y disponibilidad de los vuelos actualizados correctamente.');
    }
}

