<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearCaches extends Command
{
    protected $signature = 'clear:caches';

    protected $description = 'Clear various caches';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Add your cache-clearing logic here
        $this->info('Caches cleared successfully.');
    }
}
