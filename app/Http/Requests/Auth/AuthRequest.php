<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class AuthRequest extends FormRequest
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
        $arr = explode('@', $this->route()->getActionName());
        $action = $arr[1];

        switch ($action) {
            case 'checkEmail':
                return [
                    'email' => 'required|email|string|max:150',
                ];
                break;

            case 'register':
                return [
                    'email' => 'required|email|string|max:150',
                    'password' => 'required|string|min:6|max:255|confirmed',
                    'name' => 'required|max:255',
                ];
                break;

            case 'login':
                return [
                    'email' => 'required|email|string|max:150',
                    'password' => 'required|string|min:6|max:255',
                ];
                break;

            case 'verifyEmail':
                return [
                    'email' => 'required|string|exists:users',
                    'code' => 'required|numeric|min:100000|max:999999'
                ];
                break;

            case 'resendVerificationEmail':
                return [
                    'email' => 'required|string|exists:users',
                ];
                break;
        }
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(xmlResponse(1, $errors));
    }
}
