<?php

namespace App\Http\Requests\Races;

use App\Http\Requests\Request;

class ChampionshipTeamRequest extends Request
{
    protected $checkboxFields = [
        'rookie',
    ];

    protected $emptyIsNullFields = [
        'races_car_id',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'races_car_id' => 'exists:races_cars,id',
            'name' => 'required|string',
            'short_name' => 'required|string',
            'css' => 'string'
        ];
    }
}
