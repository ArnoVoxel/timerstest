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
                'started_at' => $this->started_at->toDateTimeString(),
                'ended_at' => isset($this->ended_at)? $this->ended_at->toDateTimeString() : $this->ended_at,
                'time_spent' => $this->time_spent,
                'company_id' => $this->company_id,
                'company_name' => Company::find($this->company_id)->company_label,
                //'company_name' => CompanyResource::collection($this->company_id),
                'category' => Category::find($this->category_id)->category_label,
                'ticket' => $this->ticket_id
            ];
    }
}
