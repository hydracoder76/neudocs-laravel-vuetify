<?php
/**
 * User: mlawson
 * Date: 2019-01-14
 * Time: 09:18
 */

namespace NeubusSrm\Http\Controllers\Requests;


use NeubusSrm\Http\Controllers\Controller;
use NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException;
use NeubusSrm\Services\PartService;
use Cache;
use Illuminate\Http\Request;

/**
 * Class TodoController
 * @package NeubusSrm\Http\Controllers\Requests
 */
class TodoController extends Controller
{

    /**
     * @var PartService
     */
    protected $partService;

    /**
     * TodoController constructor.
     * @param PartService $partService
     * @param SettingService $settingService
     */
    public function __construct(PartService $partService){
        $this->partService = $partService;
    }

    /**
     * @return string
     */
    public function index() {
        return view('requests.todo', ['pageTitle' => 'Request To Do']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewUpload(Request $request) {
        $parts = [];
        if ($request->has('partKey')) {
            $partKey = $request->input('partKey');
            $parts = Cache::get('neu-' . $partKey);
        }

        $partData = [];
        $requestParts = [];

        //TODO: move data from url to cache
        if ($request->has('parts')){
            $requestParts = json_decode($request->input('parts'));
            $partData = array_unique(array_column($requestParts, 'part_id'));
        }
        $projectId = '';
        if ($request->has('project'))
            $projectId = $request->input('project');
        $partList = $this->partService->getPartFiles($partData, $requestParts);

        // TODO: will need a formatter
        return view('requests.uploads.upload', ['pageTitle' => 'Box Upload',
            'parts' => $partList,
            'requestParts' => $requestParts,
            'projectId' => $projectId,
            'packetSize' => config('srm.upload_packet_size')]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewScan(Request $request) {
        $parts = '[]';
        if ($request->has('partKey')) {
            $parts = Cache::get('neu-' . $request->input('partKey'));
            if ($parts == null)
                $parts = '[]';
        }
        return view('requests.scan', ['parts' => $parts]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewCompleted() {
        return view('requests.completed');
    }
}
