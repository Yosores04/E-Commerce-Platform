<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'shipping_address_id' => 'required|exists:addresses,id',
            'billing_address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:credit_card,debit_card,paypal,bank_transfer,cash_on_delivery',
            'shipping_method' => 'nullable|string|max:100',
            'coupon_code' => 'nullable|string|exists:coupons,code',
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'shipping_address_id.required' => 'Shipping address is required',
            'shipping_address_id.exists' => 'Selected shipping address does not exist',
            'billing_address_id.required' => 'Billing address is required',
            'billing_address_id.exists' => 'Selected billing address does not exist',
            'payment_method.required' => 'Payment method is required',
            'payment_method.in' => 'Invalid payment method selected',
            'coupon_code.exists' => 'Invalid coupon code',
        ];
    }
}
