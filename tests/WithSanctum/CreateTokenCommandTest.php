<?php

beforeEach(function () {
    $model = $this->getUserModelClass();

    $user = new $model;
    $user->name = 'Test User';
    $user->email = 'test@example.com';
    $user->password = 'password123';
    $user->save();
});

it('handle token creation', function () {
    $model = $this->getUserModelClass();
    $user = $model::find(1);

    $this->artisan('user:token:create')
        ->expectsSearch('Select User:', 0, $user->name, [$user->id => $user->name])
        ->expectsPromptsError('User selection failed.')
        ->assertFailed();

    $this->artisan('user:token:create')
        ->expectsSearch('Select User:', $user->id, $user->name, [$user->id => $user->name])
        ->expectsQuestion('Token Name:', 'Test Token')
        ->expectsPromptsInfo('Token has been created successfully.')
        ->assertSuccessful();
});
