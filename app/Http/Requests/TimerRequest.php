<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'ended_at' => 'date_format:Y/m/d|after_or_equal:started_at',
        ];
    }
}
