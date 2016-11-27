<?php

namespace App\Http\Requests\Races;

use App\Http\Requests\Request;

class ChampionshipAdminRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user' => 'required|exists:users,id',
        ];
    }
}
