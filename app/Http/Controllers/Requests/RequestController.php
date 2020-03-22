<?php

namespace NeubusSrm\Http\Controllers\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use NeubusSrm\Http\Controllers\Controller;
use NeubusSrm\Services\RequestService;
use NeubusSrm\Lib\Wrappers\Collections\PartsCollection;

/**
 * Class RequestController
 * @package NeubusSrm\Http\Controllers\Requests
 */
class RequestController extends Controller
{
    /**
     * @var RequestService
     */
    protected $requestService;

    /**
     * RequestController constructor.
     * @param RequestService $requestService
     */
    public function __construct(RequestService $requestService){
        $this->requestService = $requestService;
    }

    /**
     * @param $projectName
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view('requests.requests');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function newRequest($projectName){
        $project = $this->requestService->getProjectByName($projectName);
        $indexLists = $project->indexes;
        $indexes = [];
        foreach($indexLists as $index){
            $indexes[$index->index_internal_name] = ['label' => $index->index_label, 'name' => $index->index_internal_name];
        }
        return view('requests.newrequest', ['indexes' => $indexes, 'projectID' => $project->id]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function review() : View {
        return view('requests.review');
    }
}
