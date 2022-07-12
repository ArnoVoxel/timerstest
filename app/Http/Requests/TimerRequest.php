<?php

namespace App\Http\Requests;

use App\Rules\DateRange;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
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
            'started_at' => 'date_format:"Y-m-d H:i"',
            'ended_at' => [
                'date_format:"Y-m-d H:i"',
                'after:started_at']
        ];
    }
}
