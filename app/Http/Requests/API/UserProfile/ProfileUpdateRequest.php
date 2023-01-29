<?php

namespace App\Http\Requests\API\UserProfile;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
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
            'name' => 'nullable','max:50',
            'phone' => 'nullable','max:11',Rule::unique(User::class)->ignore($this->user()->id),
            'email' => 'nullable','max:50',Rule::unique(User::class)->ignore($this->user()->id),
            'address' => 'nullable','max:250',
            'password' => 'nullable', 'string', 'min:4', 'max:4', 'confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
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
