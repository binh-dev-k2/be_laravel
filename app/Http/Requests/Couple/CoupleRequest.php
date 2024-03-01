<?php

namespace App\Http\Requests\Couple;

use App\Models\Couple\CoupleInvitation;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CoupleRequest extends FormRequest
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
            case 'invite':
                return [
                    'invited_email' => 'required|email'
                ];
                break;

            case 'updateInvite':
                return [
                    'user_uuid' => 'required|uuid',
                    'status' => ['required', 'in:' . implode(',', [
                        CoupleInvitation::STATUS_ACCEPTED,
                        CoupleInvitation::STATUS_REJECTED
                    ])]
                ];
                break;
        }
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(jsonResponse(1, $errors));
    }
}
