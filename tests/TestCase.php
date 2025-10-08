<?php

declare(strict_types=1);

namespace Petrobolos\FixedArray\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Petrobolos\FixedArray\Providers\FixedArrayFunctionsServiceProvider;

class TestCase extends Orchestra
{
    /** @inheritDoc */
    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }

    /** @inheritDoc */
    protected function getPackageProviders($app): array
    {
        return [
            FixedArrayFunctionsServiceProvider::class,
        ];
    }
}
