<?php

namespace NeubusSrm\Console\Commands;

use Illuminate\Console\Command;
use NeubusSrm\Models\Org\Company;

/**
 * Class CreateCompany
 * @package NeubusSrm\Console\Commands
 */
class CreateCompany extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'neusrm:company';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new company to the system';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    /**
     * @var Company
     */
    private $company;

    /**
     * CreateCompany constructor.
     * @param Company $company
     */
    public function __construct(Company $company)
    {
        parent::__construct();
        $this->company = $company;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Creating company...');
        $this->company->company_name = $this->ask('Company name');
        $this->company->company_access_type = $this->choice('What is the business role for the company?',
            ['client', 'neubus', 'it']);
        $this->info('A user has to be available for a contact to be added, but users cannot be added
        unless there are companies, so it has to be added later.');
        $this->company->save();
        $this->info('Company created with id: ' . $this->company->id);
    }
}
