<?php

namespace NeubusSrm\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

/**
 * Class SeedUsersTable
 * @package NeubusSrm\Console\Commands
 */
class SeedUsersTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'neusrm:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $this->info('Generating users');
        $numToCreate = intval($this->ask('How many users do you need?'));
	    $start = Carbon::now();
        for ($i = 0; $i < $numToCreate; $i++) {
        	$this->call('db:seed');
        }
        $endTime = Carbon::now();
        $ellapsed = $endTime->diffInSeconds($start);
        $this->info('Created requested users in '.$ellapsed.' seconds!');


    }
}
