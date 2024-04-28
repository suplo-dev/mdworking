<?php

namespace App\Http\Requests\User;

use App\Enums\PermissionEnum;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AddUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
//    public function authorize(): bool
//    {
//        return true;
//    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
            ],
            'email' => [
                'required',
                'string',
                'email',
            ],
            'phone' => [
                'nullable',
                'string',
            ],
            'role' => [
                'required',
                'string',
                Rule::exists('roles', 'name'),
            ],
            'permissions' => [
                'array'
            ],
            'permissions.*' => [
                'string',
                Rule::in(PermissionEnum::all())
            ]
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('attributes.user.name'),
            'email' => __('attributes.user.email'),
            'phone' => __('attributes.user.phone'),
        ];
    }
}
