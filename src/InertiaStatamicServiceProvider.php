<?php

namespace InertiaStatamic\InertiaStatamic;

use Illuminate\Contracts\Http\Kernel;
use Inertia\Inertia;
use InertiaStatamic\InertiaStatamic\Http\Middleware\InertiaStatamic;
use InertiaStatamic\InertiaStatamic\Support\SharedData;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class InertiaStatamicServiceProvider extends PackageServiceProvider
{
    public function bootingPackage()
    {
        $this->app[Kernel::class]->appendMiddlewareToGroup('web', InertiaStatamic::class);

        $this->app->booted(function () {
            Inertia::share(SharedData::all());
        });
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('inertia-statamic')
            ->hasConfigFile()
            ->hasRoute('web');
    }
}
