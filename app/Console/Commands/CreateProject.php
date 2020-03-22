<?php

namespace NeubusSrm\Console\Commands;

use Illuminate\Console\Command;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Org\Company;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Models\Org\Setting;

/**
 * Class CreateProject
 * @package NeubusSrm\Console\Commands
 */
class CreateProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'neusrm:project';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a project to the SRM';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    /**
     * @var Project
     */
    private $project;

    /**
     * @var Setting
     */
    private $settingYellow;

    /**
     * @var Setting
     */
    private $settingRed;

    /**
     * CreateProject constructor.
     * @param Project $project
     * @param Setting $settingYellow
     * @param Setting $settingRed
     */
    public function __construct(Project $project, Setting $settingYellow, Setting $settingRed)
    {
        parent::__construct();
        $this->project = $project;
        $this->settingYellow = $settingYellow;
        $this->settingRed = $settingRed;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Creating a project...');
        if (Company::count() == 0) {
        	$this->info('No company found, creating...');
        	$this->call('neusrm:company');
        }
        if (User::count() == 0) {
        	$this->info('No users found, creating...');
        	$this->call('neusrm:user');
        }

        $this->project->project_name = $this->ask('Project name');
        $this->project->project_description = $this->ask('Project description(optional)');
        $companies = Company::whereHas('users')->get();
        $selectedCompany = $this->choice('Which company does this project belong to', array_pluck($companies,
            'company_name'));
        // TODO: add a unique attribute to the company name? Need to use relational stuff honestly
        $companyEntity = Company::whereCompanyName($selectedCompany)->first();
        $this->project->company_id = $companyEntity->id;
        $userForCompany = User::first();

        $this->project->project_owner_id = $userForCompany->id;
        $this->project->save();

        $this->settingYellow->project_id = $this->project->id;
       $this->settingRed->project_id = $this->project->id;
       $this->settingYellow->setting_key = 'priority_yellow';
       $this->settingRed->setting_key = 'priority_red';
       $this->settingYellow->label = 'Days until yellow request';
       $this->settingRed->label = 'Days until red request';
       $this->settingYellow->value = Setting::YELLOW_DEFAULT;
       $this->settingRed->value = Setting::RED_DEFAULT;

       $this->settingYellow->save();
       $this->settingRed->save();

        $this->info('Project created with id: ' . $this->project->id);
    }
}
