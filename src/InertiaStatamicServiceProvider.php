<?php

namespace InertiaStatamic\InertiaStatamic;

use InertiaStatamic\InertiaStatamic\Commands\InertiaStatamicCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class InertiaStatamicServiceProvider extends PackageServiceProvider
{
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
            ->hasViews()
            ->hasMigration('create_inertia_statamic_table')
            ->hasCommand(InertiaStatamicCommand::class);
    }
}
