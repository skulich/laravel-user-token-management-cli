<?php

use SKulich\LaravelUserTokenManagementCli\Tests\TestCaseWithoutSanctum;
use SKulich\LaravelUserTokenManagementCli\Tests\TestCaseWithSanctum;

uses(TestCaseWithoutSanctum::class)->in(__DIR__.'/WithoutSanctum');
uses(TestCaseWithSanctum::class)->in(__DIR__.'/WithSanctum');
