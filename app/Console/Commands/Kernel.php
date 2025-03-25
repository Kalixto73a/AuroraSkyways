<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define los comandos de la aplicación.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Define la programación de tareas.
     */
    protected function schedule(Schedule $schedule)
    {
        // Aquí puedes agregar los comandos programados
        $schedule->command('flights:update-status')->everyMinute();
    }
}
