<?php

namespace App\Http\Requests\Races;

use App\Http\Requests\Request;
use Carbon\Carbon;

class ChampionshipEventRequest extends Request
{
    protected $dateFields = [
        'time',
    ];

    protected $emptyIsNullFields = [
        'signup_open',
        'signup_close',
    ];

    public function validate()
    {
        parent::validate();
        Request::merge([
            'signup_open' => Request::get('signup_open')
                ? Carbon::createFromFormat(
                    $this->dateFormat,
                    Request::get('signup_open')
                )
                : null,
            'signup_close' => Request::get('signup_close')
                ? Carbon::createFromFormat(
                    $this->dateFormat,
                    Request::get('signup_close')
                )
                : null,
        ]);
    }

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
            'signup_open' => 'date_format:"'.$this->dateFormat.'"',
            'signup_close' => 'date_format:"'.$this->dateFormat.'"',
        ];
    }
}
