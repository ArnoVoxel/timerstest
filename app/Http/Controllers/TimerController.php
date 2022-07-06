<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimerRequest;
use App\Http\Resources\TimerResource;
use App\Models\Timer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TimerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /* $data = Timer::where('user_id', Auth::id())->orderBy('id', 'DESC')->paginate(4);
        return TimerResource::collection($data); */

        //$this->authorize('viewAny', Timer::class);

        $timersQuery = Timer::with(['company', 'category'])->where('user_id', Auth::id())->orderBy('id', 'DESC');
        $timers = $timersQuery->paginate(6, ['*'], 'page', $request['page'] ?? 1);

        return new JsonResource([
            'data' => TimerResource::collection($timers->items()),
            'total' => $timers->total(),
            'per_page' => $timers->perPage(),
        ]);

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
    public function store(TimerRequest $request)
    {
        Log::info($request->__toString());

        $timer = new Timer();
        $timer->user_id = Auth::id();
        $timer->category_id = request('category.id');
        $timer->company_id = request('company.id');
        $timer->started_at = new Carbon(now());

        DB::beginTransaction();

        // prevent a timer to still run
        // each new timer check the null state of ended_at
        Timer::where('user_id', Auth::id())->whereNull('ended_at')->update(['ended_at' => now()]);

        $timer->save();

        DB::commit();
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
    public function update(Request $request, Timer $timer)
    {
        Log::info($timer);
        Log::info($request->category);
        $data = [
                'started_at' => $request->started_at,
                'category_id' => $request->category,
                'company_id' => $request-> company,
            ];

        $timer->fill($data);
        Log::info($timer);
        $timer->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $user_id)
    {
        //Log::info('tentative de suppression');

        Timer::where('id', $id)
            ->where('user_id', $user_id)
            ->delete();;

    }
}
