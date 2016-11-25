<?php

namespace App\Http\Requests\RallyCross;

use App\Http\Requests\Request;

class ChampionshipRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'drop_events' => 'integer',
            'constructors_count' => 'required|integer',
        ];
    }
}
