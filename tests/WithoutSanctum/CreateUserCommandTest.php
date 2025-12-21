<?php

it('handles user creation', function () {
    $model = $this->getUserModelClass();

    $this->artisan('user:create')
        ->expectsQuestion('User Name:', 'T') // Bad
        ->assertFailed();

    expect($model::count())->toBe(0);

    $this->artisan('user:create')
        ->expectsQuestion('User Name:', 'Test User') // OK
        ->expectsQuestion('User Email:', 'invalid-email') // Bad
        ->assertFailed();

    expect($model::count())->toBe(0);

    $this->artisan('user:create')
        ->expectsQuestion('User Name:', 'Test User') // OK
        ->expectsQuestion('User Email:', 'test@example') // OK
        ->expectsQuestion('User Password:', 'short') // Bad
        ->assertFailed();

    expect($model::count())->toBe(0);

    $this->artisan('user:create')
        ->expectsQuestion('User Name:', 'Test User') // Ok
        ->expectsQuestion('User Email:', 'test@example.com') // Ok
        ->expectsQuestion('User Password:', 'password123') // Ok
        ->expectsPromptsInfo('User has been created successfully.')
        ->assertSuccessful();

    expect($model::count())->toBe(1);

    $user = $model::first();
    expect($user->name)->toBe('Test User')
        ->and($user->email)->toBe('test@example.com');
});

it('handles user creation failure cases', function () {
    $model = $this->getUserModelClass();

    $model::saving(fn () => false);

    $this->artisan('user:create')
        ->expectsQuestion('User Name:', 'Test User') // Ok
        ->expectsQuestion('User Email:', 'test@example.com') // Ok
        ->expectsQuestion('User Password:', 'password123') // Ok
        ->expectsPromptsError('User creation has failed.')
        ->assertFailed();

    expect($model::count())->toBe(0);
});
