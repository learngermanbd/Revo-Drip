<?php

namespace Webkul\RevoDrip\Services;

class AddressMapper
{
    /**
     * Split one full name into the first and last name Bagisto stores separately.
     */
    public function splitName(string $fullName): array
    {
        $fullName = trim((string) preg_replace('/\s+/u', ' ', $fullName));

        if ($fullName === '') {
            return ['first_name' => '', 'last_name' => ''];
        }

        $parts = explode(' ', $fullName);

        if (count($parts) === 1) {
            return ['first_name' => $parts[0], 'last_name' => ''];
        }

        $lastName = array_pop($parts);

        return ['first_name' => implode(' ', $parts), 'last_name' => $lastName];
    }

    /**
     * Keep only digits and a leading plus so the stored number stays safe to dial.
     */
    public function normalizePhone(string $phone): string
    {
        $normalized = preg_replace('/(?!^)\+/', '', trim($phone));

        $normalized = preg_replace('/[^\d+]/', '', (string) $normalized);

        if (str_starts_with((string) $normalized, '00')) {
            $normalized = '+'.substr((string) $normalized, 2);
        }

        return (string) $normalized;
    }

    /**
     * Map the four checkout fields onto the address payload Bagisto expects.
     */
    public function toCartAddresses(array $data): array
    {
        $name = $this->splitName((string) ($data['name'] ?? ''));

        return [
            'billing' => [
                'first_name'       => $name['first_name'],
                'last_name'        => $name['last_name'],
                'email'            => (string) ($data['email'] ?? ''),
                'phone'            => $this->normalizePhone((string) ($data['phone'] ?? '')),
                'company_name'     => null,
                'address'          => [(string) ($data['address'] ?? '')],
                'city'             => '',
                'state'            => null,
                'postcode'         => null,
                'country'          => (string) ($data['country'] ?? 'BD'),
                'use_for_shipping' => 1,
                'default_address'  => 0,
            ],
        ];
    }
}
