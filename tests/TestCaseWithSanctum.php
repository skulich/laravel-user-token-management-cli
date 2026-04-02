<?php

namespace SKulich\LaravelUserTokenManagementCli\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\SanctumServiceProvider;
use Orchestra\Testbench\Attributes\WithEnv;
use Orchestra\Testbench\Attributes\WithMigration;
use SKulich\LaravelUserTokenManagementCli\Providers\PackageServiceProvider;
use Workbench\App\Models\UserWithSanctum;

#[WithMigration]
#[WithEnv('DB_CONNECTION', 'testing')]
abstract class TestCaseWithSanctum extends TestCaseWithoutSanctum
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app()->bind('\App\Models\User', fn () => resolve(UserWithSanctum::class));
    }

    #[\Override]
    protected function getPackageProviders($app): array
    {
        return [
            PackageServiceProvider::class,
            SanctumServiceProvider::class,
        ];
    }

    #[\Override]
    protected function defineDatabaseMigrations(): void
    {
        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(__DIR__.'/../vendor/laravel/sanctum/database/migrations');
    }
}
