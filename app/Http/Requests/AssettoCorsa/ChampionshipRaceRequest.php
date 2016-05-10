<?php

namespace App\Http\Requests\AssettoCorsa;

use App\Http\Requests\Request;
use Carbon\Carbon;

class ChampionshipRaceRequest extends Request
{
    protected $dateFormat = 'jS F Y, H:i';

    public function validate()
    {
        parent::validate();
        Request::merge([
            'time' => Carbon::createFromFormat(
                $this->dateFormat,
                Request::get('time')
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
        ];
    }
}
