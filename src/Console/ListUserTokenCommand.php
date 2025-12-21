<?php

declare(strict_types=1);

namespace SKulich\LaravelUserTokenManagementCli\Console;

use Illuminate\Console\Command;
use Laravel\Prompts\Concerns\Colors;
use Laravel\Prompts\Themes\Default\Concerns\DrawsBoxes;
use SKulich\LaravelUserTokenManagementCli\Traits\TokenCommandHelpers;
use SKulich\LaravelUserTokenManagementCli\Traits\UserCommandHelpers;

use function Laravel\Prompts\warning;

final class ListUserTokenCommand extends Command
{
    use Colors, DrawsBoxes, TokenCommandHelpers, UserCommandHelpers;

    /**
     * @var string
     */
    protected $signature = 'user:token:list';

    /**
     * @var string
     */
    protected $description = 'List user tokens';

    public function handle(): int
    {
        if (! $this->isUserModelTokenable()) {
            return self::FAILURE;
        }

        $user = $this->selectUser();
        if (is_null($user)) {
            return self::FAILURE;
        }

        if (! $user->tokens->count()) {
            warning('User has no tokens.');

            return self::FAILURE;
        }

        $this->printTokensTable($user);

        return self::SUCCESS;
    }
}
