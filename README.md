# Laravel TALL Preset

[![CI Status](https://github.com/laravel-frontend-presets/tall/workflows/Run%20Tests/badge.svg)](https://github.com/laravel-frontend-presets/tall/actions)
[![Total Downloads](https://poser.pugx.org/laravel-frontend-presets/tall/d/total.svg)](https://packagist.org/packages/laravel-frontend-presets/tall)

A front-end preset for Tall and Sassy installation

Install simple Laravel with jetstream and livewire, but no teams
```bash
laravel new teamsy2 --jet

# - Pick Livewire
# - Say 'no' to teams
```


go in and init the laravel install
```bash
cd teamsy2
```

Init the DB
```bash
[ ] make a db for this
[ ] update .env to match your db
php artisan migrate
( ) Try visiting the site in the local browser - it should basically work as standard laravel app
```

Install and init this preset
---

edit 'composer.json' (this is only until I learn to develop packages more gracefully)
```json
"require": {
 ...
 "laravel-frontend-presets/tall-and-sassy-preset" : "master-dev"

...
"repositories": [
        {
            "type": "path",
            "url": "../tall-and-sassy-preset"
        }
	]
```

Install the repos
```bash
cd .. #up one directory 
git clone https://github.com/TallAndSassy/tall-and-sassy
cd teamsy #go back
composer update
php artisan ui tassy 
composer update     #per instructions
npm install         #per instructions
npm run dev         #per instructions
```

#wip
repository paths don't seem to be honored for packages.  So we sync it to the main composer.

Some notable features of this package include:
- Uses Tall stack
- Framework for making simply organization-focused SaaS apps
- Uses jetstream, but overrides a bunch of UI stuff with the goal of more reuse 

All routes, components, controllers and tests are published to your application. The idea behind this is that you have full control over every aspect of the scaffolding in your own app, removing the need to dig around in the vendor folder to figure out how things are working.

## CSS purging

Tailwind uses PurgeCSS to remove any unused classes from your production CSS builds. You can modify or remove this behaviour in the `purge` section of your `tailwind.config.js` file. For more information, please see the [Tailwind documentation](https://tailwindcss.com/docs/controlling-file-size/).

## Removing the package

If you don't want to keep this package installed once you've installed the preset, you can safely remove it. Unlike the default Laravel presets, this one publishes all the auth logic to your project's `/app` directory, so it's fully redundant.


### A note on pagination

If you are using pagination, you set the default pagination views to the ones provided in the `boot` method of a service provider:

```php
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Paginator::defaultView('pagination::default');

        Paginator::defaultSimpleView('pagination::simple-default');
    }
}
```

## Credits
- Initially forked from the wonderful tall preset (https://github.com/laravel-frontend-presets/tall)
