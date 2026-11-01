<?php

namespace Webkul\RevoDrip\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Webkul\RevoDrip\Rules\BangladeshPhoneNumber;
use Webkul\RevoDrip\Services\DeliveryZoneService;

class CheckoutAddressRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'min:3', 'max:120'],
            'phone'         => ['required', 'string', 'max:30', new BangladeshPhoneNumber],
            'email'         => ['required', 'string', 'email:rfc', 'max:190'],
            'address'       => ['required', 'string', 'min:8', 'max:1000'],
            'delivery_zone' => [
                Rule::requiredIf(fn () => app(DeliveryZoneService::class)->selectionVisible()),
                'nullable',
                'string',
                Rule::in(app(DeliveryZoneService::class)->codes()),
            ],
        ];
    }

    /**
     * Clean the submitted values before validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name'    => is_string($this->input('name')) ? trim((string) preg_replace('/\s+/u', ' ', $this->input('name'))) : $this->input('name'),
            'phone'   => is_string($this->input('phone')) ? trim($this->input('phone')) : $this->input('phone'),
            'email'   => is_string($this->input('email')) ? trim($this->input('email')) : $this->input('email'),
            'address' => is_string($this->input('address')) ? trim($this->input('address')) : $this->input('address'),
        ]);
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'delivery_zone.in' => trans('revodrip::app.checkout.validation.zone'),
        ];
    }
}
