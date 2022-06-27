<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TimerControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('create_timer');

        $response->assertStatus(200);
    }

    /**
     * If the user is not connected, the function should return null.
     */
    public function testUserNotConnected()
    {
        $this->assertNull(auth()->user());
    }

    /**
     * > This function tests that a user is logged in
     */
    public function testUserConnected()
    {
        $user = User::where('id', 2)->first();
        Auth::login($user);

        $this->assertNotNull(auth()->user());
    }

    /**
     * > This function tests that a user who has not created a timer will not be able to see any timers
     */
    public function testTimersFromUserIdWithoutTimer()
    {
        $user = User::where('id', 3)->first();
        Auth::login($user);
        $response = $this->get('create_timer');
        $arrayResponse = json_decode($response->getContent())->data;

        $this->assertEmpty($arrayResponse);
    }

    /**
     * This function tests that the user can only see the timers that they have created
     */
    public function testTimersFromUserId()
    {
        $user = User::where('id', 2)->first();
        Auth::login($user);
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
}
