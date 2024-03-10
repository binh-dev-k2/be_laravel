<?php

namespace App\Http\Requests\CoupleInvitation;

use App\Models\CoupleInvitation\CoupleInvitation;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CoupleInvitationRequest extends FormRequest
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
                    'invitation_id' => 'required',
                    'status' => ['required', 'in:' . implode(',', [
                        CoupleInvitation::STATUS_ACCEPTED,
                        CoupleInvitation::STATUS_REJECTED,
                        CoupleInvitation::STATUS_DENIED
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
