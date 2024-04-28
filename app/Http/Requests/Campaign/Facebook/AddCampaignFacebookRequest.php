<?php

namespace App\Http\Requests\Campaign\Facebook;

use App\Enums\PermissionEnum;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AddCampaignFacebookRequest extends FormRequest
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
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
            ],
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
