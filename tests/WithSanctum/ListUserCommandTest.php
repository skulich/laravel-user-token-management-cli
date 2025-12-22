<?php

beforeEach(function () {
    $model = $this->getUserModelClass();

    $user = new $model;
    $user->name = 'Test User';
    $user->email = 'test@example.com';
    $user->password = 'password123';
    $user->save();

    $user->createToken('Token Name');
});

it('handle user listing', function () {
    $return = [
        [
            'name' => str_pad('Test User', 24),
            'email' => str_pad('test@example.com', 24),
            'tokens' => str_pad('1', 9, ' ', STR_PAD_LEFT),
        ],
    ];

    $this->artisan('user:list')
        ->expectsPromptsTable(['Name', 'Email', 'Tokens'], $return)
        ->assertSuccessful();
});
