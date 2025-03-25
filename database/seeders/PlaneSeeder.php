<?php

namespace Database\Seeders;

use App\Models\Plane;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlaneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $planes = [
            [
                'name' => 'A-501 Boeing 747',
                'max_seats' => 416,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'A-502 Boeing 747',
                'max_seats' => 200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'A-504 Boeing 747',
                'max_seats' => 500,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'A-503 Boeing 747',
                'max_seats' => 300,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'A-501 Boeing 747',
                'max_seats' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        foreach ($planes as $plane) {
            Plane::create($plane);
        }
    }
}
