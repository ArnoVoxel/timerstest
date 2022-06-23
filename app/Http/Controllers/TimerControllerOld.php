<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Company;
use App\Models\Timer;

class TimerControllerOld extends Controller
{
    public function newTimer()
    {
        $categories = Category::all();
        $companies = Company::all();
        $timers = Timer::orderBy('id', 'DESC')
                            ->get();

        //dd($categories);

        return view('create_timer', [
            'categories' => $categories,
            'companies' => $companies,
            'timers' => $timers,
        ]);
    }

    public function addTimer()
    {
        $timer = new Timer();
        $timer->user_id = '1';
    
        $timer->category_id = request('category');
        $timer->company_id = request('company');

        $oldTimer = Timer::count();
        //dd($oldTimer);

        if($oldTimer != 0){
            $timer->ended_at = Timer::all()->last()->started_at;
        }

        $timer->save();
        return back();
    }
}
