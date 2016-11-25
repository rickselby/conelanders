<?php

namespace App\Http\Requests\DirtRally;

use App\Http\Requests\Request;

class ChampionshipSeasonEventRequest extends Request
{
    protected $dateFields = [
        'opens',
        'closes',
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
            'racenet_event_id' => 'integer',
            'opens' => 'required|date_format:"'.$this->dateFormat.'"',
            'closes' => 'required|date_format:"'.$this->dateFormat.'"',
        ];
    }
}
