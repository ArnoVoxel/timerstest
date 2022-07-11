<?php

namespace App\Http\Requests;

use App\Rules\DateRange;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class TimerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company' => 'required',
            'category' => 'required',
            'started_at' => 'date_format: Y-m-d H:i',
            'ended_at' => ['date_format: Y-m-d H:i' , new DateRange()]
        ];
    }
}
