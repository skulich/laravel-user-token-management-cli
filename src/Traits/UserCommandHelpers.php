<?php

declare(strict_types=1);

namespace SKulich\LaravelUserTokenManagementCli\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use RuntimeException;

use function Laravel\Prompts\error;
use function Laravel\Prompts\password;
use function Laravel\Prompts\search;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;
use function Laravel\Prompts\warning;

trait UserCommandHelpers
{
    /**
     * @return class-string<Model>
     */
    private function getUserModelClass(): string
    {
        $model = resolve('\App\Models\User');

        if (! $model instanceof Model) {
            throw new RuntimeException('User Model must be an instance of Illuminate\Database\Eloquent\Model.');
        }

        return $model::class;
    }

    private function isUserModelTokenable(): bool
    {
        if (! in_array('Laravel\Sanctum\HasApiTokens', class_uses_recursive($this->getUserModelClass()))) {
            warning('User Model is not tokenable.');

            return false;
        }

        return true;
    }

    /**
     * @return non-empty-string
     */
    private function askUserName(): string
    {
        return text(
            label: 'User Name:',
            placeholder: 'admin',
            validate: ['name' => 'required|min:2|max:255'],
            hint: 'Minimum 2 characters.',
            transform: fn (string $value) => trim($value),
        );
    }

    /**
     * @return non-empty-string
     */
    private function askUserEmail(): string
    {
        return text(
            label: 'User Email:',
            placeholder: 'admin@localhost',
            validate: ['email' => 'required|email|unique:'.$this->getUserModelClass()],
            hint: 'Valid email address.'
        );
    }

    /**
     * @return non-empty-string
     */
    private function askUserPassword(): string
    {
        return password(
            label: 'User Password:',
            placeholder: 'password',
            validate: ['password' => ['required', Password::defaults()]],
            hint: 'Minimum 8 characters.'
        );
    }

    /**
     * @return positive-int
     */
    private function askUserId(): int
    {
        $model = $this->getUserModelClass();

        return (int) search(
            label: 'Select User:',
            options: fn (string $value): array => $value !== ''
                ? $model::query()
                    ->whereLike('name', "%{$value}%")
                    ->orWhereLike('email', "%{$value}%")
                    ->pluck('name', 'id')
                    ->all()
                : [],
            placeholder: 'search by name or email'
        );
    }

    private function selectUser(): ?Model
    {
        $model = $this->getUserModelClass();

        $id = $this->askUserId();

        $user = $model::find($id);

        if (! $user) {
            error('User selection failed.');

            return null;
        }

        return $user;
    }

    private function printUsersTable(Collection $users): void
    {
        table(
            ['Name', 'Email', 'Tokens'],
            $users
                ->map(function ($token) {
                    return [
                        'name' => str_pad(Str::limit($token->name, 21), 24),
                        'email' => str_pad(Str::limit($token->email, 21), 24),
                        'tokens' => str_pad(Str::limit((string) $token->tokens?->count() ?: '-', 9, ''),
                            9, ' ', STR_PAD_LEFT),
                    ];
                })->toArray()
        );
    }
}
