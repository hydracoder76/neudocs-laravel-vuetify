<?php

namespace NeubusSrm\Console\Commands;

use Illuminate\Console\Command;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Indexing\Box;
use NeubusSrm\Models\Org\Company;
use NeubusSrm\Models\Org\Project;

/**
 * Class CreateBox
 * @package NeubusSrm\Console\Commands
 */
class CreateBox extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'neusrm:box';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create boxes for a project';

	/**
	 * @var Box
	 */
    private $boxEntity;
    /**
     * Create a new command instance.
     *
     * @param Box $boxEntity
     * @return void
     */
    public function __construct(Box $boxEntity)
    {
        parent::__construct();
        $this->boxEntity = $boxEntity;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Create box for a project');
        if (Project::count() == 0) {
        	$this->info('No projects found, please create');
        	$this->call('neusrm:project');
        }


        $this->boxEntity->box_name = $this->ask('Box Name?');
        $projects = Project::all(['project_name']);
        $projectNames = array_pluck($projects, 'project_name');
        $selectedProjectName = $this->choice('Which project should this be assigned to?', $projectNames);
	    $selectedProjectId = Project::whereProjectName($selectedProjectName)->first()->id;
	    $this->boxEntity->project_id = $selectedProjectId;

	    $users = User::all(['email']);
	    $userNames = array_pluck($users, 'email');
	    $userLastWorkedOn = $this->choice('Who last worked on this box?', $userNames);
	    $selectedUser = User::whereEmail($userLastWorkedOn)->first();
	    $selectedUserId = $selectedUser->id;
	    $this->boxEntity->created_by = $selectedUserId;
	    $this->boxEntity->updated_by = $selectedUserId;
	    $this->boxEntity->company_id = $selectedUser->company_id;

	    $this->boxEntity->box_status = $this->choice('What is the status of the box?',
		    ['NEW', 'DATA_ENTRY', 'CLOSED']);
	    $this->boxEntity->box_location_code = $this->ask('What is the location code?', '');


	    $this->boxEntity->save();


	    $this->info('Box created with id: ' . $this->boxEntity->id);

    }
}
