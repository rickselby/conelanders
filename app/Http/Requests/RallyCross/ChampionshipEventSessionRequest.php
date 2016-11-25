<?php

namespace App\Http\Requests\RallyCross;

use App\Http\Requests\Request;
use Carbon\Carbon;

class ChampionshipEventSessionRequest extends Request
{
    protected $checkboxFields = [
        'heat'
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
            'heat' => 'required|boolean',
        ];
    }
}
