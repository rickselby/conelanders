<?php

namespace App\Http\Requests\RallyCross;

use App\Http\Requests\Request;
use App\Services\Times;
use Carbon\Carbon;

class ChampionshipEventSessionEntrantRequest extends Request
{
    protected $timeRegex = '/\d?\d:\d\d\.\d\d\d/';

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

    public function validate()
    {
        parent::validate();
        $this->timeToInt();
    }

    protected function timeToInt()
    {
        $this->mergeRequest(['time', 'penalty'], function($field) {
            return \Times::fromString(Request::input($field));
        });
    }
}
