<?php

namespace SKulich\LaravelUserTokenManagementCli\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\SanctumServiceProvider;
use Orchestra\Testbench\Attributes\WithEnv;
use Orchestra\Testbench\Attributes\WithMigration;
use SKulich\LaravelUserTokenManagementCli\Providers\PackageServiceProvider;

#[WithMigration]
#[WithEnv('DB_CONNECTION', 'testing')]
abstract class TestCaseWithSanctum extends TestCaseWithoutSanctum
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app()->bind('\App\Models\User', fn () => resolve('\Workbench\App\Models\UserWithSanctum'));
    }

    protected function getPackageProviders($app): array
    {
        return [
            PackageServiceProvider::class,
            SanctumServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(__DIR__.'/../vendor/laravel/sanctum/database/migrations');
    }
}
