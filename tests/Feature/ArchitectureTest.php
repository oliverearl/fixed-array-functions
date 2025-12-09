<?php

declare(strict_types=1);

use Illuminate\Support\ServiceProvider;

arch()->preset()->php();
arch()->preset()->laravel();
arch()->preset()->security();

arch()
    ->expect('Petrobolos\FixedArray')
    ->toHaveMethodsDocumented()
    ->toHavePropertiesDocumented()
    ->toUseStrictEquality()
    ->toUseStrictTypes();

arch()
    ->expect('Petrobolos\FixedArray\Facades')
    ->toHaveMethod('getFacadeAccessor');

arch()
    ->expect('Petrobolos\FixedArray\Providers')
    ->toExtend(ServiceProvider::class)
    ->toHaveSuffix('ServiceProvider');

arch()
    ->expect('Petrobolos\FixedArray\Tests')
    ->toHaveMethodsDocumented()
    ->toHavePropertiesDocumented()
    ->toUseStrictEquality()
    ->toUseStrictTypes();
