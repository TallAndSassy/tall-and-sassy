<?php

namespace LaravelFrontendPresets\Tall;

use Illuminate\Console\Command;
use InvalidArgumentException;

class TassyCommand extends Command
{

    protected $signature = 'tassy-install
                    { --round=* : A digit, start with "1", then "2", etc. }
                    ';

    protected $description = 'Install the Tall and Sassy framework';


    public function handle()
    {
        if ($this->option('round')) {
            $roundNum = $this->option('round')[0];
            return TassyPreset::install($roundNum, $this);
        } else {
             $this->info('No "--round=X" number set, so installing everything');
             return TassyPreset::install(null, $this);

        }

    }

}
