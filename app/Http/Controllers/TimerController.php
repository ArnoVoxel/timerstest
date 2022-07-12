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

use function PHPUnit\Framework\throwException;

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
     * Store a newly created timer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TimerRequest $request)
    {
        Log::info('store');
        Log::info($request->__toString());

        $timer = new Timer();
        $timer->user_id = Auth::id();
        $timer->category_id = request('category.id');
        $timer->company_id = request('company.id');
        $timer->started_at = new Carbon(now());

        Log::info($timer);

        DB::beginTransaction();

        // prevent a timer to still run
        // each new timer check the null state of ended_at
        Timer::where('user_id', Auth::id())->whereNull('ended_at')->update(['ended_at' => now()]);

        $timer->save();

        DB::commit();
    }

    /**
     * Stop all running timers and count the time_spent of the last timer
     */
    public function stopTimer(Request $request)
    {
        Timer::where('user_id', Auth::id())->whereNull('ended_at')->update(['ended_at' => now()]);

        // update the time_spent column for the last timer of the user 
        $timer = Timer::where('user_id', Auth::id())->get()->last();

        $timer->time_spent = self::getTimeSpent($timer->started_at, $timer->ended_at);
        $timer->save();
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
    public function update(TimerRequest $request, Timer $timer)
    {
        Log::info('update request : ');
        Log::info($timer);

        $dateStart = new Carbon($request->started_at);
        $dateEnd = new Carbon($request->ended_at);
        $timeSpent = self::getTimeSpent($request->started_at, $request->ended_at);

        $newTimeSpent = $request->time_spent;

        // get the old attributes to test
        $oldStartedAt = new Carbon($timer->started_at);
        $oldEndedAt = new Carbon($timer->ended_at);
        $oldTimeSpent = $timer->time_spent;
        $oldCategoryId = $timer->category_id;
        $oldCompanyId = $timer->company_id;

        Log::info('update $dateStart $dateEnd');
        Log::info($dateStart);
        Log::info($dateEnd);


        // if the time_spent is changed but not ended_at
        if($newTimeSpent != self::getTimeSpent($request->started_at, $request->ended_at) && $oldEndedAt == $dateEnd){
            Log::info('update timeSpent + ended_at before');
            Log::info($dateEnd);
            $addTime = strtotime($newTimeSpent, 0) / 60;
            //Log::info($addTime);

            $dateEnd = new Carbon($timer->started_at);
            $dateEnd->addMinutes($addTime);
            Log::info('update timeSpent + ended_at after');
            Log::info($dateEnd);
            $timeSpent = $newTimeSpent;
        }

        $data = [
                'started_at' => $dateStart,
                'ended_at' => $dateEnd,
                'time_spent' => $timeSpent,
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
            ->delete();

    }

    /**
     * It takes two dates and returns the difference in hours and minutes
     * 
     * @param startDate The start date of the task
     * @param endDate The date and time when the task was completed.
     * 
     * @return string time spent in hours and minutes.
     */
    public function getTimeSpent($startDate, $endDate){
        $startTime = new Carbon($startDate);
        $endTime = new Carbon($endDate);

        // get the difference in minutes
        $totalSpentMinutes = $endTime->diffInMinutes($startTime);
        if(($totalSpentMinutes / 60) >= 1){
            $hoursSpent = round($totalSpentMinutes / 60);
            $hoursSpent = sprintf("%02d", $hoursSpent);
        } else {
            $hoursSpent = 0;
        }

        $time_spent = $hoursSpent.":".sprintf("%02d", $totalSpentMinutes%60);

        return $time_spent;
    }
}
