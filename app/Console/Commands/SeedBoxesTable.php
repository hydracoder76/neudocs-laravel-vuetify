<?php

namespace NeubusSrm\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

/**
 * Class SeedBoxesTable
 * @package NeubusSrm\Console\Commands
 */
class SeedBoxesTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'neusrm:seed-boxes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make unique boxes';

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
	    $this->info('Generating boxes');
	    `ulimit -n 10000`;
	    $numToCreate = intval(ceil(intval($this->ask('How many boxes do you need?'))/1000));
	    $start = Carbon::now();
	    for ($i = 0; $i < $numToCreate; $i++) {
		    $this->call('db:seed', ['--class' => 'BoxSeeder']);
	    }
	    $endTime = Carbon::now();
	    $ellapsed = $endTime->diffInSeconds($start);
	    $this->info('Created requested boxes in '.$ellapsed.' seconds!');
    }
}
