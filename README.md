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




Init the environment (DB)
```bash
[ ] make a db for this
[ ] update .env to match your db

-- optional - inspect now to see how it's going
    php artisan migrate
    ( ) Try visiting the site in the local browser - it should basically work as standard laravel app
```

Install Installaller (Locally or from packagist - pick a method)
---
.1) Install From Packagist 
```bash
composer require laravel-frontend-presets/tall-and-sassy-preset #wip
``` 

.2) Install from local development directory
```bash
#up one directory, not in the laravel app directory 
git clone https://github.com/TallAndSassy/tall-and-sassy-preset
```
edit 'teamsy2/composer.json' (this is only until I learn to develop packages more gracefully)
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
```bash 
composer update --working-dir=teamsy2
```
Run the installer
---

```bash
cd teamsy2
php artisan tassy-install   
```

Some notable features of this package include:
- Uses Tall stack
- Framework for making simply organization-focused SaaS apps
- Uses jetstream, but overrides a bunch of UI stuff with the goal of more reuse 

All routes, components, controllers and tests are published to your application. The idea behind this is that you have full control over every aspect of the scaffolding in your own app, removing the need to dig around in the vendor folder to figure out how things are working.



## Credits
- Initially forked from the wonderful tall preset (https://github.com/laravel-frontend-presets/tall)
