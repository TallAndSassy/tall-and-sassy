<?php

namespace LaravelFrontendPresets\Tall;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Laravel\Ui\UiCommand;


class TallServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        UiCommand::macro('tassy', function (UiCommand $command) {
            TassyPreset::installInit();

            TassyPreset::install();

            $command->info('Tassy preset scaffolding installed successfully. Please run "composer update');

            TassyPreset::installAuth();

            $command->comment('Please run "npm install && npm run dev" to compile your new assets.');
        });

        Paginator::defaultView('pagination::default');

        Paginator::defaultSimpleView('pagination::simple-default');
    }
}
