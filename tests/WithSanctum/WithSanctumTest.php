<?php

it('check if user model is properly configured', function () {
    expect('Laravel\Sanctum\HasApiTokens')
        ->toBeIn(class_uses_recursive($this->getUserModelClass()));
});

it('check if token:* commands exist', function () {
    $this->artisan('list user:token')
        ->expectsOutputToContain('user:token:create')
        ->expectsOutputToContain('user:token:delete')
        ->expectsOutputToContain('user:token:list')
        ->assertSuccessful();
});

it('handle token commands failure case', function () {
    app()->bind('\App\Models\User', fn () => resolve('\Workbench\App\Models\UserWithoutSanctum'));

    $this->artisan('user:token:create')
        ->expectsPromptsWarning('User Model is not tokenable.')
        ->assertFailed();

    $this->artisan('user:token:delete')
        ->expectsPromptsWarning('User Model is not tokenable.')
        ->assertFailed();

    $this->artisan('user:token:list')
        ->expectsPromptsWarning('User Model is not tokenable.')
        ->assertFailed();
});
