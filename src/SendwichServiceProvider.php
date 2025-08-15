<?php

namespace Vblinden\Sendwich;

use Illuminate\Support\Facades\Mail;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SendwichServiceProvider extends PackageServiceProvider
{
    public function boot()
    {
        parent::boot();

        Mail::extend('sendwich', function () {
            return new SendwichTransport;
        });
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('sendwich')
            ->hasConfigFile();
    }
}
