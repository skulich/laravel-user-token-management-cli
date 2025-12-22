<?php

use Illuminate\Support\Str;

beforeEach(function () {
    $model = $this->getUserModelClass();

    $user1 = new $model;
    $user1->name = 'Test User';
    $user1->email = 'test@example.com';
    $user1->password = 'password123';
    $user1->save();

    $user2 = new $model;
    $user2->name = 'Test User with really long name';
    $user2->email = 'test@example-0123456789-0123456789.com';
    $user2->password = 'password123';
    $user2->save();
});

it('handle user listing', function () {
    $model = $this->getUserModelClass();

    $return = [
        [
            'name' => str_pad('Test User', 24),
            'email' => str_pad('test@example.com', 24),
            'tokens' => str_pad('-', 9, ' ', STR_PAD_LEFT),
        ],
        [
            'name' => Str::limit('Test User with really long name', 21),
            'email' => Str::limit('test@example-0123456789-0123456789.com', 21),
            'tokens' => str_pad('-', 9, ' ', STR_PAD_LEFT),
        ],
    ];

    $this->artisan('user:list')
        ->expectsPromptsTable(['Name', 'Email', 'Tokens'], $return)
        ->assertSuccessful();
});

it('handle user listing failure case', function () {
    $model = $this->getUserModelClass();

    $model::find(1)->delete();
    $model::find(2)->delete();

    $this->artisan('user:list')
        ->expectsPromptsWarning('No users found.')
        ->assertFailed();
});
