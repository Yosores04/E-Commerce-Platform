<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVendorRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id|unique:vendors,user_id',
            'business_name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:vendors,slug',
            'description' => 'nullable|string',
            'business_email' => 'required|email|unique:vendors,business_email',
            'business_phone' => 'required|string|max:20',
            'business_address' => 'required|string',
            'business_city' => 'required|string|max:100',
            'business_state' => 'nullable|string|max:100',
            'business_country' => 'required|string|max:100',
            'business_postal_code' => 'required|string|max:20',
            'tax_id' => 'nullable|string|max:50',
            'logo' => 'nullable|string|max:255',
            'banner' => 'nullable|string|max:255',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
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
            'user_id.required' => 'User is required',
            'user_id.exists' => 'User does not exist',
            'user_id.unique' => 'This user is already registered as a vendor',
            'business_name.required' => 'Business name is required',
            'business_email.required' => 'Business email is required',
            'business_email.email' => 'Please provide a valid business email',
            'business_email.unique' => 'This business email is already registered',
            'business_phone.required' => 'Business phone is required',
            'business_address.required' => 'Business address is required',
            'business_city.required' => 'City is required',
            'business_country.required' => 'Country is required',
            'business_postal_code.required' => 'Postal code is required',
            'commission_rate.min' => 'Commission rate must be at least 0',
            'commission_rate.max' => 'Commission rate cannot exceed 100',
        ];
    }
}
