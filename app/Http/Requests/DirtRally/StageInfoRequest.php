<?php

namespace App\Http\Requests\DirtRally;

use App\Http\Requests\Request;

class StageInfoRequest extends Request
{
    protected $timeFields = [
        'dnf_time',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'location_name' => 'required|string',
            'stage_name' => 'required|string',
            'dnf_time' => 'required|regex:'.$this->timeRegex,
        ];
    }

}
