<?php

declare(strict_types=1);

namespace SKulich\LaravelUserTokenManagementCli\Console;

use Illuminate\Console\Command;
use Laravel\Prompts\Concerns\Colors;
use Laravel\Prompts\Themes\Default\Concerns\DrawsBoxes;
use Laravel\Sanctum\NewAccessToken;
use SKulich\LaravelUserTokenManagementCli\Traits\TokenCommandHelpers;
use SKulich\LaravelUserTokenManagementCli\Traits\UserCommandHelpers;

use function Laravel\Prompts\info;

final class CreateUserTokenCommand extends Command
{
    use Colors, DrawsBoxes, TokenCommandHelpers, UserCommandHelpers;

    /**
     * @var string
     */
    protected $signature = 'user:token:create';

    /**
     * @var string
     */
    protected $description = 'Create a new token for the user';

    public function handle(): int
    {
        if (! $this->isUserModelTokenable()) {
            return self::FAILURE;
        }

        $user = $this->selectUser();
        if (is_null($user)) {
            return self::FAILURE;
        }

        $name = $this->askTokenName();

        /** @var NewAccessToken $token */
        $token = $user->createToken($name);
        $abilities = $token->accessToken->abilities;

        info('Token has been created successfully.');

        $this->box(
            title: 'New Token',
            body: $token->plainTextToken,
            color: 'green',
            info: $name,
        );

        $this->box(
            title: 'Its Abilities',
            body: implode(', ', $abilities),
        );

        return self::SUCCESS;
    }
}
