<?php

namespace App\Http\Requests;

class NationRequest extends Request
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
            'acronym' => 'required|string',
            'dirt_reference' => 'required|integer',
        ];
    }
}
