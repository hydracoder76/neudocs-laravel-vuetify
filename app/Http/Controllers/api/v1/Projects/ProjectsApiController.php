<?php
/**
 * Project: mlawson
 * Date: 2018-11-29
 * Time: 20:41
 */

namespace NeubusSrm\Http\Controllers\api\v1\Projects;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use NeubusSrm\Http\Controllers\api\v1\ApiController;
use NeubusSrm\Http\Requests\SearchOrderRequest;
use NeubusSrm\Lib\Constants\HttpConstants;
use NeubusSrm\Lib\DataMappers\Formatter;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use NeubusSrm\Services\RequestService;
use NeubusSrm\Http\Requests\ProjectRequest;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Services\ProjectService;
use NeubusSrm\Http\Resources\ProjectCollection;
use NeubusSrm\Http\Requests\ProjectEditRequest;

/**
 * Class ProjectsApiController
 * @package NeubusSrm\Http\Controllers\api\v1\Projects
 */
class ProjectsApiController extends ApiController
{

	/**
	 * @var RequestService
	 */
	private $requestService;

    /**
     * @var ProjectService
     */
    private $projectService;

	/**
	 * @var Formatter
	 */
	private $formatter;

    /**
     * ProjectsApiController constructor.
     * @param RequestService $requestService
     * @param Formatter $formatter
     * @param ProjectService $projectService
     */
	public function __construct(RequestService $requestService, Formatter $formatter, ProjectService $projectService) {
		$this->requestService = $requestService;
		$this->formatter = $formatter;
        $this->projectService = $projectService;
	}

	/**
	 * @param null $format
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 */
    public function all($format = Formatter::MODE_PROJECT) {
        try {
            $projects = $this->requestService->getAllProjects(false, false);
            return $this->apiSuccess('Projects retrieved', $this->formatter->format($projects, $format));
		}
		catch (NeuSrmException | \Throwable $exception) {
			if ($exception instanceof NeuSrmException) {
				return $this->apiErrorWithException($exception);
			}
			\Log::error($exception->getMessage());
			return $this->apiError('An internal error occurred, please try again later');
		}
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($format = null)
    {
        try {
            $projects = $this->projectService->index();
            return $this->apiSuccess('Projects retrieved', ['result' => $projects['results'], 'total' => $projects['total']]);
        }
        catch (NeuSrmException | \Throwable $exception) {
            if ($exception instanceof NeuSrmException) {
                return $this->apiErrorWithException($exception);
            }            
            \Log::error($exception->getMessage());
            return $this->apiError('An internal error occurred, please try again later');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request)
    {
        try {
            $result = $this->projectService->create($request->all());
            return $this->apiSuccess('Project created', ['result' => $result]);
        }
        catch (NeuSrmException $exception) {
            return $this->apiErrorWithException($exception);
        }
        catch (\Throwable $exception) {
            \Log::error($exception->getMessage());
            return $this->apiError('An internal error occurred, please try again later');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        try {
            $result = $this->projectService->read($id);
            return $this->apiSuccess('Projects retrieved', [$result], HttpConstants::HTTP_OK);
        } catch (NeuSrmException $exception) {
            return $this->apiErrorWithException($exception);
        } catch (\Throwable $exception) {
            \Log::error($exception->getMessage());
            return $this->apiError('An internal error occurred, please try again later');
        }
    }

    /**
     * @param ProjectEditRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProjectEditRequest $request, $id)
    {
        try {
            $this->projectService->update($request->all(),$id);
            return $this->apiSuccess('Update Project Successful');
        }
        catch (NeuSrmException $exception) {
            return $this->apiErrorWithException($exception);
        }
        catch (\Throwable $exception) {
            \Log::error($exception->getMessage());
            return $this->apiError('An internal error occurred, please try again later');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        try{
            $this->projectService->delete($project->id);
            return $this->apiSuccess('Delete Project Successful');
        }
        catch (NeuSrmException $exception) {
            return $this->apiErrorWithException($exception);
        }
        catch (\Throwable $exception) {
            \Log::error($exception->getMessage());
            return $this->apiError('An internal error occurred, please try again later');
        }
    }

    /**
     * @param string $companyId
     * @return \Illuminate\Http\JsonResponse
     */
    public function byCompanyId(string $companyId) {
        try {
            $project = $this->projectService->getProjectByCompanyID($companyId);
            return $this->apiSuccess('projects retrieved', [$project]);
        }
        catch (NeuSrmException $exception) {
            return $this->apiErrorWithException($exception);
        }
        catch (\Throwable $exception) {
            \Log::error($exception->getMessage());
            return $this->apiError('An internal error occurred, please try again later');
        }
    }

    /**
     * @param SearchOrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function projectSort(SearchOrderRequest $request) : JsonResponse {
        try {
            $sortBy = $request->input('sortBy') ? $request->input('sortBy') : '';
            $order = $request->input('order');
            $keyWord = $request->input('keyword') ? $request->input('keyword') : '';
            $projects = $this->projectService->projectSearch($sortBy, $order, $keyWord);
            return $this->apiSuccess('Projects retrieved', ['result' => $projects['result'], 'total' => $projects['total']]);
        }
        catch (NeuSrmException $exception) {
            return $this->apiErrorWithException($exception);
        }
        catch (\Throwable $exception) {
            \Log::error($exception->getMessage());
            return $this->apiError('An internal error occurred, please try again later');
        }
    }
}
