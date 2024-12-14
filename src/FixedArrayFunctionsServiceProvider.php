<?php

declare(strict_types=1);

namespace Petrobolos\FixedArray;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FixedArrayFunctionsServiceProvider extends PackageServiceProvider
{
    /**
     * Configures the package for usage.
     */
    public function configurePackage(Package $package): void
    {
        $package->name('fixed-array-functions');
    }
}
