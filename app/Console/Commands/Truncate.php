<?php

namespace NeubusSrm\Console\Commands;

use Illuminate\Console\Command;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Indexing\Box;
use NeubusSrm\Models\Indexing\IndexType;
use NeubusSrm\Models\Indexing\Part;
use NeubusSrm\Models\Indexing\PartIndex;
use NeubusSrm\Models\Org\Company;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Models\Org\Request;
use NeubusSrm\Models\Relational\RequestPart;

/**
 * Class Truncate
 * @package NeubusSrm\Console\Commands
 */
class Truncate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'neusrm:truncate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear neubussrm database';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    /**
     * CreateUser constructor.
     * @param User $user
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
            RequestPart::truncate();
            Request::truncate();
            PartIndex::truncate();
            Part::truncate();
            Box::truncate();
            IndexType::truncate();
            Project::truncate();
            User::truncate();
            Company::truncate();
            $this->info('Database cleared.');

    }
}
