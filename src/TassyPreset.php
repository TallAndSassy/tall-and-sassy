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
        #'lodash',
        #'axios',
    ];


    public static function install(?int $roundNum = null, $command)
    {
        if (!$roundNum) {
            static::install_1($command);
            static::install_2($command);
            static::install_3($command);
            static::install_4($command);
        } else {
            $methodName = "install_{$roundNum}";
            static::$methodName($command);
        }
        $command->info('Tall & Sassy applied');
    }

    public static function install_1($command)
    {
        $command->info('-Tweaking node packages');
        static::updatePackages();
    }

    public static function install_2($command)
    {
        /* During development, we want use local paths to repositories, but that is difficult in package. Basically,
        apparently, if a package references local directories, it wont' find them nicely.
        So we'll put out requirements into a temporary composer called composer.pathReposWorkaround.json
        and merge the repositories listed there into the main composer.json
        We'll next merge the requirements listed there into the /package/composer.json

        This only seems necessary cuz we're doing these sym linked packages, instead of grabbing from packagist

        Hmm, what would be a better workflow, this seems stupid?
        */
        $command->info('- Bring in tassy related packages');
        $command->comment('  php artisan cache:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        static::mergeComposer();

        $command->comment('  composer update');
        jcmd('composer update', []);
        $command->comment('  php artisan migrate');
        \Illuminate\Support\Facades\Artisan::call('migrate');
    }


    public static function install_3($command)
    {
        // copy files
        $command->info('- Copying stub files...');
        $filesystem = new Filesystem();
        $filesystem->copyDirectory(__DIR__ . '/../stubs/default', base_path());

        // --- Nix jetstream auth junk
        $command->info('- Replace jetstream blade templates files...');
        // -- auth
        $key = 'auth';
        $appPathDoomed = "resources/views/{$key}";
        $appPathNew = "resources/views/-erasemesoonish.{$key}." . date("Y-m-d:H.s");
        $msg = '';
        if (static::renameDirectoryInPlace(
            $appPathDoomed,
            $appPathNew,
            false
        )) {
            $msg .= "- Soft-deleted {$key} (by renaming $appPathDoomed to $appPathNew)... ";
        }
        \Illuminate\Support\Facades\Artisan::call(
            'vendor:publish',
            [
                '--provider' => "TallAndSassy\AppThemeBase\AppThemeBaseServiceProvider",
                '--tag' => "views.{$key}"
            ]
        );
        $command->comment($msg . " and copied in new {$key} files.");


        // -- profile
        $key = 'profile';
        $appPathDoomed = "resources/views/{$key}";
        $appPathNew = "resources/views/-erasemesoonish.{$key}." . date("Y-m-d:H.s");
        $msg = '';
        if (static::renameDirectoryInPlace(
            $appPathDoomed,
            $appPathNew,
            false
        )) {
            $msg .= "- Soft-deleted {$key} (by renaming $appPathDoomed to $appPathNew)... ";
        }
        \Illuminate\Support\Facades\Artisan::call(
            'vendor:publish',
            [
                '--provider' => "TallAndSassy\AppThemeBase\AppThemeBaseServiceProvider",
                '--tag' => "views.{$key}"
            ]
        );
        $command->comment($msg . " and copied in new {$key} files.");


        // -- teams
        $key = 'teams';
        $appPathDoomed = "resources/views/{$key}";
        $appPathNew = "resources/views/-erasemesoonish.{$key}." . date("Y-m-d:H.s");
        $msg = '';
        if (static::renameDirectoryInPlace(
            $appPathDoomed,
            $appPathNew,
            false
        )) {
            $msg .= "- Soft-deleted {$key} (by renaming $appPathDoomed to $appPathNew)... ";
        }
        \Illuminate\Support\Facades\Artisan::call(
            'vendor:publish',
            [
                '--provider' => "TallAndSassy\AppThemeBase\AppThemeBaseServiceProvider",
                '--tag' => "views.{$key}"
            ]
        );
        $command->comment($msg . " and copied in new {$key} files.");


        // new home
        $command->info('- Make new home be /me instead of /dashboard...');
        static::updateFile(
            base_path('app/Providers/RouteServiceProvider.php'),
            function ($file) {
                return str_replace("HOME = '/dashboard';", "HOME = '/me';", $file);
            }
        );

        $command->info('-Put in sample logo, mainly');
        \Illuminate\Support\Facades\Artisan::call(
            'vendor:publish',
            [
                '--provider' => "TallAndSassy\AppThemeBase\AppThemeBaseServiceProvider",
                '--tag' => "public"
            ]
        );


        $command->info('-Set up config/AppBranding.php');
        \Illuminate\Support\Facades\Artisan::call(
            'vendor:publish',
            [
                '--provider' => "TallAndSassy\AppBranding\AppBrandingServiceProvider",
                '--tag' => "config"
            ]
        );



    }

    public static function install_4($command)
    {
         $command->info('- npm install');
        jcmd('npm install', []);
        $command->comment('  npm run dev');
        jcmd('npm run dev', []);
        $command->comment('  php artisan migrate');
        \Illuminate\Support\Facades\Artisan::call('migrate');
    }

    protected static function renameDirectoryInPlace(
        string $appPathDoomed,
        string $appPathNew,
        bool $dieIfDirNotThere
    ): bool {
        $oldPath = base_path($appPathDoomed);
        if (!$dieIfDirNotThere && !is_dir($oldPath)) {
            return false;
        }
        assert(is_dir($oldPath));
        $newPath = base_path($appPathNew);
        assert(!is_dir($newPath));
        rename($oldPath, $newPath);
        assert(is_dir($newPath));
        return true;
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

function jcmd($cmd, $asrFlags)
{
    exec($cmd, $output, $return);
    if (!empty($asrFlags['bForceEcho']) && $asrFlags['bForceEcho'] == true) {
        print_r($output);
    }

    if ($return != 0) {
        // an error occurred
        if (is_array($output)) {
            $output = var_export($output, true);
        }

        $s = "
Yikes: an error was generated when running:
$cmd

with error code: $return

and output: $output

";


        $c = new Colors();
        print $c->getColoredString($s, 'red');
        exit;
    }
}

class Colors
    { //http://www.if-not-true-then-false.com/2010/php-class-for-coloring-php-command-line-cli-scripts-output-php-output-colorizing-using-bash-shell-colors/
        private $foreground_colors = array();
        private $background_colors = array();

        public function __construct()
        {
            // Set up shell colors
            $this->foreground_colors['black'] = '0;30';
            $this->foreground_colors['dark_gray'] = '1;30';
            $this->foreground_colors['blue'] = '0;34';
            $this->foreground_colors['light_blue'] = '1;34';
            $this->foreground_colors['green'] = '0;32';
            $this->foreground_colors['light_green'] = '1;32';
            $this->foreground_colors['cyan'] = '0;36';
            $this->foreground_colors['light_cyan'] = '1;36';
            $this->foreground_colors['red'] = '0;31';
            $this->foreground_colors['light_red'] = '1;31';
            $this->foreground_colors['purple'] = '0;35';
            $this->foreground_colors['light_purple'] = '1;35';
            $this->foreground_colors['brown'] = '0;33';
            $this->foreground_colors['yellow'] = '1;33';
            $this->foreground_colors['light_gray'] = '0;37';
            $this->foreground_colors['white'] = '1;37';

            $this->background_colors['black'] = '40';
            $this->background_colors['red'] = '41';
            $this->background_colors['green'] = '42';
            $this->background_colors['yellow'] = '43';
            $this->background_colors['blue'] = '44';
            $this->background_colors['magenta'] = '45';
            $this->background_colors['cyan'] = '46';
            $this->background_colors['light_gray'] = '47';
        }

        // Returns colored string
        public function getColoredString($string, $foreground_color = null, $background_color = null)
        {
            $colored_string = "";

            // Check if given foreground color found
            if (isset($this->foreground_colors[$foreground_color])) {
                $colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
            }
            // Check if given background color found
            if (isset($this->background_colors[$background_color])) {
                $colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
            }

            // Add string and end coloring
            $colored_string .= $string . "\033[0m";

            return $colored_string;
        }

        // Returns all foreground color names
        public function getForegroundColors()
        {
            return array_keys($this->foreground_colors);
        }

        // Returns all background color names
        public function getBackgroundColors()
        {
            return array_keys($this->background_colors);
        }
    }
