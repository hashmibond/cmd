<?php

namespace App\Http\Requests\API\Terminal;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTerminalActionRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'terminalId' => 'required',
            /*'userId' => 'required',*/
            'terminalstatus' => 'required',
            'shuterSensorStatus' => 'required',
            'smokeSensorStatus' => 'required',
            'motionSensorStatus' => 'required',
            'gasSensorStatus' => 'required',
        ];
    }

    /*public function messages()
    {
        return [
            'terminalId.required'=>'The username field is required',
            'userId.required'=>'The password field is required',
        ];
    }*/

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator) : void
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Validation failed!',
            'errors' => implode(",", $validator->errors()->all())
        ], 422));
    }
}
