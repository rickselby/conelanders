<?php

namespace App\Http\Requests\AssettoCorsa;

use App\Http\Requests\Request;

class ChampionshipEventSessionRequest extends Request
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
            'order' => 'required|numeric',
            'type' => 'required|numeric',
        ];
    }
}
