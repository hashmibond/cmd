<?php

namespace App\Http\Requests\API\UserProfile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateAccountRequest extends FormRequest
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
            'name' => 'required|max:50',
            /*'phone' => 'required|max:11|unique:users,phone',*/
            'address' => 'nullable|max:250',
            'email' => 'nullable|email|max:50|unique:users,email',
            'password' => 'required|string|min:4|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
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
