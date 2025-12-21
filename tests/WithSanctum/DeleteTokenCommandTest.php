<?php

beforeEach(function () {
    $model = $this->getUserModelClass();

    $user = new $model;
    $user->name = 'Test User';
    $user->email = 'test@example.com';
    $user->password = 'password123';
    $user->save();
});

it('handle token deletion', function () {
    $model = $this->getUserModelClass();
    $user = $model::find(1);

    $this->artisan('user:token:delete')
        ->expectsSearch('Select User:', 0, $user->name, [$user->id => $user->name])
        ->expectsPromptsError('User selection failed.')
        ->assertFailed();

    $this->artisan('user:token:delete')
        ->expectsSearch('Select User:', $user->id, $user->name, [$user->id => $user->name])
        ->expectsPromptsWarning('User has no tokens.')
        ->assertFailed();

    $user->createToken('Test Token 1');
    $user->createToken('Test Token 2');
    $user->createToken('Test Token 3');
    $user->createToken('Test Token 4');

    $tokens = $user->tokens;
    $tokenList = $user->tokens->pluck('name', 'id')->toArray();
    $token1 = $tokens[0];
    $token2 = $tokens[1];
    $token3 = $tokens[2];
    $token4 = $tokens[3];

    $this->artisan('user:token:delete')
        ->expectsSearch('Select User:', $user->id, $user->name, [$user->id => $user->name])
        ->expectsChoice('Select Tokens:', [], $tokenList)
        ->expectsPromptsWarning('No tokens have been selected.')
        ->assertFailed();

    expect($user->tokens()->count())->toBe(4);

    $this->artisan('user:token:delete')
        ->expectsSearch('Select User:', $user->id, $user->name, [$user->id => $user->name])
        ->expectsChoice('Select Tokens:', [1, 3], $tokenList)
        ->expectsPromptsInfo('Tokens have been deleted successfully.')
        ->assertSuccessful();

    expect($user->tokens()->count())->toBe(2);

    $this->assertModelMissing($token1);
    $this->assertModelExists($token2);
    $this->assertModelMissing($token3);
    $this->assertModelExists($token4);
});
