<?php

namespace Webkul\RevoDrip\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutSettingsRequest extends FormRequest
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
            'checkout.enabled'                     => ['nullable', 'boolean'],
            'checkout.show_delivery_zone'          => ['nullable', 'boolean'],
            'checkout.fields.name.label'           => ['required', 'string', 'max:60'],
            'checkout.fields.name.placeholder'     => ['nullable', 'string', 'max:120'],
            'checkout.fields.phone.label'          => ['required', 'string', 'max:60'],
            'checkout.fields.phone.placeholder'    => ['nullable', 'string', 'max:120'],
            'checkout.fields.email.label'          => ['required', 'string', 'max:60'],
            'checkout.fields.email.placeholder'    => ['nullable', 'string', 'max:120'],
            'checkout.fields.address.label'        => ['required', 'string', 'max:60'],
            'checkout.fields.address.placeholder'  => ['nullable', 'string', 'max:160'],
        ];
    }
}
