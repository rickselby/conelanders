<?php

namespace App\Http\Requests\AssettoCorsa;

use App\Http\Requests\Request;

class ChampionshipEntrantRequest extends Request
{
    protected $checkboxFields = [
        'rookie',
    ];

    protected $emptyIsNullFields = [
        'ac_car_id',
        'ac_team_id',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'driver_id' => 'required|exists:drivers,id',
            'rookie' => 'required|boolean',
            'number' => 'required|string',
            'colour' => 'required|string',
            'colour2' => 'required|string',
            'css' => 'string',
            'ac_car_id' => 'exists:ac_cars,id',
            'ac_team_id' => 'exists:ac_teams,id',
        ];
    }
}
