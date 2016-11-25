<?php

namespace App\Http\Requests\RallyCross;

use App\Http\Requests\Request;

class ChampionshipEventSessionEntrantRequest extends Request
{
    protected $timeFields = [
        'time',
        'penalty',
        'lap',
    ];

    protected $checkboxFields = [
        'dnf',
        'dsq',
    ];

    protected $emptyIsNullFields = [
        'race'
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'entrant' => 'required|exists:rx_event_entrants,id',
            'race' => 'string',
            'time' => 'regex:'.$this->timeRegex,
            'lap' => 'regex:'.$this->timeRegex,
            'penalty' => 'regex:'.$this->timeRegex,
            'dnf' => 'boolean',
            'dsq' => 'boolean',
        ];
    }
}
