<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Company;
use App\Models\Ticket;

class TimerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return ['id' => $this->id,
                'user_id' => $this->user_id,
                'started_at' => $this->started_at->toDateTimeString($unitPrecision = 'minute'),
                'ended_at' => isset($this->ended_at)? $this->ended_at->toDateTimeString($unitPrecision = 'minute') : $this->ended_at,
                'time_spent' => $this->time_spent,
                'company_id' => $this->company_id,
                'company_label' => optional($this->company)->label,
                // optional renvoie l'id si this->category n'est pas null
                'category_id' => optional($this->category)->id,
                'category_label' => optional($this->category)->label,
                'ticket' => $this->ticket_id,
                'isEdit' => false,
             ];
    }
}
