<?php

use Laravel\Sanctum\HasApiTokens;
use Workbench\App\Models\UserWithoutSanctum;

it('check if user model is properly configured', function (): void {
    expect(HasApiTokens::class)
        ->toBeIn(class_uses_recursive($this->getUserModelClass()));
});

it('check if token:* commands exist', function (): void {
    $this->artisan('list user:token')
        ->expectsOutputToContain('user:token:create')
        ->expectsOutputToContain('user:token:delete')
        ->expectsOutputToContain('user:token:list')
        ->assertSuccessful();
});

it('handle token commands failure case', function (): void {
    app()->bind('\App\Models\User', fn () => resolve(UserWithoutSanctum::class));

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
