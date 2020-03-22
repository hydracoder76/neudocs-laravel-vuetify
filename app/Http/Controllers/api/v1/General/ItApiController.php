<?php
namespace NeubusSrm\Http\Controllers\api\v1\General;


use Illuminate\Http\Request;
use NeubusSrm\Http\Controllers\api\v1\ApiController;
use NeubusSrm\Http\Requests\GetSettingsRequest;
use NeubusSrm\Http\Requests\SettingRequest;
use NeubusSrm\Lib\Constants\HttpConstants;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use NeubusSrm\Services\SettingService;

/**
 * Class ItApiController
 * @package NeubusSrm\Http\Controllers\api\v1\General
 */
class ItApiController extends ApiController
{
    /**
     * @var SettingService
     */
    protected $settingService;

    /**
     * ItApiController constructor.
     * @param SettingService $service
     */
    public function __construct(SettingService $service){
        $this->settingService = $service;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSettingByProject(GetSettingsRequest $request){
        $settings = $this->settingService->getSettingsByProject($request->input('project_id'));
        return $this->apiSuccess('Settings retrieved successfully', ['results' => $settings] );
    }

    /**
     * @param SettingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
   public function saveSetting(SettingRequest $request){
        $this->settingService->setSettings($request->input('settings'), $request->input('project_id'));
       return $this->apiSuccess('Settings Saved Successfully' );
   }

}