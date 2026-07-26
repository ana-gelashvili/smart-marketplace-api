<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePaymentRequest extends FormRequest
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
            'order_id' => [
                'bail',
                'required',
                'string',
                'uuid',
                Rule::exists('orders', 'uuid')->where('member_id', $memberId),
            ],
            'gateway' => ['nullable', 'string', 'max:255'],
            'method' => ['nullable', 'string', 'max:255'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'meta' => ['nullable', 'array'],
        ];
    }
}
