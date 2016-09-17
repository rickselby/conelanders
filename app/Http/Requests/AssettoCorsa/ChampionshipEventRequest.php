<?php

namespace App\Http\Requests\AssettoCorsa;

use App\Http\Requests\Request;
use Carbon\Carbon;

class ChampionshipEventRequest extends Request
{
    protected $dateFormat = 'jS F Y, H:i';

    protected $emptyIsNullFields = [
        'signup_open',
        'signup_close',
    ];

    public function validate()
    {
        parent::validate();
        Request::merge([
            'time' => Carbon::createFromFormat(
                $this->dateFormat,
                Request::get('time')
            ),
            'signup_open' => Carbon::createFromFormat(
                $this->dateFormat,
                Request::get('signup_open')
            ),
            'signup_close' => Carbon::createFromFormat(
                $this->dateFormat,
                Request::get('signup_close')
            ),
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
            'time' => 'date_format:"'.$this->dateFormat.'"',
            'signup_open' => 'date_format:"'.$this->dateFormat.'"',
            'signup_close' => 'date_format:"'.$this->dateFormat.'"',
        ];
    }
}
