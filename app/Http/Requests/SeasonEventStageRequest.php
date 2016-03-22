<?php

namespace App\Http\Requests;

class SeasonEventStageRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'order' => 'required|integer',
            ''
        ];
    }
}
