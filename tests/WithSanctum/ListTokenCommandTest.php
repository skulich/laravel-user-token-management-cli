<?php

beforeEach(function () {
    $model = $this->getUserModelClass();

    $user = new $model;
    $user->name = 'Test User';
    $user->email = 'test@example.com';
    $user->password = 'password123';
    $user->save();
});

it('handle token listing', function () {
    $model = $this->getUserModelClass();
    $user = $model::find(1);

    $user->createToken('Token Name');

    expect($user->tokens()->count())->toBe(1);

    $this->artisan('user:token:list')
        ->expectsSearch('Select User:', 0, $user->name, [$user->id => $user->name])
        ->expectsPromptsError('User selection failed.')
        ->assertFailed();

    $this->artisan('user:token:list')
        ->expectsSearch('Select User:', $user->id, $user->name, [$user->id => $user->name])
        ->expectsPromptsTable(
            ['Name', 'Abilities'],
            $user->tokens
                ->map(function ($token) {
                    return [
                        'name' => implode("\n", str_split(str_pad($token->name, 16), 16)),
                        'abilities' => implode("\n", str_split(str_pad(implode(', ', $token->abilities), 41), 41)),
                    ];
                })->toArray()
        )
        ->assertSuccessful();
});

it('handle token listing failure case', function () {
    $model = $this->getUserModelClass();

    $user = $model::find(1);

    $this->artisan('user:token:list')
        ->expectsSearch('Select User:', $user->id, $user->name, [$user->id => $user->name])
        ->expectsPromptsWarning('User has no tokens.')
        ->assertFailed();
});
