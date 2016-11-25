<?php

namespace App\Http\Requests\RallyCross;

use App\Http\Requests\Request;
use Carbon\Carbon;

class ChampionshipEventRequest extends Request
{
    protected $timeFields = [
        'time',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'time' => 'required|date_format:"'.$this->dateFormat.'"',
        ];
    }
}
