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
            'started_at' => [
                'date',
                'before_or_equal:ended_at'],
            'ended_at' => [
                'date',
                'after_or_equal:started_at']
        ];
    }
}
