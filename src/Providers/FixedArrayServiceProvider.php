<?php

declare(strict_types=1);

namespace Petrobolos\FixedArray\Providers;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FixedArrayServiceProvider extends PackageServiceProvider
{
    /**
     * Configures the package for usage.
     */
    public function configurePackage(Package $package): void
    {
        $package->name('fixed-array-functions');
    }
}
