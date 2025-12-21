<?php

it('check if user model is properly configured', function () {
    expect('Laravel\Sanctum\HasApiTokens')
        ->not->toBeIn(class_uses_recursive($this->getUserModelClass()));
});

it('check if user:* commands exist', function () {
    $this->artisan('list user')
        ->expectsOutputToContain('user:create')
        ->expectsOutputToContain('user:delete')
        ->assertSuccessful();
});

it('handle *:* commands exception case', function () {
    app()->bind('\App\Models\User', fn () => new stdClass);

    $this->artisan('user:create');
})->throws(RuntimeException::class,
    'User Model must be an instance of Illuminate\Database\Eloquent\Model.');
