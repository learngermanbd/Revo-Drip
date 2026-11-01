<?php

namespace Webkul\RevoDrip\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BangladeshPhoneNumber implements ValidationRule
{
    /**
     * Validate the attribute.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->passes($value)) {
            $fail('revodrip::app.checkout.validation.phone')->translate();
        }
    }

    /**
     * Whether the value is a dialable Bangladesh mobile number.
     */
    public function passes(mixed $value): bool
    {
        $number = $this->normalize((string) $value);

        return (bool) preg_match('/^(?:\+?880|0)1[3-9]\d{8}$/', $number);
    }

    /**
     * Strip formatting so local and international formats both validate.
     */
    public function normalize(string $value): string
    {
        return (string) preg_replace('/[\s\-().]/', '', trim($value));
    }
}
