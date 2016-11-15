<?php

namespace App\Http\Requests\RallyCross;

use App\Http\Requests\Request;

class CarRequest extends Request
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
            'short_name' => 'required|string',
        ];
    }
}
