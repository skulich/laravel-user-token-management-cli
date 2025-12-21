<?php

declare(strict_types=1);

namespace SKulich\LaravelUserTokenManagementCli\Console;

use Illuminate\Console\Command;
use SKulich\LaravelUserTokenManagementCli\Traits\UserCommandHelpers;

use function Laravel\Prompts\error;
use function Laravel\Prompts\info;

final class CreateUserCommand extends Command
{
    use UserCommandHelpers;

    /**
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * @var string
     */
    protected $description = 'Create a new user';

    public function handle(): int
    {
        $model = $this->getUserModelClass();

        $name = $this->askUserName();
        $email = $this->askUserEmail();
        $password = $this->askUserPassword();

        $user = new $model;
        $user->name = $name;
        $user->email = $email;
        $user->password = $password;

        if (! $user->save()) {
            error('User creation has failed.');

            return self::FAILURE;
        }

        info('User has been created successfully.');

        return self::SUCCESS;
    }
}
