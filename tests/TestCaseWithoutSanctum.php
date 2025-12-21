<?php

namespace SKulich\LaravelUserTokenManagementCli\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Attributes\WithEnv;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\TestCase as BaseTestCase;
use SKulich\LaravelUserTokenManagementCli\Providers\PackageServiceProvider;

#[WithMigration]
#[WithEnv('DB_CONNECTION', 'testing')]
abstract class TestCaseWithoutSanctum extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * @return class-string<Model>
     */
    protected function getUserModelClass(): string
    {
        return app('\App\Models\User')::class;
    }

    protected function setUp(): void
    {
        parent::setUp();

        app()->bind('\App\Models\User', fn () => resolve('\Workbench\App\Models\UserWithoutSanctum'));
    }

    protected function getPackageProviders($app): array
    {
        return [
            PackageServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadLaravelMigrations();
    }
}
