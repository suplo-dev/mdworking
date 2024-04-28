<?php

namespace App\Http\Requests\Advertisement\Google;

use App\Enums\PermissionEnum;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchCampaignGoogleRequest extends FormRequest
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
            'per_page' => [
                'nullable',
            ],
            'page' => [
                'nullable',
            ],
            'column' => [
                'nullable',
            ],
            'sort' => [
                'nullable',
            ],
            'keyword' => [
                'nullable',
                'string',
            ],
            'started_at' => [
                'required',
                'date',
                'before:today',
            ],
            'ended_at' => [
                'required',
                'date',
                'before:today',
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

    public function messages()
    {
        return [
            'started_at.before' => 'Ngày bắt đầu không hợp lệ',
            'ended_at.before' => 'Ngày kết thúc không hợp lệ',
        ];
    }
}
