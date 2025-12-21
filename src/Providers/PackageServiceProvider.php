<?php

declare(strict_types=1);

namespace SKulich\LaravelUserTokenManagementCli\Providers;

use Illuminate\Support\ServiceProvider;
use SKulich\LaravelUserTokenManagementCli\Console\CreateUserCommand;
use SKulich\LaravelUserTokenManagementCli\Console\CreateUserTokenCommand;
use SKulich\LaravelUserTokenManagementCli\Console\DeleteUserCommand;
use SKulich\LaravelUserTokenManagementCli\Console\DeleteUserTokenCommand;
use SKulich\LaravelUserTokenManagementCli\Console\ListUserTokenCommand;

final class PackageServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateUserCommand::class,
                DeleteUserCommand::class,
            ]);

            if (class_exists('\Laravel\Sanctum\SanctumServiceProvider')) {
                $this->commands([
                    CreateUserTokenCommand::class,
                    DeleteUserTokenCommand::class,
                    ListUserTokenCommand::class,
                ]);
            }
        }
    }

    public function register(): void
    {
        //
    }
}
