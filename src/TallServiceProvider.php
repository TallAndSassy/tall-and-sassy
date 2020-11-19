<?php

namespace LaravelFrontendPresets\Tall;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;


class TallServiceProvider extends ServiceProvider
{

    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                TassyCommand::class,
            ]);
        }
    }

    //    public function boot()
    //    {
    //        TassyCommand::macro(
    //            'tassy',
    //            function ( $command) {
    //                if ($command->option('auth')) {
    //                    TassyPreset::install();
    //
    //
    //                    $command->comment('');
    //                    $command->info('Tassy preset scaffolding successfully initiated. Please run the following');
    //                    $command->comment('');
    //                    $command->comment('  php artisan cache:clear');
    //                    $command->comment('  composer update');
    //                    $command->comment('  php artisan migrate');
    //                    $command->comment(
    //                        '  php artisan vendor:publish --provider="TallAndSassy\AppThemeBase\AppThemeBaseServiceProvider" --tag="public"'
    //                    );
    //                    $command->comment(
    //                        '  php artisan vendor:publish --provider="TallAndSassy\AppBranding\AppBrandingServiceProvider" --tag="config"'
    //                    );
    //                    $command->comment('  npm install');
    //                    $command->comment('  npm run dev');
    //                    $command->comment('');
    //                    $command->comment('  --- Some optional commands---');
    //                    $command->comment('  (tbd)');
    //                }
    //            }
    //        );
    //
    //        Paginator::defaultView('pagination::default');
    //
    //        Paginator::defaultSimpleView('pagination::simple-default');
    //    }
}
