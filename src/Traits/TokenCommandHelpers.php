<?php

declare(strict_types=1);

namespace SKulich\LaravelUserTokenManagementCli\Traits;

use Illuminate\Database\Eloquent\Model;

use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

trait TokenCommandHelpers
{
    /**
     * @return non-empty-string
     */
    private function askTokenName(): string
    {
        return text(
            label: 'Token Name:',
            placeholder: 'production',
            validate: ['name' => 'required|min:2|max:64'],
            hint: 'Minimum 2 characters.',
            transform: fn (string $value) => trim($value),
        );
    }

    /**
     * @return int[]
     */
    private function askTokenIds(Model $user): array
    {
        return multiselect(
            label: 'Select Tokens:',
            options: $user->tokens()->pluck('name', 'id'),
        );
    }

    private function printTokensTable(Model $user): void
    {
        table(
            ['Name', 'Abilities'],
            $user->tokens
                ->map(function ($token) {
                    return [
                        'name' => implode("\n", str_split(str_pad($token->name, 16), 16)),
                        'abilities' => implode("\n", str_split(str_pad(implode(', ', $token->abilities), 41), 41)),
                    ];
                })->toArray()
        );
    }
}
