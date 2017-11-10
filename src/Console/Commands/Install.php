<?php

namespace Egwk\Install\Console\Commands;

use \Illuminate\Console\Command;
use \Egwk\Install\Writings\Downloader;

class Install extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'egwk:install:writings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs EGWK database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
    }

}
