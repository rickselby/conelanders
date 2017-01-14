<?php

namespace App\Http\Requests\Races;

use App\Http\Requests\Request;

class PenaltyRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'entrant' => 'required|exists:races_session_entrants,id',
            'points' => 'required|numeric',
            'reason' => 'required',
        ];
    }
}
