<?php

declare(strict_types=1);

namespace Petrobolos\FixedArray\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Petrobolos\FixedArray\Providers\FixedArrayServiceProvider;

abstract class TestCase extends Orchestra
{
    /** @inheritDoc */
    protected function tearDown(): void
    {
        parent::tearDown();

        restore_exception_handler();
    }

    /** @inheritDoc */
    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }

    /** @inheritDoc */
    protected function getPackageProviders($app): array
    {
        return [
            FixedArrayServiceProvider::class,
        ];
    }
}
