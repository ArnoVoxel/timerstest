<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Company;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

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

        return [
            'started_at' => $startTime->toDateTimeString(),
            'ended_at' => $startTime->addMinutes(rand(1,90)),
            'user_id' => User::all()->random()->id,
            'category_id' => Category::all()->random()->id,
            'company_id' => Company::all()->random()->id,
            'ticket_id' => $faker->boolean(50) ? Ticket::all()->random()->id : null,
        ];
    }
}
