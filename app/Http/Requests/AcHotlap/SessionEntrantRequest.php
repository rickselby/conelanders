<?php

namespace App\Http\Requests\AcHotlap;

use App\Http\Requests\Request;

class SessionEntrantRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'driver' => 'required|string|exists:drivers,name',
            'car' => 'required|exists:races_cars,id',
            'time' => 'required|regex:'.$this->timeRegex,
            # this should be the time regex, multiple times, comma separated
            'sectors' => [
                'regex:/((^$)|(^(\d?\d:\d\d.\d\d\d)((,|\t)(\d?\d:\d\d.\d\d\d))*$))/'
            ],
        ];
    }
}
