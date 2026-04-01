<?php

declare(strict_types=1);

namespace SKulich\LaravelUserTokenManagementCli\Console;

use Illuminate\Console\Command;
use SKulich\LaravelUserTokenManagementCli\Traits\UserCommandHelpers;

use function Laravel\Prompts\warning;

final class ListUserCommand extends Command
{
    use UserCommandHelpers;

    /**
     * @var string
     */
    protected $signature = 'user:list';

    /**
     * @var string
     */
    protected $description = 'List users';

    public function handle(): int
    {
        $model = $this->getUserModelClass();

        $collection = $model::all();

        if (! $collection->count()) {
            warning('No users found.');

            return self::FAILURE;
        }

        $this->printUsersTable($collection);

        return self::SUCCESS;
    }
}
