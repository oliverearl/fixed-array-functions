<?php

declare(strict_types=1);

namespace Petrobolos\FixedArray\Tests;

use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase as Orchestra;
use Petrobolos\FixedArray\Providers\FixedArrayServiceProvider;

abstract class TestCase extends Orchestra
{
    /** @inheritDoc */
    public function getEnvironmentSetUp($app): void
    {
        Config::set('database.default', 'testing');
    }

    /** @inheritDoc */
    protected function getPackageProviders($app): array
    {
        return [
            FixedArrayServiceProvider::class,
        ];
    }
}
