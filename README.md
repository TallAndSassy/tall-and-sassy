# Tall & Sassy: JJ's Laravel TALL Preset

[![CI Status](https://github.com/laravel-frontend-presets/tall-and-sassy/workflows/Run%20Tests/badge.svg)](https://github.com/laravel-frontend-presets/tall/actions)
[![Total Downloads](https://poser.pugx.org/laravel-frontend-presets/tall-and-sassy/d/total.svg)](https://packagist.org/packages/laravel-frontend-presets/tall)

A front-end preset for Tall & Sassy installation. Tall & Sassy is a preset for 
creating an opinionated multi-tenenat SaaS with a dashboard, Hotwire pages, and ajax models.

2021/04 - In the middle of a big change to work with TallAndSassy remerging into
single module/package
(This is a work-in-progress. It will break)

Install Base Laravel w/ Jetstream
---

Install simple Laravel with jetstream and livewire, but no teams
```bash
laravel new teamsy2 --jet

# - Pick Livewire
# - Say 'yes' to teams
```




Init the environment (DB)
```bash
cd teamsy2
[ ] make a db for this, called 'teamsy2' (or whatever you picked above)
[ ] update .env to match your db

-- optional (but do it eventually) - inspect now to see how it's going
  In another Tab
    [ ] npm install
    [ ] npm run watch
  In another Tab
    [ ] php artisan migrate
  In one tab:
    [ ] php artisan serve
    ( ) Try visiting the site in the local browser - it should basically work as standard laravel app
```

Install this preset (Choose 'Packagist' vs. 'Local Dev')
---
Option 1) Install From Packagist (recommended - wip)
---
```bash
composer require laravel-frontend-presets/tall-and-sassy-preset #wip
``` 

Option 2) Install a local copy <br> (for development on this preset package itself)
---
```bash
#up one directory, probably, not in the laravel app directory 
git clone https://github.com/TallAndSassy/tall-and-sassy-preset
```

```bash
#now you have the preset, tell laravel to start using it
 composer config repositories.laravel-frontend-presets/tall-and-sassy-preset path '../tall-and-sassy-preset' 
 composer require 'laravel-frontend-presets/tall-and-sassy-preset:master-dev'
 # Q: I'm seeing lots of junk about tallandsassy not found
 # A: Are you installing for a reused copy of this preset? If so, you need to clean up our composer.json file take out the merged dependancies...(See below)
```
Run the installer
---

```bash
# Tell the installer to muck around. This totally changes existing files <!>
php artisan tassy-install   
```

Extra Manual Steps
---
```bash
# I'm sure there is a better way to override the jetstream pages...
cp -r 'vendor/tallandsassy/app-theme-base/resources/views/' 'resources/views/'
cp -r 'vendor/tallandsassy/app-theme-base/resources/public/' 'public/'
 
```
WIP/DEV
-------
```bash
cp -r ../teamsy10/app/http/livewire/ app/http/livewire/
cp -r ../teamsy10/resources/views/livewire/  resources/views/livewire/ 
```


Next Steps
---
```
[ ] Create a use/pass and visit the admin page-->Dev->Grok->Main
    Look at items to see how to further configure your experience
``` 

Cleanup Artifacts (when you're emotionally ready)
---
```bash
# <!> careful
rm -rf 'resources/views/-erasemesoonish.*'
```

<!> Runnig the installer a second time
---
```json
# You had modified composer.json - undo that!
  # Removed all the 'tallandsassy' &  "eleganttechnologies" stuff
  # (Anything merged in from 'composer.pathReposWorkaround.json')
    # Also, do not commit the updated 'composer.json' to git.
"tallandsassy/app-branding": "master-dev",
"tallandsassy/page-guide-admin": "master-dev",
"tallandsassy/grok-livewire-jet": "master-dev",
"tallandsassy/team-means-family": "master-dev",
"tallandsassy/app-theme-base-ui-glances": "master-dev",
"eleganttechnologies/grok": "master-dev",
"tallandsassy/grok-jet-ui": "master-dev",
"tallandsassy/livewire-friends": "master-dev",
"tallandsassy/plugin-grok": "master-dev"   
```
Some notable side-effects
---
- <!> The <code>'composer.json'</code> file right here, in this package, gets modified.
  <br>(at least until development reachs stage where sub-packages are on packagist)
- efficerate jetstream auth 
- nix /dashboard route
- ...

Some notable features of this package include:
---
- Uses Tall stack
- Framework for making simply organization-focused SaaS apps
- Uses jetstream, but overrides a bunch of UI stuff with the goal of more reuse 

All routes, components, controllers and tests are published to your application. The idea behind this is that you have full control over every aspect of the scaffolding in your own app, removing the need to dig around in the vendor folder to figure out how things are working.



## Credits
- Initially forked from the wonderful tall preset (https://github.com/laravel-frontend-presets/tall)


## Roadmap
- Copy app/Http/Livewire/*
- Copy resources/livewire/the-modal-box.blade.php
