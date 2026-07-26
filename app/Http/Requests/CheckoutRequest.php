<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $memberId = $this->user()?->id;

        return [
            'shipping_address_id' => [
                'nullable',
                'integer',
                Rule::exists('member_addresses', 'id')->where('member_id', $memberId),
            ],
            'billing_address_id' => [
                'nullable',
                'integer',
                Rule::exists('member_addresses', 'id')->where('member_id', $memberId),
            ],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
