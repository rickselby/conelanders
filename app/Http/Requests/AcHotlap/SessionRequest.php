<?php

namespace App\Http\Requests\AcHotlap;

use App\Http\Requests\Request;

class SessionRequest extends Request
{
    protected $dateFields = [
        'start',
        'finish',
    ];

    protected $dateFormat = 'jS F Y';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'start' => 'required|date_format:"'.$this->dateFormat.'"',
            'finish' => 'required|date_format:"'.$this->dateFormat.'"',
        ];
    }
}
