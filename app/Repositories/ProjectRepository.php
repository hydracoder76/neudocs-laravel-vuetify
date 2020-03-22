<?php
/**
 * User: mlawson
 * Date: 11/12/18
 * Time: 2:15 PM
 */

namespace NeubusSrm\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use NeubusSrm\Http\Resources\ProjectCollection;
use NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Lib\Exceptions\ProjectNotFoundException;
use NeubusSrm\Models\Org\ProjectMediaType;
use NeubusSrm\Models\Org\Setting;
use phpDocumentor\Reflection\Types\Integer;
use NeubusSrm\Lib\Wrappers\Collections\ProjectsCollection;
/**
 * Class ProjectRepository
 * @package NeubusSrm\Repositories
 */
class ProjectRepository implements NeuSrmRepository
{
    /**
     * @var array
     */
    const SEARCH_ARR = ['project_name' => ['type' => 'nojoin', 'col' => 'project_name'],
        'project_description' => ['type' => 'nojoin', 'col' => 'project_description'],
        'company_name' => ['type' => 'join', 'table' => 'companies', 'col' => 'company_name', 'relation' => 'company',
            'foreignKey' => 'companies.id', 'localKey' => 'projects.company_id'],
        'project_owner_name' => ['type' => 'join', 'table' => 'users', 'col' => 'name', 'relation' => 'owner', 'foreignKey' => 'users.id', 'localKey' => 'projects.project_owner_id']];

    /**
     * @return string
     */
    public function getModelClass(): string {
        return Project::class;
    }

	/**
	 * @param string $name
	 * @return Project
	 * @throws \Throwable
	 */
    public function getProjectByName(string $name) : Project{
        $project = Project::whereProjectName($name)->first();
        throw_if($project == null,
            ProjectNotFoundException::class, 'No project found for this name');
        return $project;
    }

	/**
	 * @param string $companyId
	 * @return Collection
	 * @throws \Throwable
	 */
    public function getProjectsByCompanyId(string $companyId) : ProjectsCollection {

    	// TODO: company relation itself may not be needed
	    $projects = Project::whereCompanyId($companyId)->get();
	    throw_if($projects == null,
		    ProjectNotFoundException::class, 'No projects exist for this company');
	    $projectsColl = collect($projects);


	    return new ProjectsCollection($projectsColl);
    }

    /**
     * @param bool $canUseAnyUser
     * @return ProjectsCollection
     * @throws \Throwable
     */
    public function getAllProjects(bool $canUseAnyUser = true) : ProjectsCollection {
	    $projectsQuery = Project::with('company');
	    if (!$canUseAnyUser){
	        $role = Auth::user()->role;
	        if ($role == User::ROLE_CLIENT || $role == USER::ROLE_ADMIN) {
                $companyId = Auth::user()->company_id;
                $projectsQuery = $projectsQuery->where('company_id', $companyId);
            }
        }
	    $projects = $projectsQuery->get();
	    throw_if($projects->count() == 0,
		    ProjectNotFoundException::class, 'No projects exist for this user or in the system');
	    return $projects;
    }


	/**
	 * @param string $projectID
	 * @return Project
	 * @throws \Throwable
	 */
    public function getProjectByID(string $projectID) : Project
    {
        $project = Project::where('id', '=', $projectID)->first();
        throw_if($project == null,
            ProjectNotFoundException::class, 'No project found for this id');
        return $project;
    }

    /**
     * @return ProjectCollection
     * @throws \Throwable
     */
    public function getProjects()
    {        
        $projects = Project::with('company','owner','mediaTypes')->paginate(25);        
        throw_if($projects == null,
            NeuEntityNotFoundExcpetion::class, 'No projects exist for this company');
        return $projects;
    }


