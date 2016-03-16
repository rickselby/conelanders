<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    /** @var string[] List of request fields that are checkboxes (and might not exist) **/
    protected $checkboxFields = [];

    // Authorisating is handled at controller level.
    public function authorize()
    {
        return true;
    }

    /**
     * When validating, first, set any checkbox fields that may be missing from the request;
     * then, run hoursToMinutes on time inputs
     */
    public function validate()
    {
        $this->setCheckboxes();
        parent::validate();
    }

    /**
     * Set any checkbox fields that might be missing
     */
    protected function setCheckboxes()
    {
        $this->mergeRequest($this->checkboxFields, function($field) {
            return Request::input($field, 0);
        });
    }

    /**
     * Alter the request object for the given fields using the given callback
     *
     * @param string[] $fieldList List of fields to work on
     * @param callback $callback  Function to run on the field
     */
    protected function mergeRequest($fieldList, $callback)
    {
        $mergeArray = [];
        foreach($fieldList AS $field) {
            $mergeArray[$field] = $callback($field);
        }
        Request::merge($mergeArray);
    }
}
