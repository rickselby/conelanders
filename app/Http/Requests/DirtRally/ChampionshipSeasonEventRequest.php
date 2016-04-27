<?php

namespace App\Http\Requests\DirtRally;

use App\Http\Requests\Request;
use Carbon\Carbon;

class ChampionshipSeasonEventRequest extends Request
{
    protected $dateFormat = 'jS F Y, H:i';

    public function validate()
    {
        parent::validate();
        Request::merge([
            'opens' => Carbon::createFromFormat(
                $this->dateFormat,
                Request::get('opens')
            ),
            'closes' => Carbon::createFromFormat(
                $this->dateFormat,
                Request::get('closes')
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
            'dirt_id' => 'integer',
            'opens' => 'date_format:"'.$this->dateFormat.'"',
            'closes' => 'date_format:"'.$this->dateFormat.'"',
        ];
    }
}
