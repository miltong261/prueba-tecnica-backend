<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class TypeVehicleRequest extends FormRequest
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
        $rules = [
            'rate' => ['required', 'numeric', 'between:0,9999.99']
        ];

        if ($this->method() == 'POST') {
            $rules['name'] = ['required', 'min:3', 'max:65', 'unique:type_vehicles,name'];
        }

        return $rules;
    }
}
