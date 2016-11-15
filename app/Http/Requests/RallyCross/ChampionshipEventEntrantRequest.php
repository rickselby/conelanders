<?php

namespace App\Http\Requests\RallyCross;

use App\Http\Requests\Request;
use App\Services\Times;
use Carbon\Carbon;

class ChampionshipEventEntrantRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'driver' => 'required|string|exists:drivers,name',
            'car' => 'required|exists:rx_cars,id',
        ];
    }
}
