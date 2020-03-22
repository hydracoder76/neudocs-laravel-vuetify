<?php

namespace NeubusSrm\Services;

use NeubusSrm\Lib\Exceptions\NeuGenericException;
use NeubusSrm\Models\Org\Setting;
use NeubusSrm\Repositories\SettingRepository;

/**
 * Class SettingService
 * @package NeubusSrm\Services
 */
class SettingService extends NeuSrmService
{
    /**
     * @var SettingRepository
     */
    protected $settingRepo;

    /**
     * SettingService constructor.
     * @param SettingRepository $settingRepo
     */
    public function __construct(SettingRepository $settingRepo) {
        $this->settingRepo = $settingRepo;
    }

    /**
     * @param array $settings
     * @param string $projectId
     * @throws \Throwable|NeuGenericException
     */
    public function setSettings(array $settings, string $projectId){
        throw_if((int)$settings['priority_yellow']['value'] > (int)$settings['priority_red']['value'],
            NeuGenericException::class, 'Setting for yellow cannot be greater than that for red');
        foreach($settings as $key => $setting){
            $this->settingRepo->setSetting($key, $setting['value'], $projectId);
        }
    }

    /**
     * @param string $projectId
     * @return array
     * @throws \NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException
     * @throws \Throwable
     */
    public function getSettingsByProject(string $projectId) : array{
        $settingsCollect = $this->settingRepo->getSettingsByProject($projectId);
        $settings = $settingsCollect->keyBy('setting_key')->toArray();
        return $settings;
    }

    /**
     * @param string $key
     * @param string $projectId
     * @return string
     * @throws \NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException
     * @throws \Throwable
     */
    public function getSettingByKey(string $key, string $projectId) : string {
        return $this->settingRepo->getSettingByKey($key, $projectId);
    }
}
