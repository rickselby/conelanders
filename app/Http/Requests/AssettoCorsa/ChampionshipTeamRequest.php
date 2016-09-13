<?php

namespace App\Http\Requests\AssettoCorsa;

use App\Http\Requests\Request;

class ChampionshipTeamRequest extends Request
{
    protected $checkboxFields = [
        'rookie',
    ];

    protected $emptyIsNullFields = [
        'ac_car_id',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ac_car_id' => 'exists:ac_cars,id',
            'name' => 'required|string',
            'short_name' => 'required|string',
        ];
    }
}
