<?php

namespace App\Http\Requests;

use App\Constants\ErrorMessage;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('id');
        $user = User::find($userId);
        $tenantId = $user?->tenant_id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'email' => [
                'required',
                'email',
                Rule::unique('users')
                    ->where(function ($query) use ($tenantId) {
                        return $query->where(
                            'tenant_id',
                            $tenantId
                        );
                    })
                    ->ignore($userId),
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ErrorMessage::USER_NAME_REQUIRED,
            'name.string' => ErrorMessage::USER_NAME_STRING,
            'name.max' => ErrorMessage::USER_NAME_MAX,
            'email.required' => ErrorMessage::USER_EMAIL_REQUIRED,
            'email.email' => ErrorMessage::USER_EMAIL_FORMAT,
            'email.max' => ErrorMessage::USER_EMAIL_MAX,
            'email.unique' => ErrorMessage::USER_EMAIL_UNIQUE,
            'password.required' => ErrorMessage::USER_PASSWORD_REQUIRED,
            'password.string' => ErrorMessage::USER_PASSWORD_STRING,
            'password.min' => ErrorMessage::USER_PASSWORD_MIN,
        ];
    }
}
