<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
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
            'holder_name' => 'required|string',
            'holder_gender' => ['required', Rule::in(['male', 'female'])],
            'holder_email' => 'required|email',
            'holder_phone' => 'digits_between:12,18',
            'purchase_amount' => 'required|numeric',
            'notes' => 'string',
        ];
    }
}
