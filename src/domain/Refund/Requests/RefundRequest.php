<?php

namespace Domain\Refund\Requests;

use Domain\Global\Rules\UserExists;
use Domain\Global\Traits\Validation;
use Domain\Order\Rules\OrderExists;
use Domain\Refund\Data\RefundData;
use Domain\Refund\Rules\RefundExists;
use Illuminate\Foundation\Http\FormRequest;

class RefundRequest extends FormRequest
{
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => [
                'nullable',
                new RefundExists(),
            ],
            'user' => [
                'required',
                'integer',
                new UserExists(),
            ],
            'order' => [
                'required',
                'string',
                new OrderExists(),
            ],
            'amount' => [
                'required',
                'numeric',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id.nullable' => 'The refund ID can be null.',
            'user.required' => 'The user ID is required.',
            'user.integer' => 'The user ID must be a valid integer.',
            'order.required' => 'The order ID is required.',
            'order.string' => 'The order ID must be a valid string.',
            'amount.required' => 'The refund amount is required.',
            'amount.numeric' => 'The refund amount must be a number.',
        ];
    }

    public function data($key = null, $default = null): RefundData
    {
        return new RefundData(
            $this->input('id'),
            (int)$this->input('user'),
            $this->input('order'),
            (float)$this->input('amount'),
        );
    }
}
