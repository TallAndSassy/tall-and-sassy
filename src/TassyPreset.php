<?php

namespace LaravelFrontendPresets\Tall;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Laravel\Ui\Presets\Preset;

class TassyPreset extends Preset
{
    const NPM_PACKAGES_TO_ADD = [
        '@tailwindcss/ui' => '^0.4',
        '@tailwindcss/typography' => '^0.2',
        'alpinejs' => '^2.6',
        'laravel-mix-tailwind' => '^0.1.0',
        'tailwindcss' => '^1.5',
    ];

    const NPM_PACKAGES_TO_REMOVE = [
        'lodash',
        'axios',
    ];


    public static function installInit()
    {
        /* During development, we want use local paths to repositories, but that is difficult in package. Basically,
        apparently, if a package references local directories, it wont' find them nicely.
        So we'll put out requirements into a temporary composer called composer.pathReposWorkaround.json
        and merge the repositories listed there into the main composer.json
        We'll next merge the requirements listed there into the /package/composer.json

        This only seems necessary cuz we're doing these sym linked packages, instead of grabbing from packagist

        Hmm, what would be a better workflow, this seems stupid?
        */
        static::mergeComposer();
    }

    public static function install()
    {
        // NiceToDo: Ensure Inited
        static::updatePackages();

        $filesystem = new Filesystem();
        $filesystem->copyDirectory(__DIR__ . '/../stubs/default', base_path());

        static::updateFile(
            base_path('app/Providers/RouteServiceProvider.php'),
            function ($file) {
                return str_replace("public const HOME = '/home';", "public const HOME = '/me';", $file);
            }
        );

        static::updateFile(
            base_path('app/Http/Middleware/RedirectIfAuthenticated.php'),
            function ($file) {
                return str_replace("RouteServiceProvider::HOME", "route('home')", $file);
            }
        );

        
    }

    public static function installAuth()
    {
        $filesystem = new Filesystem();

        $filesystem->copyDirectory(__DIR__ . '/../stubs/auth', base_path());
    }

    protected static function updatePackageArray(array $packages)
    {
        return array_merge(
            static::NPM_PACKAGES_TO_ADD,
            Arr::except($packages, static::NPM_PACKAGES_TO_REMOVE)
        );
    }

    /**
     * Update the contents of a file with the logic of a given callback.
     */
    protected static function updateFile(string $path, callable $callback)
    {
        $originalFileContents = file_get_contents($path);
        $newFileContents = $callback($originalFileContents);
        file_put_contents($path, $newFileContents);
    }


    protected static function mergeComposer()
    {
        $path_app_main_composer = base_path('composer.json');
        $path_package_main_composer = __DIR__ . '/../composer.json';
        $path_package_post_composer = __DIR__ . '/../composer.pathReposWorkaround.json';
        static::mergeFromJsonFilePath(
            $path_package_post_composer,
            $path_app_main_composer,
            function ($asr_from, $asr_to) {
                $asr_to['repositories'] = $asr_to['repositories'] ?? [];

                $asr_to['repositories'] = array_merge_recursive_distinct(
                    $asr_from['repositories'],
                    $asr_to['repositories']
                );
                return $asr_to;
            }
        );


//        // We _should_ be mergining into ./composer.json, but composer update isn't seeing the changes..
//        // ugh. Let's hack update /composer.json as a workaround. Again, this goes away if we had a better dev workflow
         static::mergeFromJsonFilePath(
            $path_package_post_composer,
            $path_package_main_composer,
            function ($asr_from, $asr_to) {
                $asr_to['require'] = $asr_to['require'] ?? [];
                $asr_to['require'] = array_merge_recursive_distinct(
                    $asr_from['require'],
                    $asr_to['require']
                );
                return $asr_to;
            }
        );
//          static::mergeFromJsonFilePath(
//            $path_package_post_composer,
//            $path_package_main_composer,
//            function ($asr_from, $asr_to) {
//                $asr_to['require-dev'] = $asr_to['require-dev'] ?? [];
//                $asr_to['require-dev'] = array_merge_recursive_distinct(
//                    $asr_from['require-dev'],
//                    $asr_to['require-dev']
//                );
//                return $asr_to;
//            }
//        );

    }

    protected static function mergeFromJsonFilePath(string $fromPath, string $toPath, $callback)
    {
        $json_from = file_get_contents($fromPath);
        $asr_from = json_decode($json_from, true);


        $json_to = file_get_contents($toPath);
        $asr_to = json_decode($json_to, true);

        $asr_to = $callback($asr_from, $asr_to);
        assert($asr_to);

        $json_to = json_encode($asr_to, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        file_put_contents($toPath, $json_to);
    }
}
