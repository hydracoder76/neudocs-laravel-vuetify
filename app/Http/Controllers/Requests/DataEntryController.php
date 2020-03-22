<?php
/**
 * User: aho
 * Date: 2019-01-25
 * Time: 09:18
 */

namespace NeubusSrm\Http\Controllers\Requests;


use NeubusSrm\Http\Controllers\Controller;
use NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException;
use NeubusSrm\Services\PartService;
use Cache;
use Illuminate\Http\Request;
use NeubusSrm\Services\SettingService;
/**
 * Class TodoController
 * @package NeubusSrm\Http\Controllers\Requests
 */
class DataEntryController extends Controller
{

    /**
     * @var PartService
     */
    protected $partService;

    /**
     * @var SettingService
     */
    protected $settingService;

    /**
     * TodoController constructor.
     * @param PartService $partService
     * @param SettingService $settingService
     */
    public function __construct(PartService $partService, SettingService $settingService){
        $this->partService = $partService;
        $this->settingService = $settingService;
    }

    /**
     * @return string
     */
    public function index() {
        return view('requests.dataentry');
    }

    public function addBox() {
        return view('requests.dataentry');
    }


    public function addPart(Request $request) {
        if ($request->has('box')) {
            $box =  $request->input('box');
            if ($box == null)
                $box = '[]';
        }

        if ($request->has('project')) {
            $project =  $request->input('project');
            if ($project == null)
                $project = '[]';
        }

        return view('requests.dataentry.part', ['box' => $box, 'project' => $project]);

    }
}