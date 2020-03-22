<?php
/**
 * Project: mlawson
 * Date: 11/12/18
 * Time: 12:30 PM
 */

namespace NeubusSrm\Services;


use Illuminate\Cache\Repository;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use NeubusSrm\Lib\Exceptions\ProjectInvalidCredentialsException;
use NeubusSrm\Lib\Exceptions\ProjectNameException;
use NeubusSrm\Repositories\ProjectRepository;
use Crypt, Auth;
use NeubusSrm\Repositories\VerificationTokenRepository;
use Illuminate\Http\Request;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Lib\DataMappers\Formatter;
use Symfony\Component\Debug\Debug;
use NeubusSrm\Lib\Wrappers\Collections\ProjectsCollection;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ProjectService
 * @package NeubusSrm\Services
 */
class ProjectService extends NeuSrmService
{

	/**
	 * @var ProjectRepository
	 */
	private $projectRepository;

	private $verificationRepo;
	private $formatter;

	/**
	 * ProjectService constructor.
	 * @param ProjectRepository $projectRepository
	 * @param VerificationTokenRepository $verificationTokenRepository
	 */
	public function __construct(ProjectRepository $projectRepository, VerificationTokenRepository $verificationTokenRepository, Formatter $formatter) {
		$this->projectRepository = $projectRepository;
		$this->verificationRepo = $verificationTokenRepository;
        $this->formatter = $formatter;
	}


    /**
     * @return array
     * @throws \Throwable
     */

    public function index():array
    {
        $projects= $this->projectRepository->getProjects();
        $projectsColl = $projects->getCollection();
        return ['results' => $this->formatter->format($projectsColl, Formatter::MODE_PROJECTMANAGEMENT) , 'total' => $projects->total()];
    }

    public function create(array $request)
    {
            $params = [
                'project_name'          => $request['project_name'] ,
                'project_description'          => $request['project_description'] ,
                'company_id' => $request['company_id'],
                'media_type_id' => $request['media_type_id']
            ];

        return   $this->projectRepository->createProject($params);;
    }



    public function read($id)
    {
        return $this->projectRepository->findProject($id);
    }

    public function update(array $request, $id)
    {
        $params = [
            'project_name'          => $request['project_name'] ,
            'project_description'          => $request['project_description'] ,
            'company_id' => $request['company_id'],
            'media_type_id' => $request['media_type_id']
        ];



        return $this->projectRepository->updateProject($id,$params);

    }

    public function delete($id)
    {
        return $this->projectRepository->deleteProject($id);
    }

    /**
     * @param $projectID
     * @return Project
     */
    public function getProjectByID($projectID){
        return $this->projectRepository->findProject($projectID);
    }

     /**
     * @param $projectID
     * @return ProjectMediaTypes
     */
    public function projectMediaTypes($projectID)
    {
        return $this->projectRepository->getProjectMediaTypes($projectID);
    }

    public function getProjectByCompanyID($companyID){
        return $this->projectRepository->getProjectsByCompanyId($companyID);
    }

    /**
     * @param string $sortBy
     * @param string $order
     * @param string $keyword
     * @return array
     */
    public function projectSearch(string $sortBy, string $order, string $keyword) : array {
        $query = $this->projectRepository->searchQuery($keyword);
        $query = $this->projectRepository->orderQuery($query, $sortBy, $order);
        $projects = $this->projectRepository->projectSearch($query);
        return ['result' => $this->formatter->format($projects['result'], Formatter::MODE_PROJECTMANAGEMENT) , 'total' => $projects['total']];
    }
}
