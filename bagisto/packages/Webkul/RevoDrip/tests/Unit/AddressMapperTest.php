<?php

use Webkul\RevoDrip\Rules\BangladeshPhoneNumber;
use Webkul\RevoDrip\Services\AddressMapper;

test('a full name is split into first and last name', function () {
    $mapper = new AddressMapper;

    expect($mapper->splitName('Md. Rakib Hasan'))->toMatchArray([
        'first_name' => 'Md. Rakib',
        'last_name'  => 'Hasan',
    ]);
});

test('a single word name keeps the last name empty', function () {
    $mapper = new AddressMapper;

    expect($mapper->splitName('Rakib'))->toMatchArray([
        'first_name' => 'Rakib',
        'last_name'  => '',
    ]);
});

test('extra whitespace is collapsed before splitting', function () {
    $mapper = new AddressMapper;

    expect($mapper->splitName("  Nur   Jahan  "))->toMatchArray([
        'first_name' => 'Nur',
        'last_name'  => 'Jahan',
    ]);
});

test('the four fields map onto the bagisto billing address', function () {
    $mapper = new AddressMapper;

    $payload = $mapper->toCartAddresses([
        'name'    => 'Md. Rakib Hasan',
        'phone'   => '+880 1712-345678',
        'email'   => 'rakib@example.com',
        'address' => "House 12, Road 4\nMirpur, Dhaka",
    ]);

    expect($payload['billing'])->toMatchArray([
        'first_name'       => 'Md. Rakib',
        'last_name'        => 'Hasan',
        'email'            => 'rakib@example.com',
        'phone'            => '+8801712345678',
        'city'             => '',
        'state'            => null,
        'postcode'         => null,
        'country'          => 'BD',
        'use_for_shipping' => 1,
    ]);

    expect($payload['billing']['address'])->toBe(["House 12, Road 4\nMirpur, Dhaka"]);
});

test('valid bangladeshi numbers pass the rule', function (string $number) {
    expect((new BangladeshPhoneNumber)->passes($number))->toBeTrue();
})->with([
    '01712345678',
    '01712-345678',
    '01712 345678',
    '+8801712345678',
    '8801712345678',
    '+880 1712 345678',
]);

test('invalid numbers fail the rule', function (string $number) {
    expect((new BangladeshPhoneNumber)->passes($number))->toBeFalse();
})->with([
    '0171234567',
    '01212345678',
    '12345',
    'hello',
    '',
    '+447700900123',
]);
