<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePart extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'manufacturer_id'   =>  'required',
            'part_number'   =>  'required',
        ];
    }

}
