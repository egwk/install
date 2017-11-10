<?php

namespace Egwk\Install\Console\Commands;

use \Illuminate\Console\Command;
use Egwk\Install\Writings;

class Download extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'egwk:download:writings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downloads Ellen White writings, and dumps books into csv file.';

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
        //Setting up EGWWritings API
        $api      = new Writings\API();
        $request  = new Writings\API\Request($api);
        $iterator = new Writings\API\Iterator($request);

        //Defining target file
        $outputFile = storage_path('egwwritings.csv');

        //Setting up text processing
        $morphy = new Writings\Morphy(__DIR__ . '/../../../data/phpmorphy/en');
        $filter = new Writings\Filter($morphy);
        $export = new Writings\Export\CsvDump($filter, $outputFile);

        //Start download!
        (new Writings\Download($iterator, $export))->writings();
    }

}
