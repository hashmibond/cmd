<?php

namespace App\Http\Requests\API\AUTH;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResetPasswordRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'token' => 'required|max:100',
            'otp' => 'required|max:4',
            /*'phone' => 'required|max:11',*/
            'password' => 'required|string|min:4|max:4|confirmed',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator) : void
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => implode(",", $validator->errors()->all())
        ], 422));
    }
}
