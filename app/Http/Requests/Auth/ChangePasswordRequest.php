<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
//    public function authorize(): bool
//    {
//        return false;
//    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'old_password' => [
                'required',
                'current_password',
            ],
            'password' => [
                'required',
                Rules\Password::defaults()
            ],
            'password_confirmation' => [
                'required',
                'same:password'
            ]
        ];
    }

    public function messages()
    {
        return [
            'old_password.current_password' => 'Mật khẩu hiện tại không đúng',
            'password.min' => 'Mật khẩu mới có ít nhất 8 kí tự',
            'password_confirmation.same' => 'Mật khẩu xác nhận và mật khẩu mới không giống nhau',
        ];
    }

    public function attributes()
    {
        return [
            'old_password' => 'mật khẩu hiện tại',
            'password' => 'mật khẩu mới',
            'password_confirmation' => 'mật khẩu xác nhận',
        ];
    }
}
