<?php

use Laravel\Sanctum\HasApiTokens;

it('check if user model is properly configured', function (): void {
    expect(HasApiTokens::class)
        ->not->toBeIn(class_uses_recursive($this->getUserModelClass()));
});

it('check if user:* commands exist', function (): void {
    $this->artisan('list user')
        ->expectsOutputToContain('user:create')
        ->expectsOutputToContain('user:delete')
        ->assertSuccessful();
});

it('handle *:* commands exception case', function (): void {
    app()->bind('\App\Models\User', fn (): stdClass => new stdClass);

    $this->artisan('user:create');
})->throws(RuntimeException::class,
    'User Model must be an instance of Illuminate\Database\Eloquent\Model.');