	/**
	 * @param array $attributes
	 * @return Project
	 */
    public function createProject(array $attributes) : ProjectsCollection
    {
        $result = Project::create($attributes);
        $result->mediaTypes()->attach($attributes['media_type_id'], ['created_on' =>  Carbon::now()->toDateTimeString()]);
        Setting::create(['project_id'=>$result->id, 'setting_key'=>'priority_yellow','value'=>Setting::YELLOW_DEFAULT,
                'label'=>'Days until yellow request']);
        Setting::create(['project_id'=>$result->id, 'setting_key'=>'priority_red','value'=>Setting::RED_DEFAULT,
            'label'=>'Days until red request']);

        $projectCompany = isset($result->company) ? $result->company->company_name : '';
        $projectOwner = isset($result->owner) ? $result->owner->name : '';
        $projectMediaTypeIds = [];
        $projectMediaTypes = [];        
        foreach ($result->mediaTypes as $item){
            $projectMediaTypeIds[] = $item->id;
            $projectMediaTypes[] = $item->type. '  ';   
        }
        $projectsColl = collect([
            'id' => $result->id,
            'project_name'=>$result->project_name,
            'project_description'=>$result->project_description,
            'company_id'=>$result->company_id,
            'company_name'=>$projectCompany,
            'project_owner_id'=>$result->project_owner_id,
            'project_owner_name'=>$projectOwner,
            'type' => $projectMediaTypes,
            'media_type_id' =>$projectMediaTypeIds
        ]);

        return new ProjectsCollection($projectsColl);
    }


	/**
	 * @param string $id
	 * @return Project
	 */
    public function findProject(string $id):Project
    {
        return Project::find($id);
    }


    /**
	 * @param string $id
	 * @return Array
	 */

    public function getProjectMediaTypes(string $id) : array
    {
        $project= Project::with('mediaTypes')->where('id', '=', $id)->first();
        return $project->mediaTypes->pluck('id')->toArray();
    }    
	/**
	 * @param string $id
	 * @param array $attributes
	 * @return bool
	 */
    public function updateProject(string $id,array $attributes):bool
    {
        $project = Project::where('id','=',$id)->first();
        $project->mediaTypes()->detach();
        $project->mediaTypes()->attach($attributes['media_type_id'], ['created_on' =>  Carbon::now()->toDateTimeString()]);
        unset($attributes['media_type_id']);
        return Project::where('id','=',$id)->update($attributes);
    }

    /**
     * @param $id
     * @throws NeuEntityNotFoundException
     */
    public function deleteProject($id) : void {
        try {
            Project::whereId($id)->delete();
        }
        catch (\Throwable $exception) {
            \Log::error($exception->getMessage());
            throw new NeuEntityNotFoundException('Could not find a project with this id');
        }
    }

    /**
     * @param string $keyword
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function searchQuery(string $keyword) : Builder
    {
        $query = Project::query();
        if ($keyword != null && $keyword != '') {
            foreach (ProjectRepository::SEARCH_ARR as $key => $arr) {
                if ($arr['type'] == 'nojoin') {
                    $query = $query->orWhere($arr['col'], 'ilike', '%' . $keyword . '%');
                } else {
                    $query = $query->orWhereHas($arr['relation'], function ($queryHas) use ($arr, $keyword){
                        $queryHas->where($arr['col'], 'ilike', '%' . $keyword . '%');
                    });
                }
            }
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @param string $sortBy
     * @param string $order
     * @return Builder
     */
    public function orderQuery(Builder $query, string $sortBy, string $order) : Builder {
        if ($sortBy != null && $sortBy != ''){
            $arr = ProjectRepository::SEARCH_ARR[$sortBy];
            if ($arr['type'] == 'nojoin'){
                $query = $query->orderBy($arr['col'], $order);
            }
            else{
                $query = $query->leftJoin($arr['table'], $arr['foreignKey'], '=', $arr['localKey'])
                    ->orderBy($arr['table'] . '.' . $arr['col'], $order);
            }
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @return array
     * @throws \Throwable
     */
    public function projectSearch(Builder $query) : array {
        $results = $query->select('projects.*')->paginate(25);
        neu_throw_if($results == null || $results->isEmpty(),
            NeuEntityNotFoundException::class, 'There are no projects for this query');
        return ['result' => $results->getCollection(), 'total' => $results->total()];
    }

}
