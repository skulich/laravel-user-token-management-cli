<?php

beforeEach(function () {
    $model = $this->getUserModelClass();

    $user = new $model;
    $user->name = 'Test User';
    $user->email = 'test@example.com';
    $user->password = 'password123';
    $user->save();
});

it('handles user deletion', function () {
    $model = $this->getUserModelClass();

    $user = $model::find(1);

    $this->artisan('user:delete')
        ->expectsSearch('Select User:', $user->id, $user->name, [$user->id => $user->name])
        ->expectsPromptsInfo('User has been deleted successfully.')
        ->assertSuccessful();

    $this->assertModelMissing($user);
});

it('handles user deletion failure cases', function () {
    $model = $this->getUserModelClass();

    $user = $model::find(1);

    $model::deleting(fn () => false);

    $this->artisan('user:delete')
        ->expectsSearch('Select User:', 0, $user->name, [$user->id => $user->name])
        ->expectsPromptsError('User deletion has failed.')
        ->assertFailed();

    $this->assertModelExists($user);

    $this->artisan('user:delete')
        ->expectsSearch('Select User:', $user->id, $user->name, [$user->id => $user->name])
        ->expectsPromptsError('User deletion has failed.')
        ->assertFailed();

    $this->assertModelExists($user);
});
