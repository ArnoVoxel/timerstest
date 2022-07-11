<?php

namespace App\Models;

use Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Timer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'started_at',
        'ended_at',
        'time_spent',
        'category_id',
        'ticket_id',
        'user_id',
        'company_id',
    ];

    /**
     * Tells values of started_at and ended_at are dates
     */
    protected $casts = [
        'started_at' => 'datetime:Y-m-d H:i',
        'ended_at'=> 'datetime:Y-m-d H:i',
    ];

    /* public function getTimeSpentAttribute()
    {
        if($this->ended_at === null){
            return null;
        }
        $dateStart = new Carbon($this->started_at);
        $dateEnd = new Carbon($this->ended_at);

        //Log::info($dateStart);
        //Log::info(gettype($dateStart));
        

        // get the difference in minutes
        $totalSpentMinutes = $dateEnd->diffInMinutes($dateStart);
        

        //Log::info($totalSpentMinutes);

        // display the hours spent only if it's up to 1hour
        if(($totalSpentMinutes / 60) >= 1){
            $hoursSpent = round($totalSpentMinutes / 60);
            $hoursSpent = sprintf("%02d", $hoursSpent);
        } else {
            $hoursSpent = 0;
        }

        return $hoursSpent.":".sprintf("%02d", $totalSpentMinutes%60);
    } */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
