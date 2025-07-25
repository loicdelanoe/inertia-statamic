<?php

namespace InertiaStatamic\InertiaStatamic\Commands;

use Illuminate\Console\Command;

class InertiaStatamicCommand extends Command
{
    public $signature = 'inertia-statamic';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
