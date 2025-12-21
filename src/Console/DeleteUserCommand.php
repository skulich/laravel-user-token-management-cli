<?php

declare(strict_types=1);

namespace SKulich\LaravelUserTokenManagementCli\Console;

use Illuminate\Console\Command;
use SKulich\LaravelUserTokenManagementCli\Traits\UserCommandHelpers;

use function Laravel\Prompts\error;
use function Laravel\Prompts\info;

final class DeleteUserCommand extends Command
{
    use UserCommandHelpers;

    /**
     * @var string
     */
    protected $signature = 'user:delete';

    /**
     * @var string
     */
    protected $description = 'Delete a user';

    public function handle(): int
    {
        $model = $this->getUserModelClass();

        $id = $this->askUserId();

        $user = $model::find($id);

        if (! $user || ! $user->delete()) {
            error('User deletion has failed.');

            return self::FAILURE;
        }

        info('User has been deleted successfully.');

        return self::SUCCESS;
    }
}
