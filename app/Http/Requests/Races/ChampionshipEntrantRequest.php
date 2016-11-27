<?php

namespace App\Http\Requests\Races;

use App\Http\Requests\Request;

class ChampionshipEntrantRequest extends Request
{
    protected $checkboxFields = [
        'rookie',
    ];

    protected $emptyIsNullFields = [
        'races_car_id',
        'races_team_id',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'driver' => 'required|string|exists:drivers,name',
            'rookie' => 'required|boolean',
            'number' => 'required|string',
            'colour' => 'required|string',
            'colour2' => 'required|string',
            'css' => 'string',
            'races_car_id' => 'exists:races_cars,id',
            'races_team_id' => 'exists:races_teams,id',
        ];
    }
}
