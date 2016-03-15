<?php

namespace App\Http\Requests;

use Carbon\Carbon;

class SeasonEventRequest extends Request
{
    protected $dateFormat = 'jS F Y, H:i';
    public function authorize()
    {
        return true;
    }

    public function validate()
    {
        parent::validate();
        Request::merge([
            'closes' => Carbon::createFromFormat(
                $this->dateFormat,
                Request::get('closes'),
                new \DateTimeZone('UTC')
            )
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
            'dirt_id' => 'integer',
            'closes' => 'date_format:"'.$this->dateFormat.'"',
        ];
    }
}
