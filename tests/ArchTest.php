<?php

arch()->preset()->php();
arch()->preset()->security();

arch()->expect(['die', 'dd', 'dump'])->not->toBeUsed();

arch()
    ->expect('SKulich')
    ->toUseStrictTypes()
    ->toUseStrictEquality();

arch()
    ->expect('SKulich')
    ->classes()
    ->toBeFinal();
