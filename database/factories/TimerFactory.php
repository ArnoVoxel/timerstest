<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Company;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

class TimerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = Faker::create();
        $startTime = Carbon::createFromTimestamp($faker->dateTimeBetween('-1 years','now')->getTimestamp());
        $tempTime = new Carbon($startTime);
        $endTime = $tempTime->addMinutes(rand(1,90));

        // get the difference in minutes
        $totalSpentMinutes = $endTime->diffInMinutes($startTime);
        if(($totalSpentMinutes / 60) >= 1){
            $hoursSpent = round($totalSpentMinutes / 60);
            $hoursSpent = sprintf("%02d", $hoursSpent);
        } else {
            $hoursSpent = 0;
        }

        $time_spent = sprintf("%02d", $hoursSpent).":".sprintf("%02d", $totalSpentMinutes%60);

        return [
            'started_at' => $startTime->toDateTimeString($unitPrecision = 'minute'),
            'ended_at' => $endTime->toDateTimeString($unitPrecision = 'minute'),
            'time_spent' => $time_spent,
            'user_id' => User::all()->random()->id,
            'category_id' => Category::all()->random()->id,
            'company_id' => Company::all()->random()->id,
            'ticket_id' => $faker->boolean(50) ? Ticket::all()->random()->id : null,
        ];
    }
}
