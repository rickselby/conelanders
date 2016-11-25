<?php

namespace App\Http\Requests\RallyCross;

use App\Http\Requests\Request;

class ChampionshipEventRequest extends Request
{
    protected $dateFields = [
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
