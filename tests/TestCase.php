<?php

declare(strict_types=1);

namespace Petrobolos\FixedArray\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Petrobolos\FixedArray\Providers\FixedArrayServiceProvider;
use TypeError;

abstract class TestCase extends Orchestra
{
    /** @inheritDoc */
    protected function setUp(): void
    {
        try {
            parent::setUp();
        } catch (TypeError) {
            // Ignore PHPUnit handler error when no test instance exists
        }
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
