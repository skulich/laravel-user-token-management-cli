<?php

arch()->preset()->php();
arch()->preset()->security();

arch()->expect(['die', 'dd', 'dump'])->not->toBeUsed();

arch()
    ->expect('SKulich\LaravelUserTokenManagementCli')
    ->toUseStrictTypes()
    ->toUseStrictEquality();

arch()
    ->expect('SKulich\LaravelUserTokenManagementCli')
    ->classes()
    ->toBeFinal();
