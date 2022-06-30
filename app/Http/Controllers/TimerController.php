<?php

namespace App\Http\Controllers;

use App\Http\Resources\TimerResource;
use App\Models\Timer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TimerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TimerResource::collection(Timer::where('user_id', Auth::id())->orderBy('id', 'DESC')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $timer = new Timer();
        $timer->user_id = Auth::id();
        $timer->category_id = request('category.id');
        $timer->company_id = request('company.id');
        $timer->started_at = new Carbon(now());

        // prevent a timer to still run
        // each new timer check the null state of ended_at
        Timer::where('user_id', Auth::id())->whereNull('ended_at')->update(['ended_at' => now()]);

        $timer->save();
    }

    /**
     * Stop all running timers
     */
    public function stopTimer(Request $request)
    {
        Timer::where('user_id', Auth::id())->whereNull('ended_at')->update(['ended_at' => now()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Log::info($request);
        Timer::where('user_id', Auth::id())
            ->update([
                'started_at' => $request->started_at,
                'category_id' => $request->category,
                'company_id' => $request-> company,
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
