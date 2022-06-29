<?php

namespace Database\Seeders;

use App\Models\Timer;
use Illuminate\Database\Seeder;

class TimerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Timer::factory()->count(50)->create();
    }
}
