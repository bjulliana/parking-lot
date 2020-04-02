<?php

use App\ParkingSpace;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        factory(ParkingSpace::class, 10)->create();
    }
}
