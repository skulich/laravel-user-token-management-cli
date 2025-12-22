<?php

declare(strict_types=1);

namespace SKulich\LaravelUserTokenManagementCli\Console;

use Illuminate\Console\Command;
use Laravel\Prompts\Concerns\Colors;
use Laravel\Prompts\Themes\Default\Concerns\DrawsBoxes;
use SKulich\LaravelUserTokenManagementCli\Traits\TokenCommandHelpers;
use SKulich\LaravelUserTokenManagementCli\Traits\UserCommandHelpers;

use function Laravel\Prompts\warning;

final class ListUserCommand extends Command
{
    use Colors, DrawsBoxes, TokenCommandHelpers, UserCommandHelpers;

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
