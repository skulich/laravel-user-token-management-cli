<?php

declare(strict_types=1);

namespace SKulich\LaravelUserTokenManagementCli\Console;

use Illuminate\Console\Command;
use Laravel\Prompts\Concerns\Colors;
use Laravel\Prompts\Themes\Default\Concerns\DrawsBoxes;
use SKulich\LaravelUserTokenManagementCli\Traits\TokenCommandHelpers;
use SKulich\LaravelUserTokenManagementCli\Traits\UserCommandHelpers;

use function Laravel\Prompts\info;
use function Laravel\Prompts\warning;

final class DeleteUserTokenCommand extends Command
{
    use Colors, DrawsBoxes, TokenCommandHelpers, UserCommandHelpers;

    /**
     * @var string
     */
    protected $signature = 'user:token:delete';

    /**
     * @var string
     */
    protected $description = 'Delete tokens for the user';

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

        $ids = $this->askTokenIds($user);

        if ($ids === []) {
            warning('No tokens have been selected.');

            return self::FAILURE;
        }

        $user->tokens()->whereIn('id', $ids)->delete();

        info('Tokens have been deleted successfully.');

        $this->printTokensTable($user);

        return self::SUCCESS;
    }
}
