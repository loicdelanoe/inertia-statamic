<?php

namespace InertiaStatamic\InertiaStatamic;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use InertiaStatamic\InertiaStatamic\Commands\InertiaStatamicCommand;

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
