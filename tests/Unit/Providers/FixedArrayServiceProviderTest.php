<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Petrobolos\FixedArray\Providers\FixedArrayServiceProvider;
use Spatie\LaravelPackageTools\Package;

it('can configure the package', function (): void {
    /** @var Application $app */
    $app = mock(Application::class);

    $provider = new FixedArrayServiceProvider($app);
    expect($provider)->toBeInstanceOf(FixedArrayServiceProvider::class);

    $package = new Package();
    $provider->configurePackage($package);

    expect($package->name)->toEqual('fixed-array-functions');
});
