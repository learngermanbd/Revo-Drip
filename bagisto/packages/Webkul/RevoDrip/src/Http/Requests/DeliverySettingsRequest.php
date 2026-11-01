<?php

namespace Webkul\RevoDrip\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Webkul\RevoDrip\Services\DeliveryZoneService;

class DeliverySettingsRequest extends FormRequest
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
            'delivery.enabled'                     => ['nullable', 'boolean'],
            'delivery.default_zone'                => ['required', 'string', Rule::in(app(DeliveryZoneService::class)->codes())],
            'delivery.zones.inside_dhaka.label'    => ['required', 'string', 'max:60'],
            'delivery.zones.inside_dhaka.charge'   => ['required', 'numeric', 'min:0', 'max:100000'],
            'delivery.zones.outside_dhaka.label'   => ['required', 'string', 'max:60'],
            'delivery.zones.outside_dhaka.charge'  => ['required', 'numeric', 'min:0', 'max:100000'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'delivery.default_zone.in' => trans('revodrip::app.admin.delivery.invalid-zone'),
        ];
    }

    /**
     * Refuse to switch the zones off when that would leave stockable products with no
     * shipping method at all, which would make checkout impossible.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ((string) $this->input('delivery.enabled', '1') === '1') {
                return;
            }

            if (app(DeliveryZoneService::class)->alternativeCarrier() !== null) {
                return;
            }

            $validator->errors()->add(
                'delivery.enabled',
                trans('revodrip::app.admin.delivery.disable-blocked')
            );
        });
    }
}
