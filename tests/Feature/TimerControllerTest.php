<?php

namespace Tests\Feature;

use App\Models\Timer;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Testing\Fluent\AssertableJson;

class TimerControllerTest extends TestCase
{

    public function setConnectedUser($user_id)
    {
        $user = User::where('id', $user_id)->first();
        if($user_id != null){
            Auth::login($user);
        }
    }

    /**
     * If the user is not connected, the function should return null.
     */
    public function test_user_not_connected()
    {
        $this->assertNull(auth()->user());
    }

    /**
     * > This function tests that a user is logged in
     */
    public function test_user_connected()
    {
        $user = User::where('id', 2)->first();
        Auth::login($user);

        $this->assertNotNull(auth()->user());
    }

    /**
     * > This function tests that a user who has not created a timer will not be able to see any timers
     */
    /* public function testTimersFromUserIdWithoutTimer()
    {
        self::setConnectedUser(3);
        $response = $this->get('create_timer');
        $arrayResponse = json_decode($response->getContent())->data;

        $this->assertEmpty($arrayResponse);
    } */

    /**
     * This function tests that the user can only see the timers that they have created
     */
    public function test_timers_from_user_id()
    {
        self::setConnectedUser(2);
        $response = $this->get('create_timer');
        $arrayResponse = json_decode($response->getContent())->data;

        //dd($arrayResponse);

        // count how many time the value id matches the id of the user_id
        $match = 0;

        foreach($arrayResponse as $key){
            if($key->user_id =='2'){
                $match++;
            }
        }

        $this->assertEquals(sizeof($arrayResponse), $match);
    }

    
    public function test_data_from_json()
    {
        self::setConnectedUser(rand(4,5));

        $this->json('get', 'api/timers')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' => [
                        '*' => [
                            'id',
                            'started_at',
                            'ended_at',
                            'category_id',
                            'user_id',
                            'company_id',
                            'ticket',
                        ]
                    ]
                ]
            );
    }

    /**
     * It creates a timer for the currently authenticated user
     */
    public function test_is_timer_created ()
    {
        self::setConnectedUser(rand(1,10));

        $timerData = [
            'user_id' => auth()->user()->id,
            'category' => ['id' => rand(1,3)],
            'company' => ['id' => rand(1,10)],
            'started_at' => now(),
        ];

        $response = $this->postJson('/api/timers', $timerData);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_stop_timer_of_user_id()
    {
        self::setConnectedUser(1);

        $timerData = [
            'user_id' => auth()->user()->id,
            'category' => ['id' => rand(1,3)],
            'company' => ['id' => rand(1,10)],
            'started_at' => now(),
        ];

        // create a new timer 
        $this->postJson('/api/timers', $timerData);

        // get the timers of the connected user
        $response = $this->getJson('/api/timers');

        // response structure if there is at least one timer created
        $response->assertJsonStructure(
            [
                'data' => [
                    0 => [
                        'id',
                        'user_id',
                        'started_at',
                        'ended_at',
                        'time_spent',
                        'company_id',
                        'company_label',
                        'category_id',
                        'ticket',
                    ]
                ]
            ]
        );
        
        $timersListe = $this->getJson('/api/timers');
        
        $json = $timersListe->json();
        //Log::info($json);
        $this->assertSame(null, $json['data'][0]['ended_at']);
        $this->assertNull($json['data'][0]['ended_at']);

        // request to stop the timer
        $this->getJson('/api/timers/stop-timer');

        // get the timers after stopping them
        $timersListeStop = $this->getJson('/api/timers');
        $jsonStop = $timersListeStop->json();

        //Log::info($jsonStop);
        $this->assertNotNull($jsonStop['data'][0]['ended_at']);

    }

    public function test_update_timer()
    {
        self::setConnectedUser(2);

        // define category and company to compare the updated timer
        $category_id = 1;
        $company_id = 1;

        // create a new timer
        $timerData = [
            'user_id' => auth()->user()->id,
            'category' => ['id' => $category_id],
            'company' => ['id' => $company_id],
            'started_at' => now(),
        ];

        $this->postJson('/api/timers', $timerData);

        // the last timer in Database is the new created one
        $newTimer = Timer::where('user_id', 2)->first();

        Log::info($newTimer);

        $this->assertEquals(1, $newTimer->category_id);
        $this->assertEquals(1, $newTimer->company_id);

        /* $newTimerDate = [
            'category' => ['id' => 2],
            'company' => ['id' => 2],
        ];

        $this->putJson('/api/timers/update/'+auth()->user()->id, []); */
    }
}
