<?php

namespace App\Http\Requests\API\AUTH;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
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
            'identifier' => 'required',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'identifier.required'=>'The identifier field is required',
            'password.required'=>'The password field is required',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator) : void
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Validation failed!',
            'errors' => implode(",", $validator->errors()->all())
        ], 422));
    }
}
